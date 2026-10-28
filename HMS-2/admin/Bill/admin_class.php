<?php 
require_once("../../Includes/config.php"); 
require_once("../../Includes/session.php"); 

// Only administrators are permitted to create, edit, pay, or delete billing records
require_role('admin', '../../login.php');

Class Action {
	private $db;

	public function __construct() {
		ob_start();
		global $con;
		$this->db = $con;
	}

	function __destruct() {
	    ob_end_flush();
	}

	function save_billing(){
		$member_id = isset($_POST['member_id']) ? (int)$_POST['member_id'] : 0;
		$billing_month = isset($_POST['billing_date']) && preg_match('/^\d{4}-\d{2}$/', $_POST['billing_date']) ? $_POST['billing_date'] : date('Y-m');
		$billing_date = $billing_month . '-01';
		$total_amount = isset($_POST['o_amount']) ? (float)$_POST['o_amount'] : 0.0;
		$id = !empty($_POST['id']) ? (int)$_POST['id'] : 0;

		$amount = isset($_POST['amount']) && is_array($_POST['amount']) ? $_POST['amount'] : array();
		$rate = isset($_POST['rate']) && is_array($_POST['rate']) ? $_POST['rate'] : array();
		$consumption = isset($_POST['consumption']) && is_array($_POST['consumption']) ? $_POST['consumption'] : array();
		$reading = isset($_POST['reading']) && is_array($_POST['reading']) ? $_POST['reading'] : array();
		$previous_amount = isset($_POST['previous_amount']) && is_array($_POST['previous_amount']) ? $_POST['previous_amount'] : array();
		$previous_consumption = isset($_POST['previous_consumption']) && is_array($_POST['previous_consumption']) ? $_POST['previous_consumption'] : array();
		$previous_reading = isset($_POST['previous_reading']) && is_array($_POST['previous_reading']) ? $_POST['previous_reading'] : array();

		if(empty($id)){
			$stmt = $this->db->prepare("INSERT INTO billing (member_id, billing_date, total_amount, status) VALUES (?, ?, ?, 0)");
			$stmt->bind_param("isd", $member_id, $billing_date, $total_amount);
			$save = $stmt->execute();
			$new_id = $stmt->insert_id;
			$stmt->close();
			
			if($save && $new_id > 0){
				$stmt_bill = $this->db->prepare("INSERT INTO bills (billing_id, type, reading, consumption, rate, previous_reading, previous_consumption, amount, previous_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
				foreach($amount as $k => $v){
					$type = (int)$k;
					$v_amount = (float)$v;
					$v_rate = isset($rate[$k]) ? (float)$rate[$k] : 0.0;
					$v_cons = isset($consumption[$k]) ? (int)$consumption[$k] : 0;
					$v_read = isset($reading[$k]) ? (int)$reading[$k] : 0;
					$v_prev_amt = isset($previous_amount[$k]) ? (float)$previous_amount[$k] : 0.0;
					$v_prev_cons = isset($previous_consumption[$k]) ? (int)$previous_consumption[$k] : 0;
					$v_prev_read = isset($previous_reading[$k]) ? (int)$previous_reading[$k] : 0;

					$stmt_bill->bind_param("iiiddiddi", $new_id, $type, $v_read, $v_cons, $v_rate, $v_prev_read, $v_prev_cons, $v_amount, $v_prev_amt);
					$stmt_bill->execute();
				}
				$stmt_bill->close();
				return json_encode(array("status"=>1,"id"=>$new_id));
			}
		} else {
			$stmt = $this->db->prepare("UPDATE billing SET member_id = ?, billing_date = ?, total_amount = ? WHERE id = ?");
			$stmt->bind_param("isdi", $member_id, $billing_date, $total_amount, $id);
			$save = $stmt->execute();
			$stmt->close();

			if($save){
				$stmt_up = $this->db->prepare("UPDATE bills SET amount = ?, rate = ?, consumption = ?, reading = ?, previous_amount = ?, previous_consumption = ?, previous_reading = ? WHERE type = ? AND billing_id = ?");
				foreach($amount as $k => $v){
					$type = (int)$k;
					$v_amount = (float)$v;
					$v_rate = isset($rate[$k]) ? (float)$rate[$k] : 0.0;
					$v_cons = isset($consumption[$k]) ? (int)$consumption[$k] : 0;
					$v_read = isset($reading[$k]) ? (int)$reading[$k] : 0;
					$v_prev_amt = isset($previous_amount[$k]) ? (float)$previous_amount[$k] : 0.0;
					$v_prev_cons = isset($previous_consumption[$k]) ? (int)$previous_consumption[$k] : 0;
					$v_prev_read = isset($previous_reading[$k]) ? (int)$previous_reading[$k] : 0;

					$stmt_up->bind_param("ddiddiiii", $v_amount, $v_rate, $v_cons, $v_read, $v_prev_amt, $v_prev_cons, $v_prev_read, $type, $id);
					$stmt_up->execute();
				}
				$stmt_up->close();
				return json_encode(array("status"=>1,"id"=>$id));
			}
		}
		return json_encode(array("status"=>0));
	}

	function delete_billing(){
		$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if($id > 0){
			$stmt1 = $this->db->prepare("DELETE FROM billing WHERE id = ?");
			$stmt1->bind_param("i", $id);
			$delete = $stmt1->execute();
			$stmt1->close();

			$stmt2 = $this->db->prepare("DELETE FROM bills WHERE billing_id = ?");
			$stmt2->bind_param("i", $id);
			$delete2 = $stmt2->execute();
			$stmt2->close();

			if($delete && $delete2){
				return 1;
			}
		}
		return 0;
	}

	function save_payment(){
		$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$amount_payed = isset($_POST['amount_payed']) ? (float)$_POST['amount_payed'] : 0.0;
		$amount_change = isset($_POST['amount_change']) ? (float)$_POST['amount_change'] : 0.0;

		if($id > 0){
			$stmt = $this->db->prepare("UPDATE billing SET amount_payed = ?, amount_change = ?, status = 1 WHERE id = ?");
			$stmt->bind_param("ddi", $amount_payed, $amount_change, $id);
			$save = $stmt->execute();
			$stmt->close();
			if($save){
				return 1;
			}
		}
		return 0;
	}
}
?>
