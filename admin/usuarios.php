<?php
// require_once 'includes/auth.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>

    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/usuarios.css">

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
                Usuarios Registrados
            </h1>

            <div class="table-container">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Juan Pérez</td>
                            <td>juan@gmail.com</td>
                            <td>2914556677</td>
                        </tr>

                        <tr>
                            <td>Martín Gómez</td>
                            <td>martin@gmail.com</td>
                            <td>2914332211</td>
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