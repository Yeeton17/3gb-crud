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
                        <h1 class="mt-4">User list</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">:)</li>
                        </ol>
                        
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-users me-1"></i>
                                    Users List
                                </div>
                                <a href="add_user.php" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i>Add New User
                                </a>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th width="25%">Username</th>
                                            <th width="20%">Role</th>
                                            <th width="25%">Created At</th>
                                            <th width="25%">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        require_once '../includes/db_connect.php';

                                        $sql = "SELECT id, username, role, created_at FROM users";
                                        $result = $conn->query($sql);

                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>".htmlspecialchars($row['id'])."</td>";
                                                echo "<td>".htmlspecialchars($row['username'])."</td>";
                                                echo "<td><span class='badge bg-".($row['role'] == 'admin' ? 'primary' : 'secondary')."'>".htmlspecialchars($row['role'])."</span></td>";
                                                echo "<td>".date('F j, Y g:i A', strtotime($row['created_at']))."</td>";
                                                echo "<td class='text-center'>
                                                    <a href='user_edit.php?id=".$row['id']."' class='btn btn-sm btn-primary me-2'><i class='fas fa-edit me-1'></i>Edit</a>
                                                    <a href='../controllers/process_delete_user.php?id=".$row['id']."' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this user?')\"><i class='fas fa-trash-alt me-1'></i>Delete</a>
                                                    </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' class='text-center'>No users found.</td></tr>";
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
