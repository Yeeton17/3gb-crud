<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
include '../includes/header.php';
?>

<body class="sb-nav-fixed">

    <?php include '../includes/navbar.php'; ?>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">

            <?php include '../includes/sidebar.php'; ?>

        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Product Edit</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Product Edit</li>
                    </ol>
                </div>
                <?php
                // Fetch product data based on ID from GET parameter
                if (isset($_GET['id'])) {
                    $product_id = intval($_GET['id']);
                    // Connect to database
                    include '../includes/db_connect.php';
                    $stmt = $conn->prepare("SELECT id, product_code, name, description, category, price FROM products WHERE id = ?");
                    $stmt->bind_param("i", $product_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();
                    $stmt->close();
                    $conn->close();
                } else {
                    echo '<div class="alert alert-danger">No product selected.</div>';
                    exit;
                }
                ?>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-box-edit"></i>
                        Edit Product
                    </div>
                    <div class="card-body">
                        <form action="../controllers/process_edit_product.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                            <div class="mb-3">
                                <label for="product_code" class="form-label">Product Code</label>
                                <input type="text" class="form-control" id="product_code" name="product_code" required value="<?php echo htmlspecialchars($product['product_code']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" id="category" name="category" required value="<?php echo htmlspecialchars($product['category']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" required value="<?php echo htmlspecialchars($product['price']); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <a href="product_list.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </main>

                <?php include '../includes/footer.php'; ?>

            </div>
        </div>

        <?php include '../includes/scripts.php'; ?>

    </body>
</html>
