<?php
// process_edit_user.php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and sanitize
    $id        = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $role  = isset($_POST['role']) ? trim($_POST['role']) : '';
    $password  = isset($_POST['password']) ? $_POST['password'] : '';

    // Basic validation
    if ($id <= 0 || !$username || !$role) {
        echo "<script>
            alert('Invalid input. Please fill all required fields.');
            window.location.href = '../edit_user.php?id=$id';
        </script>";
        exit;
    }
    // Prepare SQL
    if ($password) {
        // Hash new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username=?, role=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $role, $hashed_password, $id);
    } else {
        $sql = "UPDATE users SET username=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $role, $id);
    }

    // Execute query
    if ($stmt && $stmt->execute()) {
        echo "<script>
            alert('User updated successfully');
            window.location.href = '../pages/user_list.php';
        </script>";
    } else {
        echo "<script>
            alert('Error updating user');
            window.location.href = '../pages/edit_user.php?id=$id';
        </script>";
    }

    $stmt->close();
} else {
    echo "<script>
        alert('Invalid request');
        window.location.href = '../pages/user_list.php';
    </script>";
}

$conn->close();
?>