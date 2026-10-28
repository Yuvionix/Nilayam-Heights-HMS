<?php
ob_start();
require_once("../../Includes/config.php");
require_once("../../Includes/session.php");

// Only administrators may perform billing AJAX actions
require_role('admin', '../../login.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';
include 'admin_class.php';
$crud = new Action();

if ($action === "save_billing") {
	$save = $crud->save_billing();
	if ($save)
		echo $save;
} elseif ($action === "delete_billing") {
	$save = $crud->delete_billing();
	if ($save)
		echo $save;
} elseif ($action === "save_payment") {
	$save = $crud->save_payment();
	if ($save)
		echo $save;
} else {
	http_response_code(400);
	echo "Invalid action";
}
?>
