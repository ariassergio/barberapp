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

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="admin-container">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">

        <?php include 'includes/navbar.php'; ?>

        <section class="dashboard">

            <h1 class="dashboard-title">
                Servicios
            </h1>
            <div class="mb-4">

                <button
                    class="btn btn-dark"
                    data-bs-toggle="modal"
                    data-bs-target="#modalServicio">

                    <i class="fa-solid fa-plus"></i>
                    Nuevo servicio

                </button>

            </div>
            <div class="cards-container">

                <?php while($servicio = mysqli_fetch_assoc($resultado)) : ?>

                <div class="admin-card">

                    <i class="fa-solid fa-briefcase"></i>

                    <h3>
                        <?= $servicio['nombre']; ?>
                    </h3>

                    <p>
                        $<?= number_format($servicio['precio'], 0, ',', '.'); ?>
                    </p>

                    <span>
                        <?= $servicio['duracion']; ?>
                        <?= $servicio['unidad_tiempo']; ?>
                    </span>
                    <button
                        class="btn btn-dark btn-sm mt-3 btn-editar"

                        data-id="<?= $servicio['id_servicio']; ?>"
                        data-nombre="<?= $servicio['nombre']; ?>"
                        data-precio="<?= $servicio['precio']; ?>"
                        data-duracion="<?= $servicio['duracion']; ?>"
                        data-unidad="<?= $servicio['unidad_tiempo']; ?>"

                        data-bs-toggle="modal"
                        data-bs-target="#modalEditarServicio">

                        <i class="fa-solid fa-pen"></i>
                        Editar

                    </button>
                    <form
                        action="actions/toggle_servicio.php"
                        method="POST"
                        class="mt-2">

                        <input
                            type="hidden"
                            name="id_servicio"
                            value="<?= $servicio['id_servicio']; ?>">

                        <input
                            type="hidden"
                            name="estado_actual"
                            value="<?= $servicio['activo']; ?>">

                        <?php if($servicio['activo'] == 1): ?>

                            <button
                                type="submit"
                                class="btn btn-danger btn-sm">

                                <i class="fa-solid fa-ban"></i>
                                Deshabilitar

                            </button>

                        <?php else: ?>

                            <button
                                type="submit"
                                class="btn btn-success btn-sm">

                                <i class="fa-solid fa-check"></i>
                                Habilitar

                            </button>

                        <?php endif; ?>

                    </form>
                </div>

                <?php endwhile; ?>

            </div>

        </section>

    </main>

</div>
<div class="modal fade" id="modalServicio" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="actions/crear_servicios.php" method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Agregar servicio
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Precio
                        </label>

                        <input
                            type="number"
                            name="precio"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Duración
                        </label>

                        <div class="duracion-container">

                            <input
                                type="number"
                                name="duracion"
                                class="form-control"
                                required>

                            <select
                                name="unidad"
                                class="form-select">

                                <option value="minutos">
                                    Minutos
                                </option>

                                <option value="horas">
                                    Horas
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button
                        type="submit"
                        class="btn btn-dark">

                        Guardar servicio

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
<div class="modal fade" id="modalEditarServicio" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="actions/editar_servicio.php" method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Editar servicio
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="id_servicio"
                        id="editar-id">

                    <div class="mb-3">

                        <label class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            id="editar-nombre"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Precio
                        </label>

                        <input
                            type="number"
                            name="precio"
                            id="editar-precio"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Duración
                        </label>

                        <div class="duracion-container">

                            <input
                                type="number"
                                name="duracion"
                                id="editar-duracion"
                                class="form-control"
                                required>

                            <select
                                name="unidad"
                                id="editar-unidad"
                                class="form-select">

                                <option value="minutos">
                                    Minutos
                                </option>

                                <option value="horas">
                                    Horas
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button
                        type="submit"
                        class="btn btn-dark">

                        Guardar cambios

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>

const botonesEditar = document.querySelectorAll(".btn-editar");

botonesEditar.forEach(boton => {

    boton.addEventListener("click", () => {

        document.getElementById("editar-id").value =
            boton.dataset.id;

        document.getElementById("editar-nombre").value =
            boton.dataset.nombre;

        document.getElementById("editar-precio").value =
            boton.dataset.precio;

        document.getElementById("editar-duracion").value =
            boton.dataset.duracion;

        document.getElementById("editar-unidad").value =
            boton.dataset.unidad;

    });

});

</script>
</body>

</html>