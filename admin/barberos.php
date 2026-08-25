<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$sql = "SELECT p.*, h.hora_inicio, h.hora_fin, h.dia_franco 
        FROM profesionales p 
        LEFT JOIN horarios_profesionales h ON p.id_profesional = h.id_profesional";
$resultado = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barberos</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/barberos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Fix modal input colors */
        .premium-modal .custom-input input, 
        .premium-modal .custom-input select,
        .premium-modal select.modern-select {
            color: #fff !important;
            background-color: #2c2c2c;
        }
        .premium-modal .custom-input input:focus,
        .premium-modal .custom-input select:focus,
        .premium-modal select.modern-select:focus {
            color: #fff !important;
            background-color: #333;
        }
    </style>
</head>

<body>

<div class="admin-container">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">

        <?php include 'includes/navbar.php'; ?>

        <section class="dashboard">

            <div class="barberos-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h1 class="dashboard-title mb-1">Gestión de Profesionales</h1>
                    <p class="text-blanco mb-0">Administrá el equipo de barberos, especialidades y sus horarios de trabajo</p>
                </div>
                <button class="new-barbero-btn" data-bs-toggle="modal" data-bs-target="#modalProfesional">
                    <i class="fa-solid fa-plus me-2"></i> Nuevo profesional
                </button>
            </div>

            <div class="cards-container">

                <?php while($profesional = mysqli_fetch_assoc($resultado)) : 
                    // Si el barbero está deshabilitado, le agregamos una clase para opacarlo en CSS
                    $claseInactivo = ($profesional['activo'] == 0) ? 'profesional-deshabilitado' : '';
                ?>

                <div class="admin-card <?= $claseInactivo ?>">
                    <div class="card-barber-avatar">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>

                    <h3><?= htmlspecialchars($profesional['nombre']); ?></h3>
                    
                    <p class="barber-specialty">
                        <i class="fa-solid fa-scissors me-1 text-primary"></i> 
                        <?= htmlspecialchars($profesional['especialidad']); ?>
                    </p>

                    <div class="barber-meta-data w-100 d-flex justify-content-between align-items-center mt-2">
                        <?php if($profesional['activo'] == 1): ?>
                            <span class="estado-badge activo">🟢 Activo</span>
                        <?php else: ?>
                            <span class="estado-badge inactivo">🔴 Deshabilitado</span>
                        <?php endif; ?>
                    </div>

                    <div class="barber-card-actions w-100 mt-3 d-flex flex-column gap-2">
                        <div class="d-flex gap-2">
                            <button class="btn-card-action btn-edit-barber btn-editar flex-grow-1"
                                    data-id="<?= $profesional['id_profesional']; ?>"
                                    data-nombre="<?= htmlspecialchars($profesional['nombre']); ?>"
                                    data-especialidad="<?= htmlspecialchars($profesional['especialidad']); ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarProfesional">
                                <i class="fa-solid fa-pen me-1"></i> Editar
                            </button>
                            
                            <button class="btn-card-action btn-schedule-barber btn-horarios flex-grow-1"
                                    data-id="<?= $profesional['id_profesional']; ?>"
                                    data-nombre="<?= htmlspecialchars($profesional['nombre']); ?>"
                                    data-inicio="<?= htmlspecialchars($profesional['hora_inicio'] ?? ''); ?>"
                                    data-fin="<?= htmlspecialchars($profesional['hora_fin'] ?? ''); ?>"
                                    data-franco="<?= htmlspecialchars($profesional['dia_franco'] ?? ''); ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalHorarios">
                                <i class="fa-solid fa-clock me-1"></i> Horarios
                            </button>
                        </div>

                        <form action="actions/toggle_profesional.php" method="POST" class="w-100 m-0 form-toggle-profesional">
                            <input type="hidden" name="id_profesional" value="<?= $profesional['id_profesional']; ?>">
                            <input type="hidden" name="estado_actual" value="<?= $profesional['activo']; ?>">

                            <?php if($profesional['activo'] == 1): ?>
                                <button type="button" class="btn-card-action btn-toggle-disable w-100 btn-confirm-toggle" data-action="deshabilitar">
                                    <i class="fa-solid fa-ban me-2"></i> Deshabilitar Barbero
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn-card-action btn-toggle-enable w-100 btn-confirm-toggle" data-action="habilitar">
                                    <i class="fa-solid fa-check me-2"></i> Habilitar Barbero
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <?php endwhile; ?>

            </div>

        </section>
    </main>
</div>

<div class="modal fade" id="modalProfesional" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal">
            <form action="actions/crear_profesional.php" method="POST" class="m-0">

                <div class="modal-header border-0">
                    <div class="modal-header-content">
                        <div class="modal-icon create-icon">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <div>
                            <h4 class="modal-title mb-1">Nuevo Profesional</h4>
                            <p class="modal-subtitle mb-0">Registrá un miembro en el equipo de trabajo</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Profesional</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Lucas Martínez" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label mb-2 d-block">Especialidades</label>
                        <div class="custom-checkbox-card d-flex flex-column gap-2 p-3 rounded-3" style="background-color: #1f2937; border: 1px solid rgba(255,255,255,0.06);">
                            <div class="form-check custom-checkbox">
                                <input class="form-check-input" type="checkbox" value="Barbero" name="especialidades[]" id="barbero_check">
                                <label class="form-check-label text-white" for="barbero_check">💈 Barbero Tradicional</label>
                            </div>
                            <div class="form-check custom-checkbox">
                                <input class="form-check-input" type="checkbox" value="Colorista" name="especialidades[]" id="colorista_check">
                                <label class="form-check-label text-white" for="colorista_check">🎨 Colorista / Estilista</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-save">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Guardar profesional
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarProfesional" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal">
            <form action="actions/editar_profesional.php" method="POST" class="m-0" id="form-edit">

                <div class="modal-header border-0">
                    <div class="modal-header-content">
                        <div class="modal-icon edit-icon">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <div>
                            <h4 class="modal-title mb-1">Editar Profesional</h4>
                            <p class="modal-subtitle mb-0">Modificá el nombre o especialidad del barbero</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_profesional" id="editar-id">

                    <div class="mb-3">
                        <label class="form-label">Nombre del Profesional</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="nombre" id="editar-nombre" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Especialidad Actual</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-scissors"></i></span>
                            <input type="text" name="especialidad" id="editar-especialidad" class="form-control" placeholder="Ej: Barbero, Colorista" required>
                        </div>
                        <small class="text-white-50 mt-1 d-block">Escribí las especialidades separadas por comas.</small>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-save" id="btn-save-edit">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHorarios" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal">
            <form action="actions/guardar_horario.php" method="POST" class="m-0" id="form-horario">

                <div class="modal-header border-0">
                    <div class="modal-header-content">
                        <div class="modal-icon schedule-icon">
                            <i class="fa-solid fa-calendar-clock"></i>
                        </div>
                        <div>
                            <h4 class="modal-title mb-1">Configurar Horarios</h4>
                            <p class="modal-subtitle mb-0">Establecé la jornada laboral y el día de franco</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_profesional" id="horario-id-profesional">

                    <div class="mb-3">
                        <label class="form-label">Profesional Seleccionado</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-user-tie"></i></span>
                            <input type="text" id="horario-nombre" class="form-control" style="opacity: 0.8;" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Día Franco (Descanso)</label>
                        <select name="dia_franco" id="horario-franco" class="form-select modern-select w-100" required>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Hora Entrada</label>
                            <div class="input-group custom-input">
                                <span class="input-group-text"><i class="fa-solid fa-door-open"></i></span>
                                <input type="time" name="hora_inicio" id="horario-inicio" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Hora Salida</label>
                            <div class="input-group custom-input">
                                <span class="input-group-text"><i class="fa-solid fa-door-closed"></i></span>
                                <input type="time" name="hora_fin" id="horario-fin" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-save" id="btn-save-horario">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Guardar jornadas
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ==========================================
// CAPTURA DE DATOS - MODAL HORARIOS
// ==========================================
const botonesHorarios = document.querySelectorAll(".btn-horarios");
botonesHorarios.forEach(boton => {
    boton.addEventListener("click", () => {
        document.getElementById("horario-id-profesional").value = boton.dataset.id;
        document.getElementById("horario-nombre").value = boton.dataset.nombre;
        
        // Poner valores actuales si existen
        if (boton.dataset.inicio) document.getElementById("horario-inicio").value = boton.dataset.inicio;
        else document.getElementById("horario-inicio").value = "";
        
        if (boton.dataset.fin) document.getElementById("horario-fin").value = boton.dataset.fin;
        else document.getElementById("horario-fin").value = "";
        
        if (boton.dataset.franco) document.getElementById("horario-franco").value = boton.dataset.franco;
        else document.getElementById("horario-franco").value = "Lunes";
    });
});

// ==========================================
// CAPTURA DE DATOS - MODAL EDITAR
// ==========================================
const botonesEditar = document.querySelectorAll(".btn-editar");
botonesEditar.forEach(boton => {
    boton.addEventListener("click", () => {
        document.getElementById("editar-id").value = boton.dataset.id;
        document.getElementById("editar-nombre").value = boton.dataset.nombre;
        document.getElementById("editar-especialidad").value = boton.dataset.especialidad;
    });
});

// ==========================================
// SWEETALERT FOR SAVING EDIT AND SCHEDULE
// ==========================================
document.getElementById('btn-save-edit').addEventListener('click', function() {
    Swal.fire({
        title: '¿Guardar cambios?',
        text: '¿Confirmás la edición del barbero?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-edit').submit();
        }
    });
});

document.getElementById('btn-save-horario').addEventListener('click', function() {
    Swal.fire({
        title: '¿Guardar horarios?',
        text: 'Se actualizarán los horarios de este barbero.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-horario').submit();
        }
    });
});

// ==========================================
// SWEETALERT CONFIRMATION FOR TOGGLE
// ==========================================
document.querySelectorAll(".btn-confirm-toggle").forEach(boton => {
    boton.addEventListener("click", function() {
        const accion = this.dataset.action;
        const form = this.closest('form');
        
        Swal.fire({
            title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} barbero?`,
            text: `Estás a punto de ${accion} a este profesional.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
</body>
</html>