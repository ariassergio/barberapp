<?php
// require_once 'includes/auth.php';
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

                        <tr>

                            <td>
                                <div class="cliente-info">

                                    <img
                                        src="https://i.pravatar.cc/45?img=12"
                                        class="cliente-avatar">

                                    <div>
                                        <h4>Juan Pérez</h4>
                                        <span>2914556677</span>
                                    </div>

                                </div>
                            </td>

                            <td>Corte Fade</td>

                            <td>Lucas</td>

                            <td>07/05/2026</td>

                            <td>18:00</td>

                            <td>
                                <span class="estado pendiente">
                                    Pendiente
                                </span>
                            </td>

                            <td>

                                <div class="reservas-actions">

                                    <button class="reserva-btn btn-view">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <button class="reserva-btn btn-edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    <button class="reserva-btn btn-delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>

                                </div>

                            </td>

                        </tr>

                        <tr>

                            <td>
                                <div class="cliente-info">

                                    <img
                                        src="https://i.pravatar.cc/45?img=15"
                                        class="cliente-avatar">

                                    <div>
                                        <h4>Martín Gómez</h4>
                                        <span>2914332211</span>
                                    </div>

                                </div>
                            </td>

                            <td>Barba</td>

                            <td>Franco</td>

                            <td>07/05/2026</td>

                            <td>19:00</td>

                            <td>
                                <span class="estado confirmado">
                                    Confirmado
                                </span>
                            </td>

                            <td>

                                <div class="reservas-actions">

                                    <button class="reserva-btn btn-view">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <button class="reserva-btn btn-edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    <button class="reserva-btn btn-delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>