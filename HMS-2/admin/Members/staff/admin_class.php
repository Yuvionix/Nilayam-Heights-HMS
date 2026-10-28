<?php
require_once("../../../Includes/config.php"); 
require_once("../../../Includes/session.php"); 

require_role('admin', '../../../login.php');

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
		$username = trim(isset($_POST['username']) ? $_POST['username'] : '');
		$email = trim(isset($_POST['email']) ? $_POST['email'] : '');
		$password = isset($_POST['password']) ? $_POST['password'] : '';

		if (empty($username) || empty($email)) {
			return 0;
		}

		// Check for duplicate username in staff with prepared statement
		$stmt_chk = $this->db->prepare("SELECT id FROM staff WHERE username = ? AND id != ?");
		$stmt_chk->bind_param("si", $username, $id);
		$stmt_chk->execute();
		$stmt_chk->store_result();
		if ($stmt_chk->num_rows > 0) {
			$stmt_chk->close();
			return 2;
		}
		$stmt_chk->close();

		if ($id === 0) {
			$hash = password_hash(!empty($password) ? $password : 'password', PASSWORD_DEFAULT);
			$stmt = $this->db->prepare("INSERT INTO staff (email, username, password) VALUES (?, ?, ?)");
			$stmt->bind_param("sss", $email, $username, $hash);
			$save = $stmt->execute();
			$stmt->close();
			if ($save) return 1;
		} else {
			if (!empty($password)) {
				$hash = password_hash($password, PASSWORD_DEFAULT);
				$stmt = $this->db->prepare("UPDATE staff SET email = ?, username = ?, password = ? WHERE id = ?");
				$stmt->bind_param("sssi", $email, $username, $hash, $id);
			} else {
				$stmt = $this->db->prepare("UPDATE staff SET email = ?, username = ? WHERE id = ?");
				$stmt->bind_param("ssi", $email, $username, $id);
			}
			$save = $stmt->execute();
			$stmt->close();
			if ($save) return 1;
		}
		return 0;
	}

	function delete_user(){
		$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
		if ($id > 0) {
			$stmt = $this->db->prepare("DELETE FROM staff WHERE id = ?");
			$stmt->bind_param("i", $id);
			$delete = $stmt->execute();
			$stmt->close();
			if ($delete) return 1;
		}
		return 0;
	}									
}
?>
