<?php
// require_once 'includes/auth.php';
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
                Gestión de Barberos
            </h1>

            <div class="cards-container">

                <div class="admin-card">
                    <i class="fa-solid fa-scissors"></i>
                    <h3>Lucas</h3>
                    <p>Especialista en Fade</p>
                </div>

                <div class="admin-card">
                    <i class="fa-solid fa-scissors"></i>
                    <h3>Franco</h3>
                    <p>Barba y Perfilado</p>
                </div>

                <div class="admin-card">
                    <i class="fa-solid fa-scissors"></i>
                    <h3>Matías</h3>
                    <p>Cortes clásicos</p>
                </div>

            </div>

        </section>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>