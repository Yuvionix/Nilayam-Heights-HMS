<?php
require_once(__DIR__ . "/Includes/session.php");

if (isset($_SESSION['pg_logged']) && $_SESSION['pg_logged'] === true) {
    if (isset($_SESSION['pg_role']) && $_SESSION['pg_role'] === 'admin') {
        header("Location: admin/dashboard.php");
        exit;
    } else {
        header("Location: student/profile.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>