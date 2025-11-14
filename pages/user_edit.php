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
                        <h1 class="mt-4">User Edit</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">;]</li>
                        </ol>
                        
                        
                    </div>
                <?php
                // Fetch user data based on ID from GET parameter
                if (isset($_GET['id'])) {
                    $user_id = intval($_GET['id']);
                    // Connect to database
                    include '../includes/db_connect.php';
                    $stmt = $conn->prepare("SELECT id, username, role, created_at, password FROM users WHERE id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                    $stmt->close();
                    $conn->close();
                } else {
                    echo '<div class="alert alert-danger">No user selected.</div>';
                    exit;
                }
                ?>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-user-edit"></i>
                        Edit User
                    </div>
                    <div class="card-body">
                        <form action="../controllers/process_edit_user.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required value="<?php echo htmlspecialchars($user['username']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="staff" <?php echo ($user['role'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required value="<?php echo htmlspecialchars($user['password']); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Update User</button>
                            <a href="user_list.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </main>

                <?php include '../includes/footer.php'; ?>

            </div>
        </div>

        <?php include '../includes/scripts.php'; ?>

    </body>
</html>
