<?php

    require_once '../includes/db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $product_code = trim($_POST['product_code']);
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $category = trim($_POST['category']);
        $quantity = trim($_POST['quantity']);
        $price = trim($_POST['price']);

        if (empty($product_code) || empty($name) || empty($description) || empty($category) || empty($price)) {
            echo "<script>
                alert('Please fill all the fields');
                window.location.href = '../pages/add_product.php';
            </script>";
            exit();
        }

        date_default_timezone_set('Asia/Manila');
        $date_added = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO products (product_code, name, description, category, quantity, price, date_added) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssids", $product_code, $name, $description, $category, $quantity, $price, $date_added);

        if ($stmt->execute()) {
            echo "<script>
                alert('Product added successfully');
                window.location.href = '../pages/product_list.php';
            </script>";
        } else {
            echo "<script>
                alert('Failed to add product');
                window.location.href = '../pages/product_list.php';
            </script>";
        }

        $stmt->close();
        $conn->close();

    } else {
        header('Location: ../pages/add_product.php');
        exit();
    }

?>