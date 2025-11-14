<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="dashboard.php">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            
            <div class="sb-sidenav-menu-heading">System</div>
            
            <a class="nav-link" href="user_list.php">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                User List
            </a>
            <a class="nav-link" href="product_list.php">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                Product List
            </a>
            <a class="nav-link" href="transactions.php">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                Transactions
            </a>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>
    </div>
</nav>