<?php
// Más adelante acá irá la validación de sesión
// require_once 'includes/auth.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/admin.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <div class="admin-container">

        <!-- SIDEBAR -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- CONTENIDO -->
        <main class="main-content">

            <!-- NAVBAR -->
            <?php include 'includes/navbar.php'; ?>

            <!-- DASHBOARD -->
            <section class="dashboard">

                <h1 class="dashboard-title">
                    Panel de Administración
                </h1>

                <!-- CARDS -->
                <div class="cards-container">

                    <div class="admin-card">
                        <i class="fa-solid fa-calendar-check"></i>
                        <h3>24</h3>
                        <p>Turnos Hoy</p>
                    </div>

                    <div class="admin-card">
                        <i class="fa-solid fa-clock"></i>
                        <h3>8</h3>
                        <p>Pendientes</p>
                    </div>

                    <div class="admin-card">
                        <i class="fa-solid fa-scissors"></i>
                        <h3>5</h3>
                        <p>Barberos Activos</p>
                    </div>

                    <div class="admin-card">
                        <i class="fa-solid fa-dollar-sign"></i>
                        <h3>$125.000</h3>
                        <p>Ingresos Hoy</p>
                    </div>

                </div>

                <!-- TABLA -->
                <div class="table-container">

                    <div class="table-header">
                        <h2>Próximos Turnos</h2>
                    </div>

                    <table class="table table-dark table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Barbero</th>
                                <th>Hora</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td>Juan Pérez</td>
                                <td>Corte Fade</td>
                                <td>Lucas</td>
                                <td>18:00</td>
                                <td>
                                    <span class="estado pendiente">
                                        Pendiente
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td>Martín Gómez</td>
                                <td>Barba</td>
                                <td>Franco</td>
                                <td>19:00</td>
                                <td>
                                    <span class="estado confirmado">
                                        Confirmado
                                    </span>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </section>

        </main>

    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS -->
    <script src="js/admin.js"></script>

</body>

</html>