<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$sql = "SELECT * FROM profesionales";

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
                Gestión de Profesionales
            </h1>

            <div class="mb-4">

                <button
                    class="btn btn-dark"
                    data-bs-toggle="modal"
                    data-bs-target="#modalProfesional">

                    <i class="fa-solid fa-plus"></i>
                    Nuevo profesional

                </button>

            </div>

            <div class="cards-container">

                <?php while($profesional = mysqli_fetch_assoc($resultado)) : ?>

                    <div class="admin-card">

                        <i class="fa-solid fa-scissors"></i>

                        <h3>
                            <?= $profesional['nombre']; ?>
                        </h3>

                        <p>
                            <?= $profesional['especialidad']; ?>
                        </p>

                        <?php if($profesional['activo'] == 1): ?>

                            <div class="estado activo">
                                Activo
                            </div>

                        <?php else: ?>

                            <div class="estado inactivo">
                                Deshabilitado
                            </div>

                        <?php endif; ?>

                        <!-- BOTON EDITAR -->

                        <button
                            class="btn btn-dark btn-sm mt-3 btn-editar"

                            data-id="<?= $profesional['id_profesional']; ?>"
                            data-nombre="<?= $profesional['nombre']; ?>"
                            data-especialidad="<?= $profesional['especialidad']; ?>"

                            data-bs-toggle="modal"
                            data-bs-target="#modalEditarProfesional">

                            <i class="fa-solid fa-pen"></i>
                            Editar

                        </button>

                        <!-- BOTON HORARIOS -->

                        <button
                            class="btn btn-primary btn-sm mt-2 btn-horarios"

                            data-id="<?= $profesional['id_profesional']; ?>"
                            data-nombre="<?= $profesional['nombre']; ?>"

                            data-bs-toggle="modal"
                            data-bs-target="#modalHorarios">

                            <i class="fa-solid fa-clock"></i>
                            Horarios

                        </button>

                        <!-- BOTON ACTIVAR / DESACTIVAR -->

                        <form
                            action="actions/toggle_profesional.php"
                            method="POST"
                            class="mt-2">

                            <input
                                type="hidden"
                                name="id_profesional"
                                value="<?= $profesional['id_profesional']; ?>">

                            <input
                                type="hidden"
                                name="estado_actual"
                                value="<?= $profesional['activo']; ?>">

                            <?php if($profesional['activo'] == 1): ?>

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

<!-- MODAL NUEVO PROFESIONAL -->

<div class="modal fade" id="modalProfesional" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="actions/crear_profesional.php" method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Nuevo profesional
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
                            Especialidades
                        </label>

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                value="Barbero"
                                name="especialidades[]"
                                id="barbero">

                            <label
                                class="form-check-label"
                                for="barbero">

                                Barbero

                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                value="Colorista"
                                name="especialidades[]"
                                id="colorista">

                            <label
                                class="form-check-label"
                                for="colorista">

                                Colorista

                            </label>

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

                        Guardar profesional

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- MODAL HORARIOS -->

<div class="modal fade" id="modalHorarios" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="actions/guardar_horario.php" method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Configurar horarios
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
                        name="id_profesional"
                        id="horario-id-profesional">

                    <div class="mb-3">

                        <label class="form-label">
                            Profesional
                        </label>

                        <input
                            type="text"
                            id="horario-nombre"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="mb-3">

                    <label class="form-label">
                        Día franco
                    </label>

                        <select
                            name="dia_franco"
                            class="form-select"
                            required>

                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Hora inicio
                        </label>

                        <input
                            type="time"
                            name="hora_inicio"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Hora fin
                        </label>

                        <input
                            type="time"
                            name="hora_fin"
                            class="form-control"
                            required>

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

                        Guardar horario

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>

const botonesHorarios = document.querySelectorAll(".btn-horarios");

botonesHorarios.forEach(boton => {

    boton.addEventListener("click", () => {

        document.getElementById(
            "horario-id-profesional"
        ).value = boton.dataset.id;

        document.getElementById(
            "horario-nombre"
        ).value = boton.dataset.nombre;

    });

});

</script>

</body>

</html>