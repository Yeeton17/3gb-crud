<?php
// process_edit_product.php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and sanitize
    $id          = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $product_code = isset($_POST['product_code']) ? trim($_POST['product_code']) : '';
    $name        = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $category    = isset($_POST['category']) ? trim($_POST['category']) : '';
    $quantity    = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $price       = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;

    // Basic validation
    if ($id <= 0 || !$product_code || !$name || !$category) {
        echo "<script>
            alert('Invalid input. Please fill all required fields.');
            window.location.href = '../edit_product.php?id=$id';
        </script>";
        exit;
    }

    // Prepare SQL
    $sql = "UPDATE products SET product_code=?, name=?, description=?, category=?, quantity=?, price=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssidi", $product_code, $name, $description, $category, $quantity, $price, $id);

    // Execute query
    if ($stmt && $stmt->execute()) {
        echo "<script>
            alert('Product updated successfully');
            window.location.href = '../pages/product_list.php';
        </script>";
    } else {
        echo "<script>
            alert('Error updating product');
            window.location.href = '../edit_product.php?id=$id';
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
