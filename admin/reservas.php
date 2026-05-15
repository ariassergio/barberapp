<?php

require_once 'config/db.php';

$sql = "SELECT 

            turnos.*,

            profesionales.nombre AS profesional_nombre,

            servicios.nombre AS servicio_nombre

        FROM turnos

        INNER JOIN profesionales
        ON turnos.id_profesional =
           profesionales.id_profesional

        INNER JOIN servicios
        ON turnos.id_servicio =
           servicios.id_servicio

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

    <link rel="stylesheet"
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

                    <?php while($turno = mysqli_fetch_assoc($resultado)) : ?>

                    <tr>

                        <td>

                            <div class="cliente-info">

                                <img
                                    src="https://i.pravatar.cc/45?u=<?= $turno['nombre']; ?>"
                                    class="cliente-avatar">

                                <div>

                                    <h4>
                                        <?= $turno['nombre']; ?>
                                    </h4>

                                    <span>
                                        <?= $turno['telefono']; ?>
                                    </span>

                                </div>

                            </div>

                        </td>

                        <td>
                            <?= $turno['servicio_nombre']; ?>
                        </td>

                        <td>
                            <?= $turno['profesional_nombre']; ?>
                        </td>

                        <td>

                            <?= date(
                                "d/m/Y",
                                strtotime($turno['fecha_inicio'])
                            ); ?>

                        </td>

                        <td>

                            <?= date(
                                "H:i",
                                strtotime($turno['fecha_inicio'])
                            ); ?>

                        </td>

                        <td>

                            <span class="estado <?= strtolower($turno['estado']); ?>">

                                <?= ucfirst($turno['estado']); ?>

                            </span>

                        </td>

                        <td>

                            <div class="reservas-actions">

                                <!-- VER -->
                                <button
                                    class="reserva-btn btn-view"

                                    data-cliente="<?= $turno['nombre']; ?>"
                                    data-telefono="<?= $turno['telefono']; ?>"
                                    data-servicio="<?= $turno['servicio_nombre']; ?>"
                                    data-profesional="<?= $turno['profesional_nombre']; ?>"
                                    data-fecha="<?= date('d/m/Y', strtotime($turno['fecha_inicio'])); ?>"
                                    data-hora="<?= date('H:i', strtotime($turno['fecha_inicio'])); ?>"
                                    data-estado="<?= $turno['estado']; ?>"
                                    data-registro="<?= date('d/m/Y H:i', strtotime($turno['fecha_registro'])); ?>"

                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetalleReserva">

                                    <i class="fa-solid fa-eye"></i>

                                </button>

                                <!-- EDITAR -->
                                <button class="reserva-btn btn-edit">

                                    <i class="fa-solid fa-pen"></i>

                                </button>

                                <!-- CANCELAR -->
                                <a
                                    href="actions/cancelar_turno.php?id=<?= $turno['id_turno']; ?>"
                                    class="reserva-btn btn-delete">

                                    <i class="fa-solid fa-xmark"></i>

                                </a>

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
<div
    class="modal fade"
    id="modalDetalleReserva"
    tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Detalle de reserva
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <strong>Cliente:</strong>

                        <p id="detalle-cliente"></p>

                    </div>

                    <div class="col-md-6">

                        <strong>Teléfono:</strong>

                        <p id="detalle-telefono"></p>

                    </div>

                    <div class="col-md-6">

                        <strong>Email:</strong>

                        <p id="detalle-email"></p>

                    </div>

                    <div class="col-md-6">

                        <strong>Servicio:</strong>

                        <p id="detalle-servicio"></p>

                    </div>

                    <div class="col-md-6">

                        <strong>Profesional:</strong>

                        <p id="detalle-profesional"></p>

                    </div>

                    <div class="col-md-6">

                        <strong>Fecha:</strong>

                        <p id="detalle-fecha"></p>

                    </div>

                    <div class="col-md-6">

                        <strong>Hora:</strong>

                        <p id="detalle-hora"></p>

                    </div>

                    <div class="col-md-6">

                        <strong>Estado:</strong>

                        <p id="detalle-estado"></p>

                    </div>

                    <div class="col-12">

                        <strong>Notas:</strong>

                        <p id="detalle-notas"></p>

                    </div>

                    <div class="col-12">

                        <strong>Reserva creada:</strong>

                        <p id="detalle-registro"></p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>

const botonesVer = document.querySelectorAll(".btn-view");

botonesVer.forEach(boton => {

    boton.addEventListener("click", () => {

        document.getElementById("detalle-cliente").textContent =
            boton.dataset.cliente;

        document.getElementById("detalle-telefono").textContent =
            boton.dataset.telefono;


        document.getElementById("detalle-servicio").textContent =
            boton.dataset.servicio;

        document.getElementById("detalle-profesional").textContent =
            boton.dataset.profesional;

        document.getElementById("detalle-fecha").textContent =
            boton.dataset.fecha;

        document.getElementById("detalle-hora").textContent =
            boton.dataset.hora;

        document.getElementById("detalle-estado").textContent =
            boton.dataset.estado;


        document.getElementById("detalle-registro").textContent =
            boton.dataset.registro;

    });

});

</script>
</body>

</html>