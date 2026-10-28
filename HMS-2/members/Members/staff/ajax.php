<?php
ob_start();
require_once("../../../Includes/config.php");
require_once("../../../Includes/session.php");

require_role('admin', '../../../login.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';
include 'admin_class.php';
$crud = new Action();

if ($action === 'save_user') {
	$save = $crud->save_user();
	if ($save)
		echo $save;
} elseif ($action === 'delete_user') {
	$save = $crud->delete_user();
	if ($save)
		echo $save;
} else {
	http_response_code(400);
	echo "Invalid action";
}
?>
