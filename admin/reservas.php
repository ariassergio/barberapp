<?php

$reservasJson = file_get_contents('../data/reservas.json');

$reservas = json_decode($reservasJson, true);

if (!$reservas) {
    $reservas = [];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Reservas</title>

    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/reservas.css">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

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

                    <h1 class="dashboard-title">
                        Gestión de Reservas
                    </h1>

                    <p class="reservas-subtitle">
                        Administrá todos los turnos de la barbería
                    </p>

                </div>

                <button class="new-reserva-btn">

                    <i class="fa-solid fa-plus"></i>

                    Nueva Reserva

                </button>

            </div>

            <!-- FILTROS -->
            <div class="reservas-filters">

                <input
                    type="text"
                    class="reservas-search"
                    placeholder="Buscar cliente...">

                <select class="reservas-select">

                    <option>Todos los estados</option>
                    <option>Pendiente</option>
                    <option>Confirmado</option>
                    <option>Cancelado</option>
                    <option>Finalizado</option>

                </select>

                <select class="reservas-select">

                    <option>Todos los barberos</option>
                    <option>Lucas</option>
                    <option>Franco</option>
                    <option>Matías</option>

                </select>

            </div>

            <!-- TABLA -->
            <div class="table-container">

                <table class="table table-hover align-middle">

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

                        <?php if (empty($reservas)): ?>

                            <tr>

                                <td colspan="7" class="text-center py-4">

                                    No hay reservas registradas

                                </td>

                            </tr>

                        <?php endif; ?>

                        <?php foreach ($reservas as $reserva): ?>

                            <tr>

                                <td>

                                    <div class="cliente-info">

                                        <img
                                            src="https://i.pravatar.cc/45?u=<?php echo $reserva['cliente']; ?>"
                                            class="cliente-avatar">

                                        <div>

                                            <h4>
                                                <?php echo $reserva['cliente']; ?>
                                            </h4>

                                            <span>
                                                <?php echo $reserva['telefono']; ?>
                                            </span>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <?php
                                        echo isset($reserva['serviciosTexto'])
                                            ? $reserva['serviciosTexto']
                                            : $reserva['servicio'];
                                    ?>

                                </td>

                                <td>
                                    <?php echo $reserva['peluquero']; ?>
                                </td>

                                <td>
                                    <?php echo date('d/m/Y', strtotime($reserva['fecha'])); ?>
                                </td>

                                <td>
                                    <?php echo $reserva['hora']; ?>
                                </td>

                                <td>

                                    <span class="estado <?php echo $reserva['estado']; ?>">

                                        <?php echo ucfirst($reserva['estado']); ?>

                                    </span>

                                </td>

                                <td>

                                    <div class="reservas-actions">

                                        <button
                                            class="reserva-btn btn-view"
                                            data-id="<?php echo $reserva['id']; ?>"

                                            data-cliente="<?php echo $reserva['cliente']; ?>"

                                            data-telefono="<?php echo $reserva['telefono']; ?>"

                                            data-servicio="<?php echo isset($reserva['serviciosTexto']) ? $reserva['serviciosTexto'] : $reserva['servicio']; ?>"

                                            data-peluquero="<?php echo $reserva['peluquero']; ?>"

                                            data-fecha="<?php echo $reserva['fecha']; ?>"

                                            data-hora="<?php echo $reserva['hora']; ?>"

                                            data-estado="<?php echo $reserva['estado']; ?>">

                                            <i class="fa-solid fa-eye"></i>

                                        </button>

                                        <button
                                            class="reserva-btn btn-edit"
                                            data-id="<?php echo $reserva['id']; ?>"

                                            data-cliente="<?php echo $reserva['cliente']; ?>"

                                            data-telefono="<?php echo $reserva['telefono']; ?>"

                                            data-servicio="<?php echo isset($reserva['serviciosTexto']) ? $reserva['serviciosTexto'] : $reserva['servicio']; ?>"

                                            data-peluquero="<?php echo $reserva['peluquero']; ?>"

                                            data-fecha="<?php echo $reserva['fecha']; ?>"

                                            data-hora="<?php echo $reserva['hora']; ?>"

                                            data-estado="<?php echo $reserva['estado']; ?>">
                                            <i class="fa-solid fa-pen"></i>

                                        </button>

                                        <button
                                            class="reserva-btn btn-delete"
                                            data-id="<?php echo $reserva['id']; ?>">

                                            <i class="fa-solid fa-trash"></i>

                                        </button>

                                    </div>

                                </td>
                                <!-- MODAL VER RESERVA -->

                                <div
                                    class="modal fade"
                                    id="viewReservaModal"
                                    tabindex="-1">

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content">

                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    Detalle de Reserva
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                                </button>

                                            </div>

                                            <div class="modal-body">

                                                <p><strong>Cliente:</strong> <span id="viewCliente"></span></p>

                                                <p><strong>Teléfono:</strong> <span id="viewTelefono"></span></p>

                                                <p><strong>Servicio:</strong> <span id="viewServicio"></span></p>

                                                <p><strong>Barbero:</strong> <span id="viewPeluquero"></span></p>

                                                <p><strong>Fecha:</strong> <span id="viewFecha"></span></p>

                                                <p><strong>Hora:</strong> <span id="viewHora"></span></p>

                                                <p><strong>Estado:</strong> <span id="viewEstado"></span></p>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>
            <!-- MODAL EDITAR RESERVA -->

            <div
                class="modal fade"
                id="editReservaModal"
                tabindex="-1">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Editar Reserva
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <form id="editReservaForm">

                                <input
                                    type="hidden"
                                    id="editId">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Cliente
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="editCliente">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Teléfono
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="editTelefono">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Servicio
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="editServicio">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Barbero
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="editPeluquero">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Fecha
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control"
                                        id="editFecha">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Hora
                                    </label>

                                    <input
                                        type="time"
                                        class="form-control"
                                        id="editHora">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Estado
                                    </label>

                                    <select
                                        class="form-select"
                                        id="editEstado">

                                        <option value="pendiente">
                                            Pendiente
                                        </option>

                                        <option value="confirmado">
                                            Confirmado
                                        </option>

                                        <option value="cancelado">
                                            Cancelado
                                        </option>

                                        <option value="finalizado">
                                            Finalizado
                                        </option>

                                    </select>

                                </div>

                            </form>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                                Cancelar

                            </button>

                            <button
                                type="button"
                                class="btn btn-dark"
                                id="saveEditBtn">

                                Guardar Cambios

                            </button>

                        </div>

                    </div>

                </div>

            </div>
            <!-- MODAL ELIMINAR RESERVA -->

            <div
                class="modal fade"
                id="deleteReservaModal"
                tabindex="-1">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-header border-0">

                            <h5 class="modal-title">
                                Eliminar Reserva
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body text-center">

                            <div class="delete-icon mb-3">

                                <i class="fa-solid fa-trash"></i>

                            </div>

                            <h4 class="mb-2">
                                ¿Eliminar esta reserva?
                            </h4>

                            <p class="text-muted">

                                Esta acción no se puede deshacer.

                            </p>

                            <input
                                type="hidden"
                                id="deleteReservaId">

                        </div>

                        <div class="modal-footer border-0">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                                Cancelar

                            </button>

                            <button
                                type="button"
                                class="btn btn-danger"
                                id="confirmDeleteBtn">

                                Sí, eliminar

                            </button>

                        </div>

                    </div>

                </div>

            </div>
        </section>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>

    const viewButtons = document.querySelectorAll('.btn-view');

    viewButtons.forEach(button => {

        button.addEventListener('click', () => {

            document.getElementById('viewCliente').textContent =
                button.dataset.cliente;

            document.getElementById('viewTelefono').textContent =
                button.dataset.telefono;

            document.getElementById('viewServicio').textContent =
                button.dataset.servicio;

            document.getElementById('viewPeluquero').textContent =
                button.dataset.peluquero;

            document.getElementById('viewFecha').textContent =
                button.dataset.fecha;

            document.getElementById('viewHora').textContent =
                button.dataset.hora;

            document.getElementById('viewEstado').textContent =
                button.dataset.estado;

            const modal = new bootstrap.Modal(
                document.getElementById('viewReservaModal')
            );

            modal.show();

        });

    });
    const editButtons = document.querySelectorAll('.btn-edit');

    editButtons.forEach(button => {

        button.addEventListener('click', () => {

            document.getElementById('editId').value =
                button.dataset.id;

            document.getElementById('editCliente').value =
                button.dataset.cliente;

            document.getElementById('editTelefono').value =
                button.dataset.telefono;

            document.getElementById('editServicio').value =
                button.dataset.servicio;

            document.getElementById('editPeluquero').value =
                button.dataset.peluquero;

            document.getElementById('editFecha').value =
                button.dataset.fecha;

            document.getElementById('editHora').value =
                button.dataset.hora;

            document.getElementById('editEstado').value =
                button.dataset.estado;

            const modal = new bootstrap.Modal(
                document.getElementById('editReservaModal')
            );

            modal.show();

        });

    });
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {

        button.addEventListener('click', () => {

            document.getElementById('deleteReservaId').value =
                button.dataset.id;

            const modal = new bootstrap.Modal(
                document.getElementById('deleteReservaModal')
            );

            modal.show();

        });

    });

    document
        .getElementById('confirmDeleteBtn')
        .addEventListener('click', () => {

            const reservaId =
                document.getElementById('deleteReservaId').value;

            console.log('Eliminar reserva ID:', reservaId);

            // ACA DESPUÉS VA LA ELIMINACIÓN REAL

            bootstrap.Modal
                .getInstance(
                    document.getElementById('deleteReservaModal')
                )
                .hide();

    });
    document
    .getElementById('saveEditBtn')
    .addEventListener('click', async () => {

        const data = {

            id: document.getElementById('editId').value,

            cliente:
                document.getElementById('editCliente').value,

            telefono:
                document.getElementById('editTelefono').value,

            servicio:
                document.getElementById('editServicio').value,

            peluquero:
                document.getElementById('editPeluquero').value,

            fecha:
                document.getElementById('editFecha').value,

            hora:
                document.getElementById('editHora').value,

            estado:
                document.getElementById('editEstado').value
        };

        const res = await fetch(
            'api/editar-reserva.php',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify(data)
            }
        );

        const result = await res.json();

        if (result.ok) {

            location.reload();

        } else {

            alert('Error al editar reserva');

        }

    });
</script>
</body>

</html>