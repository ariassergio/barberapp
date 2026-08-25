<?php
session_start();
// Ajusta la ruta para llegar a config/db.php desde panel/dashboard.php
require_once '../admin/config/db.php'; 

// 🔒 Proteger acceso: Solo si inició sesión puede ver el dashboard
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

$peluqueroId = $_SESSION["peluquero_id"];
$nombreSesion = $_SESSION["peluquero_nombre"];

$rango = isset($_GET['rango']) ? $_GET['rango'] : 'hoy';

$fechaFiltro = "";
if ($rango === 'semana') {
    $fechaFiltro = "YEARWEEK(fecha_inicio, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($rango === 'mes') {
    $fechaFiltro = "MONTH(fecha_inicio) = MONTH(CURDATE()) AND YEAR(fecha_inicio) = YEAR(CURDATE())";
} else { // hoy
    $fechaFiltro = "DATE(fecha_inicio) = CURDATE()";
}

// 🔹 Estadísticas del Barbero
$sqlStats = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'finalizado' THEN 1 ELSE 0 END) as realizados,
                SUM(CASE WHEN estado IN ('pendiente', 'confirmado') THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN estado = 'cancelado' THEN 1 ELSE 0 END) as cancelados
             FROM turnos 
             WHERE id_profesional = '$peluqueroId' AND $fechaFiltro";
$resStats = mysqli_query($conn, $sqlStats);
$stats = mysqli_fetch_assoc($resStats);

$totalTurnos = $stats['total'] ?? 0;
$realizados = $stats['realizados'] ?? 0;
$pendientes = $stats['pendientes'] ?? 0;
$cancelados = $stats['cancelados'] ?? 0;

// 🔹 Historial (basado en el filtro, se muestra en estadísticas)
$sqlHistorial = "SELECT * FROM turnos 
                 WHERE id_profesional = '$peluqueroId' AND $fechaFiltro
                 ORDER BY fecha_inicio DESC";
$resultadoHistorial = mysqli_query($conn, $sqlHistorial) or die("Error en consulta: " . mysqli_error($conn));

// 🔹 Vista principal: muestra siempre los pendientes de hoy
$sqlPendientes = "SELECT * FROM turnos 
                  WHERE id_profesional = '$peluqueroId' 
                  AND DATE(fecha_inicio) = CURDATE() 
                  AND estado NOT IN ('finalizado', 'cancelado')
                  ORDER BY fecha_inicio ASC";
$resultadoPendientes = mysqli_query($conn, $sqlPendientes) or die("Error en consulta: " . mysqli_error($conn));

// Función para renderizar una tarjeta de turno
function renderizarTurno($r) {
    $fecha = date('d/m/Y', strtotime($r["fecha_inicio"]));
    $hora = date('H:i', strtotime($r["fecha_inicio"]));
    $nombre = htmlspecialchars($r["nombre"]);
    $notas = htmlspecialchars($r["notas"]);
    $telefono = htmlspecialchars($r["telefono"]);
    
    $estadoStr = strtolower($r["estado"]);
    $badgeClass = 'bg-info text-dark';
    if ($estadoStr === 'pendiente') $badgeClass = 'bg-warning text-dark';
    elseif ($estadoStr === 'finalizado') $badgeClass = 'bg-success text-white';
    elseif ($estadoStr === 'cancelado') $badgeClass = 'bg-danger text-white';
    elseif ($estadoStr === 'confirmado') $badgeClass = 'bg-primary text-white';
    
    $estadoTexto = strtoupper(htmlspecialchars($r["estado"]));
    
    $accionesHtml = "";
    if (!in_array($estadoStr, ['finalizado', 'cancelado'])) {
        $id = $r['id_turno'];
        $accionesHtml = "
        <div class='acciones d-flex gap-2 mt-2'>
            <button class='btn btn-success w-100' style='font-size: 15px; padding: 10px;' onclick='actualizarEstado($id, \"finalizado\")'>✔ Realizado</button>
            <button class='btn btn-danger w-100' style='font-size: 15px; padding: 10px;' onclick='actualizarEstado($id, \"cancelado\")'>❌ Ausente</button>
        </div>";
    }

    echo "
    <div class='col-md-4'>
        <div class='turno-card p-3 border rounded shadow-sm h-100 bg-white d-flex flex-column'>
            <div class='fecha text-muted fw-bold'>$fecha</div>
            <div class='hora text-primary mb-2'>$hora hs</div>
            <h5 class='mt-2'>$nombre</h5>
            <p class='servicio text-secondary mb-1'>$notas</p>
            <p class='telefono mb-2'>📞 $telefono</p>
            <div class='mt-auto'>
                <div class='estado badge $badgeClass mb-2'>$estadoTexto</div>
                $accionesHtml
            </div>
        </div>
    </div>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Barbería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">💈 Hola, <?= htmlspecialchars($nombreSesion) ?></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
</nav>

<div class="container my-5">
    
    <!-- BOTÓN PARA VER ESTADÍSTICAS -->
    <div class="mb-4">
        <button class="btn btn-dark" type="button" data-bs-toggle="collapse" data-bs-target="#estadisticasCollapse" aria-expanded="<?= isset($_GET['rango']) ? 'true' : 'false' ?>" aria-controls="estadisticasCollapse">
            📊 Ver Estadísticas
        </button>
    </div>

    <!-- SECCIÓN ESTADÍSTICAS (COLLAPSABLE) -->
    <div class="collapse <?= isset($_GET['rango']) ? 'show' : '' ?>" id="estadisticasCollapse">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h4 class="mb-0 fw-bold">Mis Estadísticas</h4>
                <form method="GET" class="d-flex align-items-center m-0">
                    <select name="rango" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()" style="width: auto; border-radius: 10px; font-weight: 500;">
                        <option value="hoy" <?= $rango == 'hoy' ? 'selected' : '' ?>>Hoy</option>
                        <option value="semana" <?= $rango == 'semana' ? 'selected' : '' ?>>Esta Semana</option>
                        <option value="mes" <?= $rango == 'mes' ? 'selected' : '' ?>>Este Mes</option>
                    </select>
                </form>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <h3 class="fw-bold mb-1"><?= $totalTurnos ?></h3>
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Registrados</span>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <h3 class="text-success fw-bold mb-1"><?= $realizados ?></h3>
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Realizados</span>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <h3 class="text-warning fw-bold mb-1"><?= $pendientes ?></h3>
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Pendientes</span>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <h3 class="text-danger fw-bold mb-1"><?= $cancelados ?></h3>
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Cancelados</span>
                </div>
            </div>
        </div>

        <!-- HISTORIAL DENTRO DE ESTADISTICAS -->
        <div class="mb-4 p-4 bg-light rounded-4 shadow-sm">
            <h5 class="fw-bold mb-3">Historial de Turnos (<?= ucfirst($rango) ?>)</h5>
            <?php if(mysqli_num_rows($resultadoHistorial) === 0): ?>
                <div class="alert alert-secondary border-0 shadow-sm">No hay turnos registrados en este período.</div>
            <?php else: ?>
                <div class="row g-3" style="max-height: 500px; overflow-y: auto; overflow-x: hidden;">
                    <?php while($r = mysqli_fetch_assoc($resultadoHistorial)): ?>
                        <?php renderizarTurno($r); ?>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>

        <hr class="mb-5" style="opacity: 0.1;">
    </div>

    <!-- PANTALLA PRINCIPAL: PENDIENTES DE HOY -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <?php $cantidadTurnos = mysqli_num_rows($resultadoPendientes); ?>
        <h2 class="mb-0">
            Turnos de hoy <span class="badge bg-primary fs-6 ms-2 align-middle"><?= $cantidadTurnos ?></span>
        </h2>
    </div>

    <?php if(mysqli_num_rows($resultadoPendientes) === 0): ?>
        <div class="alert alert-info border-0 shadow-sm">No tenés turnos pendientes para hoy.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php while($r = mysqli_fetch_assoc($resultadoPendientes)): ?>
                <?php renderizarTurno($r); ?>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<!-- ===================== TOAST ===================== -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="toast-msg" class="toast align-items-center text-white border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toast-texto"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function mostrarToast(mensaje, tipo) {
    const toast  = document.getElementById("toast-msg");
    const texto  = document.getElementById("toast-texto");
    texto.textContent = mensaje;
    toast.className = `toast align-items-center text-white border-0 bg-${tipo}`;
    bootstrap.Toast.getOrCreateInstance(toast, { delay: 2500 }).show();
}

function actualizarEstado(id, estado) {
    const estadoTexto = estado === 'finalizado' ? 'Realizado' : 'Ausente';
    
    Swal.fire({
        title: '¿Confirmar acción?',
        text: `Vas a marcar este turno como ${estadoTexto}.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar',
        background: '#212529',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../admin/php/actualizar_estado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id, estado: estado })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarToast("Turno actualizado correctamente", "success");
                    setTimeout(() => location.reload(), 1200);
                } else {
                    mostrarToast("Error al actualizar: " + data.error, "danger");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarToast("Hubo un error al comunicarnos con el servidor.", "danger");
            });
        }
    });
}
</script>
</body>
</html>