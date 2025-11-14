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
                    <h1 class="mt-4">Transactions</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Transactions</li>
                    </ol>
                    
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-exchange-alt me-1"></i>
                                Transactions List
                            </div>
                        </div>
                        <div class="card-body">

                            <!-- Transaction Form Start -->
                            <?php
                            require_once '../includes/db_connect.php';
                            date_default_timezone_set('Asia/Manila');

                            // Handle form submission
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_transaction'])) {
                                $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
                                $type = ($_POST['type'] === 'IN') ? 'IN' : 'OUT';
                                $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
                                $date = date('Y-m-d H:i:s');

                                // Validate input
                                $errors = [];
                                if ($product_id <= 0) $errors[] = "Invalid product selected.";
                                if ($quantity <= 0) $errors[] = "Quantity must be greater than zero.";

                                // Get current product quantity
                                $stmt = $conn->prepare("SELECT quantity FROM products WHERE id = ?");
                                $stmt->bind_param("i", $product_id);
                                $stmt->execute();
                                $stmt->bind_result($current_quantity);
                                if (!$stmt->fetch()) {
                                    $errors[] = "Product not found.";
                                }
                                $stmt->close();

                                if ($type === 'OUT' && $quantity > $current_quantity) {
                                    $errors[] = "Not enough stock for OUT transaction.";
                                }

                                if (empty($errors)) {
                                    $new_quantity = ($type === 'IN') ? $current_quantity + $quantity : $current_quantity - $quantity;

                                    // Insert transaction
                                    $stmt = $conn->prepare("INSERT INTO transactions (product_id, type, quantity, date) VALUES (?, ?, ?, ?)");
                                    $stmt->bind_param("isis", $product_id, $type, $quantity, $date);
                                    $stmt->execute();
                                    $stmt->close();

                                    // Update product quantity
                                    $stmt = $conn->prepare("UPDATE products SET quantity = ? WHERE id = ?");
                                    $stmt->bind_param("ii", $new_quantity, $product_id);
                                    $stmt->execute();
                                    $stmt->close();

                                    // Redirect to avoid resubmission
                                    header("Location: transactions.php?success=1");
                                    exit();
                                } else {
                                    echo '<div class="alert alert-danger">'.implode('<br>', $errors).'</div>';
                                }
                            }

                            // Fetch products for dropdown
                            $products = [];
                            $result = $conn->query("SELECT id, name FROM products ORDER BY name ASC");
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $products[] = $row;
                                }
                            }
                            ?>

                            <?php if (isset($_GET['success'])): ?>
                                <div class="alert alert-success">Transaction added successfully.</div>
                            <?php endif; ?>

                            <form method="post" class="mb-4">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-4">
                                        <label for="product_id" class="form-label">Product</label>
                                        <select name="product_id" id="product_id" class="form-select" required>
                                            <option value="">Select Product</option>
                                            <?php foreach ($products as $product): ?>
                                                <option value="<?= htmlspecialchars($product['id']) ?>">
                                                    <?= htmlspecialchars($product['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="type" class="form-label">Type</label>
                                        <select name="type" id="type" class="form-select" required>
                                            <option value="IN">IN</option>
                                            <option value="OUT">OUT</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" name="add_transaction" class="btn btn-primary">Add Transaction</button>
                                    </div>
                                </div>
                            </form>
                            <!-- Transaction Form End -->

                            <table id="datatablesSimple" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="10%">ID</th>
                                        <th width="20%">Product</th>
                                        <th width="20%">Type</th>
                                        <th width="20%">Quantity</th>
                                        <th width="30%">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT t.id, t.product_id, p.name AS product_name, t.type, t.quantity, t.date 
                                            FROM transactions t 
                                            LEFT JOIN products p ON t.product_id = p.id 
                                            ORDER BY t.date DESC";
                                    $result = $conn->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>".htmlspecialchars($row['id'])."</td>";
                                            echo "<td>".htmlspecialchars($row['product_name'] ?? $row['product_id'])."</td>";
                                            echo "<td>".htmlspecialchars(ucfirst($row['type']))."</td>";
                                            echo "<td>".htmlspecialchars($row['quantity'])."</td>";
                                            echo "<td>".date('F j, Y g:i A', strtotime($row['date']))."</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No transactions found.</td></tr>";
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
