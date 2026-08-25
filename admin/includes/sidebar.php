<div class="sidebar-overlay" id="sidebarOverlay"></div>
<aside class="sidebar" id="sidebar">

    <div class="logo">
        <h2>Barber Admin</h2>
    </div>

    <ul class="menu">

        <li>
            <a href="admin.php">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="reservas.php">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Reservas</span>
            </a>
        </li>

        <li>
            <a href="barberos.php">
                <i class="fa-solid fa-scissors"></i>
                <span>Barberos</span>
            </a>
        </li>

        <li>
            <a href="servicios.php">
                <i class="fa-solid fa-briefcase"></i>
                <span>Servicios</span>
            </a>
        </li>

        <li>
            <a href="usuarios.php">
                <i class="fa-solid fa-users"></i>
                <span>Usuarios</span>
            </a>
        </li>

        <li>
            <a href="reportes.php">
                <i class="fa-solid fa-chart-line"></i>
                <span>Reportes</span>
            </a>
        </li>

        
        <?php if (isset($_SESSION['admin_rol']) && $_SESSION['admin_rol'] === 'maintainer'): ?>
    <li>
        <a href="configuracion.php">
            <i class="fa-solid fa-gear"></i>
            <span>Configuración</span>
        </a>
    </li>
<?php endif; ?>


    </ul>

</aside>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.getElementById("mobile-toggle");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");

    if (toggleBtn) {
        toggleBtn.addEventListener("click", function() {
            sidebar.classList.toggle("show-mobile");
            overlay.classList.toggle("show");
        });
    }

    if (overlay) {
        overlay.addEventListener("click", function() {
            sidebar.classList.remove("show-mobile");
            overlay.classList.remove("show");
        });
    }
});
</script>