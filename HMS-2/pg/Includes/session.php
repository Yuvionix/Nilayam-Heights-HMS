<?php
require_once(__DIR__ . "/../../Includes/config.php");

// Initialize session securely
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    session_start();
}

// Security headers
if (!headers_sent()) {
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}

/**
 * Require PG authenticated role
 */
function require_pg_role($allowed_roles = array('student', 'admin'), $redirect = '../login.php') {
    if (!is_array($allowed_roles)) {
        $allowed_roles = array($allowed_roles);
    }

    if (!isset($_SESSION['pg_logged']) || $_SESSION['pg_logged'] !== true) {
        header("Location: " . $redirect);
        exit;
    }

    $current_role = isset($_SESSION['pg_role']) ? $_SESSION['pg_role'] : '';
    if (!in_array($current_role, $allowed_roles, true)) {
        http_response_code(403);
        echo "<!DOCTYPE html><html><head><title>Access Denied</title>";
        echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'></head>";
        echo "<body class='bg-light text-center pt-5'><div class='card p-5 mx-auto' style='max-width:480px;'>";
        echo "<h4 class='text-danger'>403 - Unauthorized Access</h4><p>You do not have access to this PG portal area.</p>";
        echo "<a href='" . htmlspecialchars($redirect) . "' class='btn btn-primary'>Back to PG Login</a></div></body></html>";
        exit;
    }
}
?>