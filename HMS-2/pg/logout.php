<?php
require_once(__DIR__ . "/Includes/session.php");

// Unset PG session variables
unset($_SESSION['pg_logged']);
unset($_SESSION['pg_role']);
unset($_SESSION['pg_student_id']);
unset($_SESSION['pg_admin_id']);
unset($_SESSION['pg_name']);
unset($_SESSION['pg_email']);
unset($_SESSION['pg_username']);

header("Location: login.php");
exit;
?>