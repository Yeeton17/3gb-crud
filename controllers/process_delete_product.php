<?php
require_once '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Product deleted successfully');
            window.location.href = '../pages/product_list.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Error deleting product');
            window.location.href = '../pages/product_list.php';
        </script>";
    }

    $stmt->close();
} else {
    echo "<script>
        alert('Invalid request');
        window.location.href = '../pages/product_list.php';
    </script>";
}

$conn->close();
?>