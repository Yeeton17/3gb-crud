<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
include '../includes/header.php';
include '../includes/db_connect.php';

// ===== FETCH STATISTICS =====
$totalProducts = 0;
$totalQuantity = 0;
$totalValue = 0;
$totalTransactions = 0;

if ($result = $conn->query("SELECT COUNT(*) AS total FROM products")) {
    $row = $result->fetch_assoc();
    $totalProducts = $row ? $row['total'] : 0;
    $result->free();
}
if ($result = $conn->query("SELECT SUM(quantity) AS total FROM products")) {
    $row = $result->fetch_assoc();
    $totalQuantity = $row && $row['total'] !== null ? $row['total'] : 0;
    $result->free();
}
if ($result = $conn->query("SELECT SUM(quantity * price) AS total FROM products")) {
    $row = $result->fetch_assoc();
    $totalValue = $row && $row['total'] !== null ? $row['total'] : 0;
    $result->free();
}
if ($result = $conn->query("SELECT COUNT(*) AS total FROM transactions")) {
    $row = $result->fetch_assoc();
    $totalTransactions = $row ? $row['total'] : 0;
    $result->free();
}

// ===== FETCH DATA FOR CHARTS =====
$categories = [];
$categoryQty = [];
if ($categoryData = $conn->query("SELECT category, SUM(quantity) AS total FROM products GROUP BY category")) {
    while ($row = $categoryData->fetch_assoc()) {
        $categories[] = $row['category'];
        $categoryQty[] = $row['total'];
    }
    $categoryData->free();
}

// ===== FETCH TOP PRODUCTS =====
$topProducts = [];
if ($topData = $conn->query("SELECT name, quantity FROM products ORDER BY quantity DESC LIMIT 5")) {
    while ($row = $topData->fetch_assoc()) {
        $topProducts[] = $row;
    }
    $topData->free();
}

// ===== FETCH RECENT TRANSACTIONS =====
$recentTransactions = [];
if ($transData = $conn->query("SELECT t.id, p.name AS product, t.type, t.quantity, t.date FROM transactions t JOIN products p ON t.product_id = p.id ORDER BY t.date DESC LIMIT 5")) {
    while ($row = $transData->fetch_assoc()) {
        $recentTransactions[] = $row;
    }
    $transData->free();
}

// ===== FETCH TRANSACTION DATA PER PRODUCT AND TYPE =====
$chartData = [];
if ($transactionData = $conn->query("
    SELECT p.name AS product_name,
           DATE(t.date) AS tdate,
           t.type,
           SUM(t.quantity) AS total
    FROM transactions t
    JOIN products p ON t.product_id = p.id
    GROUP BY p.name, t.type, DATE(t.date)
    ORDER BY DATE(t.date), p.name
")) {
    while ($row = $transactionData->fetch_assoc()) {
        $chartData[] = [
            'product' => $row['product_name'],
            'date' => $row['tdate'],
            'type' => $row['type'],
            'total' => $row['total']
        ];
    }
    $transactionData->free();
}
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
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard Overview</li>
                    </ol>

                    <!-- ===== STATISTIC CARDS ===== -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4 shadow">
                                <div class="card-body">
                                    <h5>Total Products</h5>
                                    <h2><?= number_format($totalProducts) ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4 shadow">
                                <div class="card-body">
                                    <h5>Total Quantity in Stock</h5>
                                    <h2><?= number_format($totalQuantity) ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4 shadow">
                                <div class="card-body">
                                    <h5>Total Inventory Value</h5>
                                    <h2>₱<?= number_format($totalValue, 2) ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-4 shadow">
                                <div class="card-body">
                                    <h5>Total Transactions</h5>
                                    <h2><?= number_format($totalTransactions) ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== TOP PRODUCTS ===== -->
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <div class="card border-info mb-4">
                                <div class="card-header bg-info text-white"><i class="fas fa-star me-1"></i> Top Products by Quantity</div>
                                <div class="card-body p-0">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($topProducts as $prod): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($prod['name']) ?></td>
                                                <td><?= number_format($prod['quantity']) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($topProducts)): ?>
                                            <tr><td colspan="2" class="text-center">No data</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- RECENT TRANSACTIONS -->
                        <div class="col-lg-6">
                            <div class="card border-secondary mb-4">
                                <div class="card-header bg-secondary text-white"><i class="fas fa-history me-1"></i> Recent Transactions</div>
                                <div class="card-body p-0">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Product</th>
                                                <th>Type</th>
                                                <th>Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentTransactions as $trans): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($trans['date']) ?></td>
                                                <td><?= htmlspecialchars($trans['product']) ?></td>
                                                <td><?= htmlspecialchars($trans['type']) ?></td>
                                                <td><?= number_format($trans['quantity']) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($recentTransactions)): ?>
                                            <tr><td colspan="4" class="text-center">No data</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== CHARTS ===== -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header"><i class="fas fa-chart-bar me-1"></i> Quantity by Category</div>
                                <div class="card-body"><canvas id="categoryChart" width="100%" height="50"></canvas></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header"><i class="fas fa-chart-line me-1"></i> Transactions Over Time</div>
                                <div class="card-body"><canvas id="transactionChart" width="100%" height="50"></canvas></div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <?php include '../includes/footer.php'; ?>

        </div>
    </div>

    <?php include '../includes/scripts.php'; ?>

    <!-- ===== CHART.JS SCRIPTS ===== -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Bar Chart: Product Quantity by Category
        const ctxCategory = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxCategory, {
            type: 'bar',
            data: {
                labels: <?= json_encode($categories) ?>,
                datasets: [{
                    label: 'Total Quantity',
                    data: <?= json_encode($categoryQty) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Product Quantity by Category' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Line Chart: Transactions Over Time (specifying product and type)
        const ctxTrans = document.getElementById('transactionChart').getContext('2d');
        const chartData = <?= json_encode($chartData) ?>;

        // Extract unique dates and build dataset grouped by product+type
        const allDates = [...new Set(chartData.map(d => d.date))];
        const grouped = {};

        chartData.forEach(d => {
            const key = `${d.product} (${d.type})`;
            if (!grouped[key]) grouped[key] = {};
            grouped[key][d.date] = d.total;
        });

        function randomColor(alpha = 1) {
            const r = Math.floor(Math.random()*200)+30;
            const g = Math.floor(Math.random()*200)+30;
            const b = Math.floor(Math.random()*200)+30;
            return `rgba(${r}, ${g}, ${b}, ${alpha})`;
        }

        const datasets = Object.entries(grouped).map(([label, data]) => {
            const color = randomColor();
            return {
                label: label,
                data: allDates.map(date => data[date] ?? 0),
                borderColor: color,
                backgroundColor: color.replace(/[\d\.]+\)$/,'0.2)'),
                fill: false,
                tension: 0.3
            };
        });

        new Chart(ctxTrans, {
            type: 'line',
            data: {
                labels: allDates,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: {
                        display: true,
                        text: 'Transactions Over Time (By Product and Type)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Quantity' }
                    },
                    x: {
                        title: { display: true, text: 'Date' }
                    }
                }
            }
        });
    </script>

</body>
</html>
