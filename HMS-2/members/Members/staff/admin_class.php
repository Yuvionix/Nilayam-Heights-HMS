<?php
require_once("../../../Includes/config.php"); 
require_once("../../../Includes/session.php"); 

require_role(array('member', 'admin'), '../../../login.php');

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
		if (!isset($_SESSION['account']) || $_SESSION['account'] !== 'admin') {
			return 0;
		}
		return 0;
	}

	function delete_user(){
		if (!isset($_SESSION['account']) || $_SESSION['account'] !== 'admin') {
			return 0;
		}
		return 0;
	}									
}
?>
