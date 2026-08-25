<?php

require_once 'admin/config/db.php';

$sql = "SELECT * FROM servicios
        WHERE activo = 1";

$resultadoServicios = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reservar Turno</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- 🔹 CSS SOLO DE ESTA PAGINA -->
<link rel="stylesheet" href="assets/css/reservar.css">
</head>

<body>

<!-- 🔹 NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    
    <a class="navbar-brand" href="#">💈 BarberApp</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" href="#">Reservar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Mis turnos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Login</a>
        </li>
      </ul>
    </div>

  </div>
</nav>
<!-- 🔹 BANNER / HERO -->
<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">

    <div class="carousel-inner">

        <div class="carousel-item active">
            <img src="assets/img/banner1 (1).png" class="d-block w-100 banner-img" alt="Banner 1">
        </div>

        <div class="carousel-item">
            <img src="assets/img/banner1 (2).png" class="d-block w-100 banner-img" alt="Banner 2">
        </div>

        <div class="carousel-item">
            <img src="assets/img/banner1 (3).png" class="d-block w-100 banner-img" alt="Banner 3">
        </div>

    </div>

    <!-- Flechas -->
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</div>
<section class="quienes-somos container my-5">

    <div class="row align-items-center">

        <!-- TEXTO -->
        <div class="col-md-6">
            <h2 class="titulo-seccion">Quiénes somos</h2>

            <p>
            Somos una barbería moderna enfocada en brindar una experiencia premium en cada visita. 
            Combinamos técnica, estilo y atención personalizada para lograr resultados que realmente marquen la diferencia.
            </p>

            <p>
            Nuestro equipo está formado por profesionales comprometidos con la excelencia, siempre actualizados con las últimas tendencias y herramientas del rubro.
            </p>

            <p class="frase-final">
            No se trata solo de un corte, se trata de cómo te sentís después.
            </p>
        </div>

        <!-- IMAGEN -->
        <div class="col-md-6 text-center">
            <img src="assets/img/logo.png" class="img-fluid img-quienes">
        </div>

    </div>
    

</section>


<section class="servicios container my-5">

    <h2 class="titulo-seccion text-center mb-5">Nuestros Servicios</h2>

    <div class="row g-4">

        <?php while($servicio = mysqli_fetch_assoc($resultadoServicios)) : ?>

        <div class="col-md-4">

            <div class="servicio-card">

                <img
                    src="assets/img/corte.png"
                    class="servicio-img">

                <div class="servicio-body">

                    <h5>
                        <?= $servicio['nombre']; ?>
                    </h5>

                    <p>
                        Duración:
                        <?= $servicio['duracion']; ?>
                        <?= $servicio['unidad_tiempo']; ?>
                    </p>

                    <div class="servicio-footer">

                        <span>
                            $<?= number_format(
                                $servicio['precio'],
                                0,
                                ',',
                                '.'
                            ); ?>
                        </span>

                        <a
                            href="#reserva"
                            class="btn btn-dark btn-sm">

                            Reservar

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <?php endwhile; ?>

    </div>

</section>


<section class="reseñas container my-5">

    <h2 class="titulo-seccion text-center mb-5">Lo que dicen nuestros clientes</h2>

    <div class="row g-4">

        <!-- RESEÑA 1 -->
        <div class="col-md-4">
            <div class="reseña-card">
            <p>⭐⭐⭐⭐⭐</p>
                <p>"Excelente atención y muy profesionales. Siempre salgo conforme."</p>
                <h6>- Juan Pérez</h6>
            </div>
        </div>

        <!-- RESEÑA 2 -->
        <div class="col-md-4">
            <div class="reseña-card">
            <p>⭐⭐⭐⭐⭐</p>
                <p>"El mejor lugar para cortarse el pelo. Rápido y de calidad."</p>
                <h6>- Martín Gómez</h6>

            </div>
        </div>

        <!-- RESEÑA 3 -->
        <div class="col-md-4">
            <div class="reseña-card">
            <p>⭐⭐⭐⭐⭐</p>
                <p>"Muy buena experiencia, el sistema de turnos es súper práctico."</p>
                <h6>- Lucas Fernández</h6>
            </div>
        </div>

    </div>

</section>


<section class="cta-reserva text-center">

    <div class="container">

        <h2>Listo para tu próximo corte?</h2>
        <p>Reservá tu turno en menos de un minuto</p>

        <a href="#reserva" class="btn btn-dark btn-lg">
            Reservar ahora
        </a>

    </div>

</section>

<section id="reserva" class="reserva container my-5">

    <div class="reserva-card">

        <!-- PROGRESO -->
        <div class="steps mb-4">
            <div class="step active">1</div>
            <div class="step">2</div>
            <div class="step">3</div>
            <div class="step">4</div>
        </div>

        <!-- PASOS -->
        <div class="step-content active" data-step="1">
            <h4>Elegí un servicio</h4>
            <div id="servicios" class="row g-3"></div>
        </div>

        <div class="step-content" data-step="2">
            <h4>Elegí peluquero</h4>
            <div id="peluqueros" class="d-flex flex-wrap gap-2"></div>
        </div>

        <div class="step-content" data-step="3">
            <h4>Seleccioná una fecha</h4>
            <input type="date" id="fecha" class="form-control">
        </div>

        <div class="step-content" data-step="4">
            <h4>Elegí horario</h4>
            <div id="horarios" class="d-flex flex-wrap gap-2"></div>
        </div>

        <!-- RESUMEN -->
        <div class="resumen mt-4" id="resumen"></div>

        <!-- BOTONES -->
        <div class="d-flex justify-content-between mt-4">
            <button id="prev" class="btn btn-outline-dark">Atrás</button>
            <button id="next" class="btn btn-dark">Siguiente</button>
        </div>

    </div>
    <div class="step-content" data-step="5">

        <h4>Tus datos</h4>

        <input 
            type="text"
            id="clienteNombre"
            class="form-control mb-3"
            placeholder="Tu nombre"
        >

        <input 
            type="tel"
            id="clienteTelefono"
            class="form-control"
            placeholder="Tu teléfono"
        >
    </div>
</section>

<div id="successScreen" class="success-screen">

    <div class="success-card">

        <!-- ✔ ICONO -->
        <div class="checkmark">
            ✓
        </div>

        <h2>¡Turno confirmado!</h2>

        <p class="success-text">
            Tu reserva fue registrada correctamente
        </p>

        <!-- RESUMEN -->
        <div class="success-resumen" id="successResumen"></div>

        <button class="btn btn-dark mt-3" onclick="location.reload()">
            Volver al inicio
        </button>

    </div>

</div>

<!-- 🔹 FOOTER -->
<footer class="text-center mb-3 text-muted">
    © <?= date("Y") ?> BarberApp
</footer>

<!-- 🔹 SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="assets/js/app.js"></script>

</body>
</html>