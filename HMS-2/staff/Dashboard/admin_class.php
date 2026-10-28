<?php
require_once("../../Includes/config.php"); 
require_once("../../Includes/session.php"); 

require_role(array('staff', 'admin'), '../../login.php');

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

	function save_user(){
		$id = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
		$person_name = trim(isset($_POST['person_name']) ? $_POST['person_name'] : '');

		if (empty($person_name)) {
			return 0;
		}

		$stmt_chk = $this->db->prepare("SELECT id FROM registry WHERE person_name = ? AND id != ?");
		$stmt_chk->bind_param("si", $person_name, $id);
		$stmt_chk->execute();
		$stmt_chk->store_result();
		if ($stmt_chk->num_rows > 0) {
			$stmt_chk->close();
			return 2;
		}
		$stmt_chk->close();

		if ($id === 0) {
			$stmt = $this->db->prepare("INSERT INTO registry (person_name, created_at) VALUES (?, NOW())");
			$stmt->bind_param("s", $person_name);
			$save = $stmt->execute();
			$stmt->close();
			if ($save) return 1;
		} else {
			$stmt = $this->db->prepare("UPDATE registry SET person_name = ? WHERE id = ?");
			$stmt->bind_param("si", $person_name, $id);
			$save = $stmt->execute();
			$stmt->close();
			if ($save) return 1;
		}
		return 0;
	}

	function delete_user(){
		$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($id > 0) {
			$stmt = $this->db->prepare("DELETE FROM registry WHERE id = ?");
			$stmt->bind_param("i", $id);
			$delete = $stmt->execute();
			$stmt->close();
			if ($delete) return 1;
		}
		return 0;
	}									
}
?>
