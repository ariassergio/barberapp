<?php

require_once 'includes/auth.php';
require_once 'config/db.php';

$sql = "SELECT * FROM servicios";
$resultado = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/servicios.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="admin-container">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">

        <?php include 'includes/navbar.php'; ?>

        <section class="dashboard">

            <div class="servicios-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h1 class="dashboard-title mb-1">Servicios</h1>
                    <p class="mb-0 text-blanco">Configurá la oferta, precios y tiempos de la barbería</p>
                </div>
                <button class="new-servicio-btn" data-bs-toggle="modal" data-bs-target="#modalServicio">
                    <i class="fa-solid fa-plus me-2"></i> Nuevo servicio
                </button>
            </div>

            <div class="cards-container">

                <?php while($servicio = mysqli_fetch_assoc($resultado)) : 
                    // Si el servicio está deshabilitado, le agregamos una clase para opacarlo en CSS
                    $claseInactivo = ($servicio['activo'] == 0) ? 'servicio-deshabilitado' : '';
                ?>

                <div class="admin-card <?= $claseInactivo ?>">
                    <div class="card-service-icon">
                        <i class="fa-solid fa-scissors"></i>
                    </div>

                    <h3><?= htmlspecialchars($servicio['nombre']); ?></h3>

                    <div class="service-meta-data">
                        <p class="service-price">
                            $<?= number_format($servicio['precio'], 0, ',', '.'); ?>
                        </p>
                        <span class="service-duration">
                            <i class="fa-regular fa-clock me-1"></i> 
                            <?= $servicio['duracion']; ?> <?= htmlspecialchars($servicio['unidad_tiempo']); ?>
                        </span>
                    </div>

                    <div class="service-card-actions w-100 mt-3 d-flex flex-column gap-2">
                        <button class="btn-card-action btn-edit-serv btn-editar"
                                data-id="<?= $servicio['id_servicio']; ?>"
                                data-nombre="<?= htmlspecialchars($servicio['nombre']); ?>"
                                data-precio="<?= $servicio['precio']; ?>"
                                data-duracion="<?= $servicio['duracion']; ?>"
                                data-unidad="<?= htmlspecialchars($servicio['unidad_tiempo']); ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarServicio">
                            <i class="fa-solid fa-pen me-2"></i> Editar catálogo
                        </button>

                        <form action="actions/toggle_servicio.php" method="POST" class="w-100 m-0 form-toggle-servicio">
                            <input type="hidden" name="id_servicio" value="<?= $servicio['id_servicio']; ?>">
                            <input type="hidden" name="estado_actual" value="<?= $servicio['activo']; ?>">

                            <?php if($servicio['activo'] == 1): ?>
                                <button type="button" class="btn-card-action btn-toggle-disable w-100 btn-confirm-toggle" data-action="deshabilitar">
                                    <i class="fa-solid fa-ban me-2"></i> Deshabilitar
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn-card-action btn-toggle-enable w-100 btn-confirm-toggle" data-action="habilitar">
                                    <i class="fa-solid fa-check me-2"></i> Habilitar servicio
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

<div class="modal fade" id="modalServicio" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal">
            <form action="actions/crear_servicios.php" method="POST" class="m-0">

                <div class="modal-header border-0">
                    <div class="modal-header-content">
                        <div class="modal-icon create-icon">
                            <i class="fa-solid fa-scissors"></i>
                        </div>
                        <div>
                            <h4 class="modal-title mb-1">Agregar Servicio</h4>
                            <p class="modal-subtitle mb-0">Introducí los datos para el nuevo servicio</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Servicio</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-signature"></i></span>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Corte + Perfilado de Barba" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Precio</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-dollar-sign"></i></span>
                            <input type="number" name="precio" class="form-control" placeholder="0000" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Duración Estimada</label>
                        <div class="duracion-row d-flex gap-2">
                            <div class="input-group custom-input flex-grow-1">
                                <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                                <input type="number" name="duracion" class="form-control" placeholder="30" required>
                            </div>
                            <select name="unidad" class="form-select modern-select m-0" style="width: 140px;">
                                <option value="minutos">Minutos</option>
                                <option value="horas">Horas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-save">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Guardar servicio
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarServicio" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal">
            <form action="actions/editar_servicio.php" method="POST" class="m-0" id="form-editar-servicio">

                <div class="modal-header border-0">
                    <div class="modal-header-content">
                        <div class="modal-icon edit-icon">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h4 class="modal-title mb-1">Editar Servicio</h4>
                            <p class="modal-subtitle mb-0">Modificá los valores del catálogo actual</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_servicio" id="editar-id">

                    <div class="mb-3">
                        <label class="form-label">Nombre del Servicio</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-signature"></i></span>
                            <input type="text" name="nombre" id="editar-nombre" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Precio</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-dollar-sign"></i></span>
                            <input type="number" name="precio" id="editar-precio" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Duración Estimada</label>
                        <div class="duracion-row d-flex gap-2">
                            <div class="input-group custom-input flex-grow-1">
                                <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                                <input type="number" name="duracion" id="editar-duracion" class="form-control" required>
                            </div>
                            <select name="unidad" id="editar-unidad" class="form-select modern-select m-0" style="width: 140px;">
                                <option value="minutos">Minutos</option>
                                <option value="horas">Horas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-save" id="btn-save-edit-servicio">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const botonesEditar = document.querySelectorAll(".btn-editar");

botonesEditar.forEach(boton => {
    boton.addEventListener("click", () => {
        document.getElementById("editar-id").value = boton.dataset.id;
        document.getElementById("editar-nombre").value = boton.dataset.nombre;
        document.getElementById("editar-precio").value = boton.dataset.precio;
        document.getElementById("editar-duracion").value = boton.dataset.duracion;
        document.getElementById("editar-unidad").value = boton.dataset.unidad;
    });
});

document.getElementById('btn-save-edit-servicio').addEventListener('click', function() {
    Swal.fire({
        title: '¿Guardar cambios?',
        text: '¿Estás seguro de modificar este servicio?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-editar-servicio').submit();
        }
    });
});

document.querySelectorAll(".btn-confirm-toggle").forEach(boton => {
    boton.addEventListener("click", function() {
        const accion = this.dataset.action;
        const form = this.closest('form');
        
        Swal.fire({
            title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} servicio?`,
            text: `Estás a punto de ${accion} este servicio en el catálogo.`,
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