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
                    <h1 class="mt-4">Product List</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">:O</li>
                    </ol>
                    
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-boxes me-1"></i>
                                Products List
                            </div>
                            <a href="add_product.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Add New Product
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="10%">Product Code</th>
                                        <th width="15%">Name</th>
                                        <th width="20%">Description</th>
                                        <th width="10%">Category</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Price</th>
                                        <th width="20%">Date Added</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    require_once '../includes/db_connect.php';

                                    $sql = "SELECT id, product_code, name, description, category, quantity, price, date_added FROM products";
                                    $result = $conn->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>".htmlspecialchars($row['id'])."</td>";
                                            echo "<td>".htmlspecialchars($row['product_code'])."</td>";
                                            echo "<td>".htmlspecialchars($row['name'])."</td>";
                                            echo "<td>".htmlspecialchars($row['description'])."</td>";
                                            echo "<td>".htmlspecialchars($row['category'])."</td>";
                                            echo "<td>".htmlspecialchars($row['quantity'])."</td>";
                                            echo "<td>".htmlspecialchars(number_format($row['price'], 2))."</td>";
                                            echo "<td>".date('F j, Y g:i A', strtotime($row['date_added']))."</td>";
                                            echo "<td class='text-center'>
                                                <a href='product_edit.php?id=".$row['id']."' class='btn btn-sm btn-primary me-2'><i class='fas fa-edit me-1'></i>Edit</a>
                                                <a href='../controllers/process_delete_product.php?id=".$row['id']."' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this product?')\"><i class='fas fa-trash-alt me-1'></i>Delete</a>
                                                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='9' class='text-center'>No products found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
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
