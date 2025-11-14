<?php
    
    require_once '../includes/db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $username = trim($_POST['username']);
        $role = trim($_POST['role']);
        $created_at = trim($_POST['created_at']);
        $password = $_POST['password'];

        if (empty($username) || empty($role) || empty($created_at) || empty($password)) {
            echo "<script>
                alert('Please fill all the fields');
                window.location.href = '../pages/add_user.php';
            </script>";
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, role, created_at, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $role, $created_at, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>
                alert('User added successfully');
                window.location.href = '../pages/user_list.php';
            </script>";
        } else {
            echo "<script>
                alert('Failed to add user');
                window.location.href = '../pages/user_list.php';
            </script>";
        }

        $stmt->close();
        $conn->close();

    } else {
        header('Location: ../pages/add_user.php');
        exit();
    }

?>