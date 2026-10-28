<?php
ob_start();
require_once("../../Includes/config.php");
require_once("../../Includes/session.php");

require_role(array('member', 'admin'), '../../login.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';
include 'admin_class.php';
$crud = new Action();

if ($action === "save_payment") {
	$save = $crud->save_payment();
	if ($save)
		echo $save;
} else {
	http_response_code(403);
	echo "Unauthorized action for member account.";
}
?>
