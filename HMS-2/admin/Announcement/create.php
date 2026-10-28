<?php
require_once("../../Includes/config.php");
require_once("../../Includes/session.php");

// Only administrators are authorized to publish announcements
require_role('admin', '../../login.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $name = trim(isset($_POST['name']) ? $_POST['name'] : '');
    $message = trim(isset($_POST['message']) ? $_POST['message'] : '');

    if (!empty($name) && !empty($message)) {
        $stmt = mysqli_prepare($con, "INSERT INTO announcement (announcement_subject, announcement_text, created_at) VALUES (?, ?, NOW())");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $name, $message);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header("Location: admin_annou.php");
    exit;
} else {
    header("Location: admin_annou.php");
    exit;
}
?>
