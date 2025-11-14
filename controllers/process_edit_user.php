<?php
// process_edit_user.php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and sanitize
    $id        = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
    $lastname  = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $email     = isset($_POST['email']) ? trim($_POST['email']) : '';
    $course    = isset($_POST['course']) ? trim($_POST['course']) : '';
    $username  = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password  = isset($_POST['password']) ? $_POST['password'] : '';

    // Basic validation
    if ($id <= 0 || !$firstname || !$lastname || !$email || !$course || !$username) {
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
        $sql = "UPDATE user_table SET firstname=?, lastname=?, email=?, course=?, username=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $firstname, $lastname, $email, $course, $username, $hashed_password, $id);
    } else {
        $sql = "UPDATE user_table SET firstname=?, lastname=?, email=?, course=?, username=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $firstname, $lastname, $email, $course, $username, $id);
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