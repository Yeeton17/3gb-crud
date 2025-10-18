<?php include '../includes/header.php'; ?>

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
                            <li class="breadcrumb-item active">User Edit</li>
                        </ol>
                        
                        
                    </div>
                <?php
                // Fetch user data based on ID from GET parameter
                if (isset($_GET['id'])) {
                    $user_id = intval($_GET['id']);
                    // Connect to database
                    include '../includes/db_connect.php';
                    $stmt = $conn->prepare("SELECT id, firstname, lastname, email, course, username, password FROM user_table WHERE id = ?");
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
                                <label for="firstname" class="form-label">Firstname</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" required value="<?php echo htmlspecialchars($user['firstname']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="lastname" class="form-label">Lastname</label>
                                <input type="lastname" class="form-control" id="lastname" name="lastname" required value="<?php echo htmlspecialchars($user['lastname']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <input type="course" class="form-control" id="course" name="course" required value="<?php echo htmlspecialchars($user['course']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="username" class="form-control" id="username" name="username" required value="<?php echo htmlspecialchars($user['username']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required value="<?php echo htmlspecialchars($user['password']); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Update User</button>
                            <a href="user_list.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
                </main>

                <?php include '../includes/footer.php'; ?>

            </div>
        </div>

        <?php include '../includes/scripts.php'; ?>

    </body>
</html>
