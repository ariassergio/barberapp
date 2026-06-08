<?php

require_once 'includes/auth.php';
require_once 'config/db.php';

// Cargar profesionales y servicios para los selects del modal editar
$profesionales = [];
$res = mysqli_query($conn, "SELECT id_profesional, nombre FROM profesionales WHERE activo = 1");
while ($p = mysqli_fetch_assoc($res)) $profesionales[] = $p;

$servicios = [];
$res = mysqli_query($conn, "SELECT id_servicio, nombre FROM servicios WHERE activo = 1");
while ($s = mysqli_fetch_assoc($res)) $servicios[] = $s;

// Turnos
$sql = "SELECT
            turnos.*,
            profesionales.nombre AS profesional_nombre,
            servicios.nombre AS servicio_nombre
        FROM turnos
        INNER JOIN profesionales ON turnos.id_profesional = profesionales.id_profesional
        INNER JOIN servicios     ON turnos.id_servicio    = servicios.id_servicio
        ORDER BY turnos.fecha_inicio DESC";

$resultado = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/reservas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="admin-container">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">

        <?php include 'includes/navbar.php'; ?>

        <section class="dashboard">

            <!-- HEADER -->
            <div class="reservas-header">
                <div>
                    <h1 class="dashboard-title">Gestión de Reservas</h1>
                    <p class="reservas-subtitle">Administrá todos los turnos de la barbería</p>
                </div>
                <button class="new-reserva-btn" data-bs-toggle="modal" data-bs-target="#modalNuevaReserva">
                    <i class="fa-solid fa-plus"></i> Nueva Reserva
                </button>
            </div>

            <!-- FILTROS -->
            <div class="reservas-filters">
                <input type="text" id="filtro-cliente" class="reservas-search" placeholder="Buscar cliente...">
                <select id="filtro-estado" class="reservas-select">
                    <option value="">Todos los estados</option>
                    <option>pendiente</option>
                    <option>confirmado</option>
                    <option>cancelado</option>
                    <option>finalizado</option>
                </select>
                <select id="filtro-barbero" class="reservas-select">
                    <option value="">Todos los barberos</option>
                    <?php foreach ($profesionales as $p): ?>
                        <option><?= htmlspecialchars($p['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- TABLA -->
            <div class="table-container">
                <table class="table table-hover align-middle" id="tabla-reservas">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Barbero</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($turno = mysqli_fetch_assoc($resultado)): ?>
                    <tr
                        data-cliente="<?= htmlspecialchars($turno['nombre']) ?>"
                        data-estado="<?= $turno['estado'] ?>"
                        data-barbero="<?= htmlspecialchars($turno['profesional_nombre']) ?>">

                        <td>
                            <div class="cliente-info">
                                <img src="https://i.pravatar.cc/45?u=<?= urlencode($turno['nombre']) ?>" class="cliente-avatar">
                                <div>
                                    <h4><?= htmlspecialchars($turno['nombre']) ?></h4>
                                    <span><?= htmlspecialchars($turno['telefono']) ?></span>
                                </div>
                            </div>
                        </td>

                        <td><?= htmlspecialchars($turno['servicio_nombre']) ?></td>
                        <td><?= htmlspecialchars($turno['profesional_nombre']) ?></td>

                        <td><?= date("d/m/Y", strtotime($turno['fecha_inicio'])) ?></td>
                        <td><?= date("H:i", strtotime($turno['fecha_inicio'])) ?></td>

                        <td>
                            <span class="estado <?= strtolower($turno['estado']) ?>">
                                <?= ucfirst($turno['estado']) ?>
                            </span>
                        </td>

                        <td>
                            <div class="reservas-actions">

                                <!-- VER -->
                                <button class="reserva-btn btn-view"
                                    data-cliente="<?= htmlspecialchars($turno['nombre']) ?>"
                                    data-telefono="<?= htmlspecialchars($turno['telefono']) ?>"
                                    data-servicio="<?= htmlspecialchars($turno['servicio_nombre']) ?>"
                                    data-profesional="<?= htmlspecialchars($turno['profesional_nombre']) ?>"
                                    data-fecha="<?= date('d/m/Y', strtotime($turno['fecha_inicio'])) ?>"
                                    data-hora="<?= date('H:i', strtotime($turno['fecha_inicio'])) ?>"
                                    data-estado="<?= $turno['estado'] ?>"
                                    data-registro="<?= date('d/m/Y H:i', strtotime($turno['fecha_registro'])) ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetalleReserva">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                                <!-- EDITAR -->
                                <button class="reserva-btn btn-edit"
                                    data-id="<?= $turno['id_turno'] ?>"
                                    data-estado="<?= $turno['estado'] ?>"
                                    data-fecha="<?= date('Y-m-d', strtotime($turno['fecha_inicio'])) ?>"
                                    data-hora="<?= date('H:i', strtotime($turno['fecha_inicio'])) ?>"
                                    data-id-profesional="<?= $turno['id_profesional'] ?>"
                                    data-id-servicio="<?= $turno['id_servicio'] ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarReserva">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <!-- CANCELAR -->
                                <button class="reserva-btn btn-delete"
                                    data-id="<?= $turno['id_turno'] ?>"
                                    data-cliente="<?= htmlspecialchars($turno['nombre']) ?>">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>

                            </div>
                        </td>

                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </section>
    </main>
</div>


<!-- ===================== MODAL VER ===================== -->
<div class="modal fade" id="modalDetalleReserva" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><strong>Cliente:</strong><p id="detalle-cliente"></p></div>
                    <div class="col-md-6"><strong>Teléfono:</strong><p id="detalle-telefono"></p></div>
                    <div class="col-md-6"><strong>Servicio:</strong><p id="detalle-servicio"></p></div>
                    <div class="col-md-6"><strong>Profesional:</strong><p id="detalle-profesional"></p></div>
                    <div class="col-md-6"><strong>Fecha:</strong><p id="detalle-fecha"></p></div>
                    <div class="col-md-6"><strong>Hora:</strong><p id="detalle-hora"></p></div>
                    <div class="col-md-6"><strong>Estado:</strong><p id="detalle-estado"></p></div>
                    <div class="col-12"><strong>Reserva creada:</strong><p id="detalle-registro"></p></div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ===================== MODAL EDITAR ===================== -->
<div class="modal fade" id="modalEditarReserva" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Editar reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="edit-id">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Estado</label>
                    <select id="edit-estado" class="form-select">
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmado">Confirmado</option>
                        <option value="cancelado">Cancelado</option>
                        <option value="finalizado">Finalizado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Fecha</label>
                    <input type="date" id="edit-fecha" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Hora</label>
                    <input type="time" id="edit-hora" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Profesional</label>
                    <select id="edit-profesional" class="form-select">
                        <?php foreach ($profesionales as $p): ?>
                            <option value="<?= $p['id_profesional'] ?>">
                                <?= htmlspecialchars($p['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Servicio</label>
                    <select id="edit-servicio" class="form-select">
                        <?php foreach ($servicios as $s): ?>
                            <option value="<?= $s['id_servicio'] ?>">
                                <?= htmlspecialchars($s['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-edicion">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Guardar cambios
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ===================== MODAL NUEVA RESERVA ===================== -->
<div class="modal fade" id="modalNuevaReserva" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content nueva-reserva-modal">

            <!-- HEADER -->
            <div class="modal-header border-0">

                <div class="modal-header-content">
                    <div class="modal-icon">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>

                    <div>
                        <h4 class="modal-title mb-1">Nueva reserva</h4>
                        <p class="modal-subtitle mb-0">
                            Registrá un nuevo turno manualmente
                        </p>
                    </div>
                </div>

                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal"></button>

            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- CLIENTE -->
                <div class="modal-section">

                    <h6 class="section-title">
                        <i class="fa-solid fa-user"></i>
                        Información del cliente
                    </h6>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Cliente</label>

                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-user"></i>
                                </span>

                                <input
                                    type="text"
                                    id="new-cliente"
                                    class="form-control"
                                    placeholder="Nombre del cliente">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>

                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-phone"></i>
                                </span>

                                <input
                                    type="text"
                                    id="new-telefono"
                                    class="form-control"
                                    placeholder="11 2345-6789">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- RESERVA -->
                <div class="modal-section">

                    <h6 class="section-title">
                        <i class="fa-solid fa-scissors"></i>
                        Información de la reserva
                    </h6>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Fecha</label>

                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-calendar"></i>
                                </span>

                                <input
                                    type="date"
                                    id="new-fecha"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hora</label>

                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-clock"></i>
                                </span>

                                <input
                                    type="time"
                                    id="new-hora"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Profesional</label>

                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-user-tie"></i>
                                </span>

                                <select id="new-profesional" class="form-select">

                                    <?php foreach ($profesionales as $p): ?>
                                        <option value="<?= $p['id_profesional'] ?>">
                                            <?= htmlspecialchars($p['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Servicio</label>

                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-scissors"></i>
                                </span>

                                <select id="new-servicio" class="form-select">

                                    <?php foreach ($servicios as $s): ?>
                                        <option value="<?= $s['id_servicio'] ?>">
                                            <?= htmlspecialchars($s['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Estado</label>

                            <select id="new-estado" class="form-select modern-select">
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmado">Confirmado</option>
                                <option value="finalizado">Finalizado</option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- RESUMEN -->
                <div class="resumen-card">

                    <div class="resumen-icon">
                        <i class="fa-solid fa-receipt"></i>
                    </div>

                    <div>
                        <h6 class="mb-1 h6-modalreserva">Reserva manual</h6>

                        <p class="mb-0 p-modalreserva">
                            El turno se registrará automáticamente
                            en el historial y estadísticas.
                        </p>
                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer border-0">

                <button
                    type="button"
                    class="btn btn-cancel"
                    data-bs-dismiss="modal">

                    Cancelar
                </button>

                <button
                    type="button"
                    class="btn btn-save"
                    id="btn-crear-reserva">

                    <i class="fa-solid fa-plus me-2"></i>
                    Crear reserva
                </button>

            </div>

        </div>
    </div>
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>

// =====================
// MODAL VER
// =====================
document.querySelectorAll(".btn-view").forEach(btn => {
    btn.addEventListener("click", () => {
        document.getElementById("detalle-cliente").textContent    = btn.dataset.cliente;
        document.getElementById("detalle-telefono").textContent   = btn.dataset.telefono;
        document.getElementById("detalle-servicio").textContent   = btn.dataset.servicio;
        document.getElementById("detalle-profesional").textContent= btn.dataset.profesional;
        document.getElementById("detalle-fecha").textContent      = btn.dataset.fecha;
        document.getElementById("detalle-hora").textContent       = btn.dataset.hora;
        document.getElementById("detalle-estado").textContent     = btn.dataset.estado;
        document.getElementById("detalle-registro").textContent   = btn.dataset.registro;
    });
});


// =====================
// MODAL EDITAR — pre-cargar datos
// =====================
document.querySelectorAll(".btn-edit").forEach(btn => {
    btn.addEventListener("click", () => {
        document.getElementById("edit-id").value         = btn.dataset.id;
        document.getElementById("edit-estado").value     = btn.dataset.estado;
        document.getElementById("edit-fecha").value      = btn.dataset.fecha;
        document.getElementById("edit-hora").value       = btn.dataset.hora;
        document.getElementById("edit-profesional").value= btn.dataset.idProfesional;
        document.getElementById("edit-servicio").value   = btn.dataset.idServicio;
    });
});


// =====================
// GUARDAR EDICION
// =====================
document.getElementById("btn-guardar-edicion").addEventListener("click", async () => {

    const id      = document.getElementById("edit-id").value;
    const estado  = document.getElementById("edit-estado").value;
    const fecha   = document.getElementById("edit-fecha").value;
    const hora    = document.getElementById("edit-hora").value;
    const profId  = document.getElementById("edit-profesional").value;
    const servId  = document.getElementById("edit-servicio").value;

    if (!fecha || !hora) {
        mostrarToast("Completá fecha y hora", "danger");
        return;
    }

    const res = await fetch("actions/editar_reserva.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            id_turno:       parseInt(id),
            estado:         estado,
            fecha:          fecha,
            hora:           hora,
            id_profesional: parseInt(profId),
            id_servicio:    parseInt(servId)
        })
    });

    const data = await res.json();

    if (data.ok) {
        bootstrap.Modal.getInstance(
            document.getElementById("modalEditarReserva")
        ).hide();
        mostrarToast("Reserva actualizada correctamente", "success");
        setTimeout(() => location.reload(), 1200);
    } else {
        mostrarToast("Error al guardar los cambios", "danger");
    }
});
// =====================
// CREAR RESERVA
// =====================
document.getElementById("btn-crear-reserva").addEventListener("click", async () => {

const nombre       = document.getElementById("new-cliente").value;
const telefono     = document.getElementById("new-telefono").value;
const fecha        = document.getElementById("new-fecha").value;
const hora         = document.getElementById("new-hora").value;
const profesional  = document.getElementById("new-profesional").value;
const servicio     = document.getElementById("new-servicio").value;
const estado       = document.getElementById("new-estado").value;

if (!nombre || !telefono || !fecha || !hora) {
    mostrarToast("Completá todos los campos", "danger");
    return;
}

const res = await fetch("actions/crear_reserva.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
        nombre,
        telefono,
        fecha,
        hora,
        id_profesional: parseInt(profesional),
        id_servicio: parseInt(servicio),
        estado
    })
});

const data = await res.json();

if (data.ok) {

    bootstrap.Modal.getInstance(
        document.getElementById("modalNuevaReserva")
    ).hide();

    mostrarToast("Reserva creada correctamente", "success");

    setTimeout(() => {
        location.reload();
    }, 1200);

} else {
    mostrarToast("Error al crear la reserva", "danger");
}

});

// =====================
// CANCELAR RESERVA
// =====================
document.querySelectorAll(".btn-delete").forEach(btn => {
    btn.addEventListener("click", async () => {

        const nombre = btn.dataset.cliente;
        const id     = btn.dataset.id;

        if (!confirm(`¿Cancelar el turno de ${nombre}?`)) return;

        const res  = await fetch(`actions/cancelar_turno.php?id=${id}`);
        const data = await res.json();

        if (data.ok) {
            mostrarToast("Turno cancelado", "success");
            setTimeout(() => location.reload(), 1200);
        } else {
            mostrarToast("Error al cancelar", "danger");
        }
    });
});


// =====================
// FILTROS EN TIEMPO REAL
// =====================
const filtroCliente = document.getElementById("filtro-cliente");
const filtroEstado  = document.getElementById("filtro-estado");
const filtroBarbero = document.getElementById("filtro-barbero");

function filtrar() {
    const cliente = filtroCliente.value.toLowerCase();
    const estado  = filtroEstado.value.toLowerCase();
    const barbero = filtroBarbero.value.toLowerCase();

    document.querySelectorAll("#tabla-reservas tbody tr").forEach(fila => {
        const matchCliente = fila.dataset.cliente.toLowerCase().includes(cliente);
        const matchEstado  = !estado  || fila.dataset.estado.toLowerCase()  === estado;
        const matchBarbero = !barbero || fila.dataset.barbero.toLowerCase() === barbero;
        fila.style.display = (matchCliente && matchEstado && matchBarbero) ? "" : "none";
    });
}

filtroCliente.addEventListener("input",  filtrar);
filtroEstado.addEventListener("change",  filtrar);
filtroBarbero.addEventListener("change", filtrar);


// =====================
// TOAST HELPER
// =====================
function mostrarToast(mensaje, tipo) {
    const toast  = document.getElementById("toast-msg");
    const texto  = document.getElementById("toast-texto");
    texto.textContent = mensaje;
    toast.className = `toast align-items-center text-white border-0 bg-${tipo}`;
    bootstrap.Toast.getOrCreateInstance(toast, { delay: 2500 }).show();
}

</script>

</body>
</html>