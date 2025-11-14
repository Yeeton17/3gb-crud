<?php
session_start();
require_once '../includes/db_connect.php'; // Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        echo "<script>
            alert('Username and password are required.');
            window.location.href = '../index.php';
        </script>";
        exit;
    }

    // Prepare and execute query using MySQLi
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo "<script>
            alert('Login successful!');
            window.location.href = '../pages/dashboard.php';
        </script>";
    } else {
        echo "<script>
            alert('Invalid username or password.');
            window.location.href = '../index.php';
        </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}