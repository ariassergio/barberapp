<header class="top-navbar">

    <div class="navbar-left d-flex align-items-center gap-3">
        <button class="btn btn-outline-light btn-mobile-toggle" id="mobile-toggle">
            <i class="fa-solid fa-bars"></i>
        </button>
        <h3 class="m-0">Administrador</h3>
    </div>

    <div class="navbar-right">

        <button class="notification-btn">
            <i class="fa-solid fa-bell"></i>
        </button>

        <div class="admin-user d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <div class="cliente-avatar d-flex align-items-center justify-content-center bg-light text-muted rounded-circle" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-user fa-lg"></i>
                </div>
                <span><?= isset($_SESSION['admin_usuario']) ? htmlspecialchars($_SESSION['admin_usuario']) : 'Admin' ?></span>
            </div>
            <a href="logout.php" class="text-danger" title="Cerrar Sesión">
                <i class="fa-solid fa-right-from-bracket fa-lg"></i>
            </a>
        </div>

    </div>

</header>