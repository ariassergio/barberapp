<?php

require_once 'admin/config/db.php';

// Servicios activos
$sql = "SELECT * FROM servicios WHERE activo = 1";
$resultadoServicios = mysqli_query($conn, $sql);

// Configuración general
$sql_config = "SELECT * FROM configuracion_sistema LIMIT 1";
$res_config = mysqli_query($conn, $sql_config);
$config = mysqli_fetch_assoc($res_config);

if (!$config) {
    // Valores por defecto seguros si la tabla está vacía
    $config = [
        'nombre_barberia' => 'BarberApp',
        'telefono' => '+54 11 1234-5678',
        'instagram' => '#',
        'facebook' => '#',
        'hora_apertura' => '09:00:00',
        'hora_cierre' => '20:00:00'
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reservar Turno</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="assets/css/reservar.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    
    <a class="navbar-brand fw-bold" href="#">💈 <?= htmlspecialchars($config['nombre_barberia']) ?></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" href="#reserva">Reservar</a>
        </li>
      </ul>
    </div>

  </div>
</nav>

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

    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</div>

<section class="quienes-somos container my-5">

    <div class="row align-items-center">

        <div class="col-md-6">
            <h2 class="titulo-seccion mb-3">Quiénes somos</h2>

            <p class="text-secondary">
            Somos una barbería moderna enfocada en brindar una experiencia premium en cada visita. 
            Combinamos técnica, estilo y atención personalizada para lograr resultados que realmente marquen la diferencia.
            </p>

            <p class="text-secondary">
            Nuestro equipo está formado por profesionales comprometidos con la excellence, siempre actualizados con las últimas tendencias y herramientas del rubro.
            </p>

            <p class="frase-final fst-italic text-muted fw-bold border-start border-3 ps-3">
            "No se trata solo de un corte, se trata de cómo te sentís después."
            </p>
        </div>

        <div class="col-md-6 text-center">
            <img src="assets/img/logo.png" class="img-fluid img-quienes shadow-sm rounded" style="max-height: 280px;">
        </div>

    </div>
    
</section>

<section class="servicios container my-5">

    <h2 class="titulo-seccion text-center mb-5">Nuestros Servicios</h2>

    <div class="row g-4">

        <?php while($servicio = mysqli_fetch_assoc($resultadoServicios)) : ?>

        <div class="col-md-4">

            <div class="servicio-card h-100 border rounded shadow-sm p-3 bg-white d-flex flex-column justify-content-between">

                <div>
                    <img
                        src="assets/img/corte.png"
                        class="servicio-img img-fluid rounded mb-3 w-100" alt="<?= htmlspecialchars($servicio['nombre']); ?>">

                    <div class="servicio-body">
                        <h5 class="fw-bold text-dark">
                            <?= htmlspecialchars($servicio['nombre']); ?>
                        </h5>

                        <p class="text-muted small">
                            <i class="fa-regular fa-clock me-1"></i> Duración:
                            <?= $servicio['duracion']; ?>
                            <?= $servicio['unidad_tiempo']; ?>
                        </p>
                    </div>
                </div>

                <div class="servicio-footer d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                    <span class="fs-5 fw-bold text-dark">
                        $<?= number_format($servicio['precio'], 0, ',', '.'); ?>
                    </span>

                    <a
                        href="#reserva"
                        class="btn btn-dark btn-sm px-3">
                        Reservar
                    </a>
                </div>

            </div>

        </div>

        <?php endwhile; ?>

    </div>

</section>

<section class="reseñas container my-5">

    <h2 class="titulo-seccion text-center mb-5">Lo que dicen nuestros clientes</h2>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="reseña-card border p-4 rounded shadow-sm bg-light h-100 d-flex flex-column justify-content-between">
                <div>
                    <p class="text-warning mb-2">⭐⭐⭐⭐⭐</p>
                    <p class="fst-italic text-secondary">"Excelente atención y muy profesionales. Siempre salgo conforme."</p>
                </div>
                <h6 class="fw-bold text-end mb-0 text-dark">- Juan Pérez</h6>
            </div>
        </div>

        <div class="col-md-4">
            <div class="reseña-card border p-4 rounded shadow-sm bg-light h-100 d-flex flex-column justify-content-between">
                <div>
                    <p class="text-warning mb-2">⭐⭐⭐⭐⭐</p>
                    <p class="fst-italic text-secondary">"El mejor lugar para cortarse el pelo. Rápido y de calidad."</p>
                </div>
                <h6 class="fw-bold text-end mb-0 text-dark">- Martín Gómez</h6>
            </div>
        </div>

        <div class="col-md-4">
            <div class="reseña-card border p-4 rounded shadow-sm bg-light h-100 d-flex flex-column justify-content-between">
                <div>
                    <p class="text-warning mb-2">⭐⭐⭐⭐⭐</p>
                    <p class="fst-italic text-secondary">"Muy buena experiencia, el sistema de turnos es súper práctico."</p>
                </div>
                <h6 class="fw-bold text-end mb-0 text-dark">- Lucas Fernández</h6>
            </div>
        </div>

    </div>

</section>

<section class="cta-reserva text-center bg-dark text-white py-5 my-5">

    <div class="container py-3">

        <h2 class="fw-bold mb-2">¿Listo para tu próximo corte?</h2>
        <p class="text-muted lead mb-4">Reservá tu turno en menos de un minuto</p>

        <a href="#reserva" class="btn btn-light btn-lg fw-bold px-4">
            Reservar ahora
        </a>

    </div>

</section>

<section id="reserva" class="reserva container my-5">

    <div class="reserva-card border rounded p-4 shadow bg-white">

        <div class="steps mb-4">
            <div class="step active">1</div>
            <div class="step">2</div>
            <div class="step">3</div>
            <div class="step">4</div>
            <div class="step">5</div>
        </div>

        <div class="step-content active" data-step="1">
            <h4 class="fw-bold mb-3">Elegí un servicio</h4>
            <div id="servicios" class="row g-3"></div>
        </div>

        <div class="step-content" data-step="2">
            <h4 class="fw-bold mb-3">Elegí peluquero</h4>
            <div id="peluqueros" class="d-flex flex-wrap gap-2"></div>
        </div>

        <div class="step-content" data-step="3">
            <h4 class="fw-bold mb-3">Seleccioná una fecha</h4>
            <input type="date" id="fecha" class="form-control form-control-lg">
        </div>

        <div class="step-content" data-step="4">
            <h4 class="fw-bold mb-3">Elegí horario</h4>
            <div id="horarios" class="d-flex flex-wrap gap-2"></div>
        </div>

        <div class="step-content" data-step="5">
            <h4 class="fw-bold mb-3">Tus datos</h4>
            <input type="text" id="clienteNombre" class="form-control form-control-lg mb-3" placeholder="Tu nombre">
            <input type="tel" id="clienteTelefono" class="form-control form-control-lg" placeholder="Tu teléfono">
        </div>

        <div class="resumen mt-4 p-3 bg-light border rounded" id="resumen"></div>

        <div class="d-flex justify-content-between mt-4">
            <button id="prev" class="btn btn-outline-dark px-4">Atrás</button>
            <button id="next" class="btn btn-dark px-4">Siguiente</button>
        </div>

    </div>

</section>

<div id="successScreen" class="success-screen">

    <div class="success-card text-center p-5 border rounded shadow bg-white m-auto" style="max-width: 500px;">

        <div class="checkmark bg-success text-white rounded-circle d-flex align-items-center justify-content-center m-auto mb-4" style="width: 70px; height: 70px; font-size: 35px;">
            ✓
        </div>

        <h2 class="fw-bold">¡Turno confirmed!</h2>

        <p class="success-text text-muted">
            Tu reserva fue registrada correctamente
        </p>

        <div class="success-resumen my-4 p-3 bg-light border rounded" id="successResumen"></div>

        <button class="btn btn-dark w-100 py-2 fw-bold" onclick="location.reload()">
            Volver al inicio
        </button>

    </div>

</div>

<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold mb-3">💈 <?= htmlspecialchars($config['nombre_barberia']) ?></h5>
                <p class="text-white-50 small">La barbería definitiva para el hombre moderno. Combinando las mejores técnicas tradicionales y tendencias actuales.</p>
            </div>
            <div class="col-md-4">
                <h5 class="fw-bold mb-3">Horarios de Atención</h5>
                <ul class="list-unstyled text-white-50 small">
                    <li class="mb-2"><i class="fa-regular fa-clock me-2"></i>Lunes a Sábados: <?= substr($config['hora_apertura'], 0, 5) ?> - <?= substr($config['hora_cierre'], 0, 5) ?> hs</li>
                    <li><i class="fa-regular fa-clock me-2"></i>Domingos: Cerrado</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="fw-bold mb-3">Contacto</h5>
                <ul class="list-unstyled text-white-50 small mb-3">
                    <li class="mb-2"><i class="fa-solid fa-phone me-2"></i> <?= htmlspecialchars($config['telefono']) ?></li>
                    <li class="mb-2"><i class="fa-solid fa-location-dot me-2"></i> Av. Siempre Viva 742, BarberCity</li>
                </ul>
                <div class="d-flex gap-3 fs-5">
                    <?php if (!empty($config['instagram'])): ?>
                        <a href="<?= htmlspecialchars($config['instagram']) ?>" target="_blank" class="text-white-50 text-decoration-none"><i class="fa-brands fa-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($config['facebook'])): ?>
                        <a href="<?= htmlspecialchars($config['facebook']) ?>" target="_blank" class="text-white-50 text-decoration-none"><i class="fa-brands fa-facebook"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($config['telefono'])): ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $config['telefono']) ?>" target="_blank" class="text-white-50 text-decoration-none"><i class="fa-brands fa-whatsapp"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="text-center text-white-50 small">
            © <?= date("Y") ?> BarberApp. Todos los derechos reservados.
        </div>
    </div>
</footer>
<div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title fw-bold" id="modalConfirmacionLabel">💈 Confirma tu Reserva</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small mb-3">Por favor, verificá que los datos de tu turno sean correctos antes de finalizar:</p>
        
        <div id="resumenModal" class="p-3 bg-light border rounded">
            </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Modificar datos</button>
        <button type="button" id="btnConfirmarFinal" class="btn btn-success fw-bold px-4">¡Confirmar Turno!</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="assets/js/app.js"></script>

</body>
</html>