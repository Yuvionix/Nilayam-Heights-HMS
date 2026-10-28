<?php 
require_once("../../Includes/config.php"); 
require_once("../../Includes/session.php"); 

require_role(array('member', 'admin'), '../../login.php');

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
		// Members are not allowed to create or edit billing structures
		if (!isset($_SESSION['account']) || $_SESSION['account'] !== 'admin') {
			return json_encode(array("status" => 0, "error" => "Permission denied"));
		}
		return json_encode(array("status" => 0));
	}

	function delete_billing(){
		// Members are not allowed to delete bills
		if (!isset($_SESSION['account']) || $_SESSION['account'] !== 'admin') {
			return 0;
		}
		return 0;
	}

	function save_payment(){
		$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		$amount_payed = isset($_POST['amount_payed']) ? (float)$_POST['amount_payed'] : 0.0;
		$amount_change = isset($_POST['amount_change']) ? (float)$_POST['amount_change'] : 0.0;

		if ($id > 0) {
			// If logged in as member, verify this bill belongs to the logged in member
			if ($_SESSION['account'] === 'member') {
				$my_uid = isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : 0;
				$chk = $this->db->prepare("SELECT id FROM billing WHERE id = ? AND member_id = ?");
				$chk->bind_param("ii", $id, $my_uid);
				$chk->execute();
				$chk->store_result();
				if ($chk->num_rows === 0) {
					$chk->close();
					return 0; // Unauthorized bill payment attempt
				}
				$chk->close();
			}

			$stmt = $this->db->prepare("UPDATE billing SET amount_payed = ?, amount_change = ?, status = 1 WHERE id = ?");
			$stmt->bind_param("ddi", $amount_payed, $amount_change, $id);
			$save = $stmt->execute();
			$stmt->close();
			if ($save) {
				return 1;
			}
		}
		return 0;
	}
}
?>
