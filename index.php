<?php
    session_start();

    // If the user is already logged in, redirect to dashboard (adjust the target as needed)
    if (isset($_SESSION['user_id']) || isset($_SESSION['username']) || !empty($_SESSION['loggedin'])) {
        header('Location: dashboard.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
        <link href="assets/css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            body {
                background: #111;
                font-family: 'Montserrat', sans-serif;
                color: #fff;
            }
            .card {
                border: none;
                border-radius: 1.5rem;
                box-shadow: 0 8px 32px 0 rgba(0,0,0,0.37);
                background: #222;
                backdrop-filter: blur(4px);
            }
            .card-header {
                background: #000;
                color: #fff;
                border-top-left-radius: 1.5rem;
                border-top-right-radius: 1.5rem;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            .form-control {
                background: #111;
                color: #fff;
                border: 1px solid #444;
            }
            .form-control:focus {
                border-color: #fff;
                box-shadow: 0 0 0 0.2rem rgba(255,255,255,.25);
                background: #222;
                color: #fff;
            }
            .btn-primary {
                background: #fff;
                color: #111;
                border: none;
                font-weight: 700;
                transition: background 0.3s, color 0.3s;
            }
            .btn-primary:hover {
                background: #111;
                color: #fff;
                border: 1px solid #fff;
            }
            .form-floating > label {
                color: #bbb;
                font-weight: 500;
            }
            .card-footer {
                background: transparent;
            }
            .login-icon {
                font-size: 2.5rem;
                color: #fff;
                margin-bottom: 1rem;
            }
            .error-message {
                color: #e74c3c;
                font-weight: 600;
                text-align: center;
                margin-top: 1rem;
            }
            a.text-primary, a.text-primary:visited, a.text-primary:active {
                color: #fff !important;
                text-decoration: underline;
            }
            a.text-primary:hover {
                color: #bbb !important;
            }
            footer.bg-light {
                background: #111 !important;
                color: #fff;
            }
            .text-muted {
                color: #bbb !important;
            }
        </style>
    </head>
    <body>
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header text-center">
                                        <span class="login-icon"><i class="fas fa-user-circle"></i></span>
                                        <h3 class="font-weight-light my-2">Welcome To Multipurpose IMS</h3>
                                        <p class="mb-0" style="font-size: 1rem; color: #bbb;">Log in to your account</p>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="controllers/process_login.php">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputUsername" name="username" type="text" placeholder="Username" required autofocus />
                                                <label for="inputUsername"><i class="fas fa-user"></i> Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" required />
                                                <label for="inputPassword"><i class="fas fa-lock"></i> Password</label>
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small text-primary" href="password.html"><i class="fas fa-key"></i> Forgot Password?</a>
                                                <button class="btn btn-primary px-4 py-2" type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
                                            </div>
                                        </form>
                                        <?php   
                                            if (isset($_SESSION['error'])) {
                                                echo "<div class='error-message'>" . $_SESSION['error'] . "</div>";
                                                unset($_SESSION['error']);
                                            }
                                        ?>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Gian Bernadez 2025</div>
                            <div>
                                <a href="#" style="color:#fff;">Privacy Policy</a>
                                &middot;
                                <a href="#" style="color:#fff;">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>