<?php
require_once("../../Includes/config.php");
require_once("../../Includes/session.php");

// Only admin or staff can add visitor entry
require_role(array('admin', 'staff'), '../../login.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $person_name = trim(isset($_POST['person_name']) ? $_POST['person_name'] : '');

    if (empty($person_name)) {
        echo json_encode(array('status' => 'error', 'message' => 'Visitor name / purpose is required.'));
        exit;
    }

    $stmt = mysqli_prepare($con, "INSERT INTO registry (person_name, created_at) VALUES (?, NOW())");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $person_name);
        $saved = mysqli_stmt_execute($stmt);
        $new_id = mysqli_insert_id($con);
        mysqli_stmt_close($stmt);

        if ($saved) {
            echo json_encode(array(
                'status' => 'success',
                'id' => $new_id,
                'person_name' => htmlspecialchars($person_name),
                'created_at' => date('d M Y, h:i A')
            ));
            exit;
        }
    }
    echo json_encode(array('status' => 'error', 'message' => 'Failed to save entry to database.'));
    exit;
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method.'));
    exit;
}
