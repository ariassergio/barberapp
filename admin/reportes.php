<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$rango = $_GET['rango'] ?? 'mes';
$filtroGlobal = "";

if ($rango === 'hoy') {
    $filtroGlobal = "DATE(t.fecha_inicio) = CURDATE()";
} elseif ($rango === 'semana') {
    $filtroGlobal = "YEARWEEK(t.fecha_inicio, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($rango === 'todo') {
    $filtroGlobal = "1=1";
} else {
    // mes por defecto
    $filtroGlobal = "MONTH(t.fecha_inicio) = MONTH(CURDATE()) AND YEAR(t.fecha_inicio) = YEAR(CURDATE())";
}

// 🔹 ESTADÍSTICAS GLOBALES
$sqlGlobal = "SELECT 
                COUNT(t.id_turno) as total_turnos,
                SUM(CASE WHEN t.estado = 'finalizado' THEN 1 ELSE 0 END) as total_realizados,
                SUM(CASE WHEN t.estado = 'cancelado' THEN 1 ELSE 0 END) as total_cancelados,
                SUM(CASE WHEN t.estado = 'finalizado' THEN s.precio ELSE 0 END) as ingresos_totales
              FROM turnos t
              LEFT JOIN servicios s ON t.id_servicio = s.id_servicio
              WHERE $filtroGlobal";
$resGlobal = mysqli_query($conn, $sqlGlobal);
$stats = mysqli_fetch_assoc($resGlobal);

$ingresosTotales = $stats['ingresos_totales'] ?? 0;
$turnosTotales = $stats['total_turnos'] ?? 0;
$turnosRealizados = $stats['total_realizados'] ?? 0;
$turnosCancelados = $stats['total_cancelados'] ?? 0;

// 🔹 ESTADÍSTICAS POR BARBERO
$sqlBarberos = "SELECT 
                  p.nombre as barbero,
                  COUNT(t.id_turno) as turnos_totales,
                  SUM(CASE WHEN t.estado = 'finalizado' THEN 1 ELSE 0 END) as turnos_realizados,
                  SUM(CASE WHEN t.estado = 'cancelado' THEN 1 ELSE 0 END) as turnos_cancelados,
                  SUM(CASE WHEN t.estado = 'finalizado' THEN s.precio ELSE 0 END) as ingresos
                FROM profesionales p
                LEFT JOIN turnos t ON p.id_profesional = t.id_profesional AND $filtroGlobal
                LEFT JOIN servicios s ON t.id_servicio = s.id_servicio
                GROUP BY p.id_profesional
                ORDER BY ingresos DESC";
$resBarberos = mysqli_query($conn, $sqlBarberos);

$barberosData = [];
$labelsGrafico = [];
$datosGrafico = [];

while ($row = mysqli_fetch_assoc($resBarberos)) {
    $row['ingresos'] = $row['ingresos'] ?? 0;
    $barberosData[] = $row;
    
    // Preparar arrays para Chart.js
    if ($row['ingresos'] > 0 || $row['turnos_totales'] > 0) {
        $labelsGrafico[] = $row['barbero'];
        $datosGrafico[] = $row['ingresos'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes y Estadísticas</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/reportes.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <?php include 'includes/navbar.php'; ?>

        <section class="dashboard">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h1 class="dashboard-title mb-1">Reportes y Rendimiento</h1>
                    <p class="text-blanco mb-0">Analiza los ingresos y el rendimiento de los barberos</p>
                </div>
                
                <!-- FILTRO DE FECHAS -->
                <form method="GET" class="d-flex align-items-center bg-dark p-2 rounded-3 border border-secondary">
                    <i class="fa-solid fa-calendar-days text-muted ms-2 me-2"></i>
                    <select name="rango" class="form-select bg-dark text-white border-0 shadow-none outline-none" onchange="this.form.submit()" style="cursor: pointer;">
                        <option value="hoy" <?= $rango == 'hoy' ? 'selected' : '' ?>>Solo Hoy</option>
                        <option value="semana" <?= $rango == 'semana' ? 'selected' : '' ?>>Esta Semana</option>
                        <option value="mes" <?= $rango == 'mes' ? 'selected' : '' ?>>Este Mes</option>
                        <option value="todo" <?= $rango == 'todo' ? 'selected' : '' ?>>Histórico Completo</option>
                    </select>
                </form>
            </div>

            <!-- TARJETAS GLOBALES -->
            <div class="row mb-4">
                <div class="col-12 col-md-3 mb-3">
                    <div class="report-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3>$<?= number_format($ingresosTotales, 0, ',', '.') ?></h3>
                                <p>Ingresos Totales</p>
                            </div>
                            <div class="icon-box text-success bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; font-size:1.5rem;">
                                <i class="fa-solid fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <div class="report-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3><?= $turnosTotales ?></h3>
                                <p>Turnos Asignados</p>
                            </div>
                            <div class="icon-box text-info bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; font-size:1.5rem;">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <div class="report-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-primary"><?= $turnosRealizados ?></h3>
                                <p>Realizados</p>
                            </div>
                            <div class="icon-box text-primary bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; font-size:1.5rem;">
                                <i class="fa-solid fa-check-double"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <div class="report-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-danger"><?= $turnosCancelados ?></h3>
                                <p>Cancelados</p>
                            </div>
                            <div class="icon-box text-danger bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; font-size:1.5rem;">
                                <i class="fa-solid fa-user-xmark"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- TABLA POR BARBERO -->
                <div class="col-12 col-lg-7 mb-4">
                    <div class="chart-container h-100">
                        <h4 class="mb-4 text-white font-weight-bold">Desglose por Barbero</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless table-reportes align-middle">
                                <thead>
                                    <tr>
                                        <th>Barbero</th>
                                        <th class="text-center">Realizados</th>
                                        <th class="text-center">Cancelados</th>
                                        <th class="text-end">Ingresos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($barberosData as $bd): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fa-solid fa-user-tie text-white"></i>
                                                </div>
                                                <span class="text-white fw-bold"><?= htmlspecialchars($bd['barbero']) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success bg-opacity-25 text-success fs-6 px-3 py-2 rounded-pill"><?= $bd['turnos_realizados'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger bg-opacity-25 text-danger fs-6 px-3 py-2 rounded-pill"><?= $bd['turnos_cancelados'] ?></span>
                                        </td>
                                        <td class="text-end fw-bold text-success" style="font-size: 1.1rem;">
                                            $<?= number_format($bd['ingresos'], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- GRÁFICO CHART.JS -->
                <div class="col-12 col-lg-5 mb-4">
                    <div class="chart-container h-100 d-flex flex-column">
                        <h4 class="mb-4 text-white font-weight-bold">Ingresos Generados</h4>
                        <div class="flex-grow-1" style="position: relative; min-height: 300px;">
                            <canvas id="ingresosChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('ingresosChart').getContext('2d');
    
    // Obtener los datos desde PHP
    const labels = <?= json_encode($labelsGrafico) ?>;
    const data = <?= json_encode($datosGrafico) ?>;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#3b82f6', // blue
                    '#10b981', // green
                    '#8b5cf6', // purple
                    '#f59e0b', // yellow
                    '#ef4444', // red
                    '#ec4899', // pink
                    '#14b8a6'  // teal
                ],
                borderWidth: 2,
                borderColor: '#1f2937'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#9ca3af',
                        padding: 20,
                        font: {
                            size: 13
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            return ' Ingresos: $' + value.toLocaleString('es-AR');
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
});
</script>
</body>
</html>
