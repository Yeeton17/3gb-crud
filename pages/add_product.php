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
                    <h1 class="mt-4">Add Product</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Add Product</li>
                    </ol>
                    <!-- PRODUCT FORM -->
                    <div class="card mb-4">
                        <div class="card-header">
                            Add Product
                        </div>
                        <div class="card-body">
                            <form action="../controllers/process_add_product.php" method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="product_code" class="form-label">Product Code</label>
                                        <input type="text" class="form-control" id="product_code" name="product_code" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="description" name="description" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category" class="form-label">Category</label>
                                        <input type="text" class="form-control" id="category" name="category" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" min="0" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Add Product</button>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </main>

            <?php include '../includes/footer.php'; ?>

        </div>
    </div>

    <?php include '../includes/scripts.php'; ?>

</body>
</html>
