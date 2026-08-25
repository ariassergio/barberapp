<?php
require_once 'includes/auth.php';
require_once 'config/db.php';


$hoy = date('Y-m-d');

// Turnos hoy
$r = mysqli_query($conn, "SELECT COUNT(*) as total FROM turnos WHERE DATE(fecha_inicio) = '$hoy'");
$turnosHoy = mysqli_fetch_assoc($r)['total'];

// Pendientes
$r = mysqli_query($conn, "SELECT COUNT(*) as total FROM turnos WHERE DATE(fecha_inicio) = '$hoy' AND estado = 'pendiente'");
$pendientes = mysqli_fetch_assoc($r)['total'];

// Barberos activos
$r = mysqli_query($conn, "SELECT COUNT(*) as total FROM profesionales WHERE activo = 1");
$barberosActivos = mysqli_fetch_assoc($r)['total'];

// Ingresos del día (turnos finalizados)
$r = mysqli_query($conn, "SELECT COALESCE(SUM(s.precio), 0) as total
                           FROM turnos t JOIN servicios s ON t.id_servicio = s.id_servicio
                           WHERE DATE(t.fecha_inicio) = '$hoy' AND t.estado = 'finalizado'");
$ingresosHoy = mysqli_fetch_assoc($r)['total'];
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
                        <h3><?= $turnosHoy ?></h3>
                        <p>Turnos Hoy</p>
                    </div>

                    <div class="admin-card">
                        <i class="fa-solid fa-clock"></i>
                        <h3><?= $pendientes ?></h3>
                        <p>Pendientes</p>
                    </div>

                    <div class="admin-card">
                        <i class="fa-solid fa-scissors"></i>
                        <h3><?= $barberosActivos ?></h3>
                        <p>Barberos Activos</p>
                    </div>

                    <div class="admin-card">
                        <i class="fa-solid fa-dollar-sign"></i>
                        <h3>$<?= number_format($ingresosHoy, 0, ',', '.') ?></h3>
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
                            <?php
                            // 🔹 Agregamos t.id_turno a la consulta para poder identificarlo en el JS
                            $consultaTurnos = "SELECT t.id_turno, t.nombre as cliente, t.telefono, t.fecha_inicio, t.estado, 
                                                    s.nombre as servicio, p.nombre as barbero
                                            FROM turnos t
                                            JOIN servicios s ON t.id_servicio = s.id_servicio
                                            JOIN profesionales p ON t.id_profesional = p.id_profesional
                                            WHERE DATE(t.fecha_inicio) = '$hoy'
                                            ORDER BY t.fecha_inicio ASC";

                            $resultadoTurnos = mysqli_query($conn, $consultaTurnos);

                            if (mysqli_num_rows($resultadoTurnos) > 0) {
                                while ($turno = mysqli_fetch_assoc($resultadoTurnos)) {
                                    $horaTurno = date('H:i', strtotime($turno['fecha_inicio']));
                                    ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($turno['cliente']) ?> <br> 
                                            <small class=""><?= $turno['telefono'] ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($turno['servicio']) ?></td>
                                        <td><?= htmlspecialchars($turno['barbero']) ?></td>
                                        <td><strong><?= $horaTurno ?></strong></td>
                                        <td>
                                            <?php
                                                $est = strtolower($turno['estado']);
                                                $selectColor = 'bg-dark text-white border-secondary';
                                                if ($est === 'pendiente') $selectColor = 'bg-warning text-dark border-warning';
                                                elseif ($est === 'finalizado') $selectColor = 'bg-success text-white border-success';
                                                elseif ($est === 'cancelado') $selectColor = 'bg-danger text-white border-danger';
                                                elseif ($est === 'confirmado') $selectColor = 'bg-primary text-white border-primary';
                                            ?>
                                            <select class="form-select form-select-sm select-estado <?= $selectColor ?>" 
                                                    data-id="<?= $turno['id_turno'] ?>" 
                                                    style="width: 130px; font-weight: 600;">
                                                <option value="pendiente" <?= $turno['estado'] == 'pendiente' ? 'selected' : '' ?> class="bg-dark text-white">Pendiente</option>
                                                <option value="confirmado" <?= $turno['estado'] == 'confirmado' ? 'selected' : '' ?> class="bg-dark text-white">Confirmado</option>
                                                <option value="finalizado" <?= $turno['estado'] == 'finalizado' ? 'selected' : '' ?> class="bg-dark text-white">Finalizado</option>
                                                <option value="cancelado" <?= $turno['estado'] == 'cancelado' ? 'selected' : '' ?> class="bg-dark text-white">Cancelado</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No hay turnos agendados para el día de hoy.</td></tr>";
                            }
                            ?>
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