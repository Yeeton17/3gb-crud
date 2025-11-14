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
                        <h1 class="mt-4">Add User</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">:|</li>
                        </ol>
                        <!-- USER FORM -->
                        <div class="card mb-4">
                            <div class="card-header">
                                Add User
                            </div>
                            <div class="card-body">
                                <form action="../controllers/process_add_user.php" method="POST">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="form-select" id="role" name="role" required>
                                                <option value="" selected disabled>Choose role</option>
                                                <option value="admin">Admin</option>
                                                <option value="staff">Staff</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="created_at" value="<?php date_default_timezone_set('Asia/Manila'); echo date('Y-m-d H:i:s'); ?>">
                                    <button type="submit" class="btn btn-success">Add User</button>
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
