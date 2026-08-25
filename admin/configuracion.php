<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

$sql_config = "SELECT * FROM configuracion_sistema LIMIT 1";
$res_config = mysqli_query($conn, $sql_config);
$config = mysqli_fetch_assoc($res_config);

if (!$config) {
    $config = [
        'nombre_barberia' => '', 'telefono' => '', 'instagram' => '', 'facebook' => '',
        'intervalo_turnos' => 30, 'hora_apertura' => '09:00', 'hora_cierre' => '20:00',
        'titulo_pagina' => '', 'slogan' => '', 'quienes_somos' => '',
        'banner1' => '', 'banner2' => '', 'banner3' => '', 'logo_url' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Sistema</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .config-card {
            background: #111827;
            border: 1px solid #1e293b;
            border-radius: 18px;
            padding: 28px;
            margin-bottom: 24px;
        }
        .config-card h4 {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #1e293b;
        }
        .config-tabs .nav-link {
            color: #94a3b8;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: 0.2s;
        }
        .config-tabs .nav-link.active {
            background: #1e293b;
            color: white;
        }
        .config-tabs .nav-link:hover:not(.active) {
            background: #1e293b55;
            color: #cbd5e1;
        }
        .custom-input .input-group-text {
            background: #1e293b;
            border: 1px solid #334155;
            color: #94a3b8;
        }
        .custom-input input,
        .custom-input select,
        .custom-input textarea {
            background: #1e293b !important;
            color: #f1f5f9 !important;
            border: 1px solid #334155 !important;
        }
        .custom-input input::placeholder,
        .custom-input textarea::placeholder {
            color: #64748b;
        }
        .custom-input input:focus,
        .custom-input select:focus,
        .custom-input textarea:focus {
            background: #273549 !important;
            color: white !important;
            box-shadow: 0 0 0 2px rgba(96,165,250,0.2);
            border-color: #60a5fa !important;
        }
        .banner-preview {
            width: 100%;
            height: 130px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #334155;
        }
        .banner-placeholder {
            width: 100%;
            height: 130px;
            background: #1e293b;
            border-radius: 10px;
            border: 2px dashed #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: 0.85rem;
            flex-direction: column;
            gap: 6px;
        }
        .upload-label {
            cursor: pointer;
            display: block;
            width: 100%;
        }
        .upload-label input[type=file] {
            display: none;
        }
        .logo-preview-wrap {
            width: 120px;
            height: 120px;
            background: #1e293b;
            border: 2px dashed #334155;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .logo-preview-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .form-label { color: #94a3b8; font-size: 0.88rem; margin-bottom: 6px; }
        .alert-config {
            border-radius: 12px;
            font-size: 0.9rem;
        }
        .btn-guardar {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 12px;
            padding: 12px 32px;
            font-weight: 600;
            color: white;
            transition: 0.2s;
        }
        .btn-guardar:hover { transform: translateY(-2px); filter: brightness(1.1); color: white; }
    </style>
</head>
<body>

<div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <?php include 'includes/navbar.php'; ?>

        <section class="dashboard">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h1 class="dashboard-title mb-1">Configuración del Sistema</h1>
                    <p class="text-white-50 mb-0">Personalizá completamente la identidad y contenido del sitio.</p>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-config bg-success bg-opacity-20 border border-success text-white mb-4 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check text-success fs-5"></i>
                    Configuración guardada correctamente.
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-config bg-danger bg-opacity-20 border border-danger text-white mb-4 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-xmark text-danger fs-5"></i>
                    Ocurrió un error al guardar. Intentá nuevamente.
                </div>
            <?php endif; ?>

            <!-- TABS NAVIGATION -->
            <ul class="nav config-tabs mb-4" id="configTabs">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-contacto">
                        <i class="fa-solid fa-store me-2"></i> Datos del Local
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-horarios">
                        <i class="fa-regular fa-clock me-2"></i> Horarios
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-disenio">
                        <i class="fa-solid fa-palette me-2"></i> Diseño Web
                    </button>
                </li>
            </ul>

            <!-- FORM (único para todo) -->
            <form action="actions/guardar_configuracion.php" method="POST" enctype="multipart/form-data" id="form-config">
                <div class="tab-content">

                    <!-- TAB 1: DATOS DEL LOCAL -->
                    <div class="tab-pane fade show active" id="tab-contacto">
                        <div class="config-card">
                            <h4><i class="fa-solid fa-id-card me-2"></i> Identidad del Negocio</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre de la Barbería</label>
                                    <div class="input-group custom-input">
                                        <span class="input-group-text"><i class="fa-solid fa-shop"></i></span>
                                        <input type="text" name="nombre_barberia" class="form-control" value="<?= htmlspecialchars($config['nombre_barberia']) ?>" placeholder="Ej: Barber Kings" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Teléfono / WhatsApp</label>
                                    <div class="input-group custom-input">
                                        <span class="input-group-text"><i class="fa-brands fa-whatsapp"></i></span>
                                        <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($config['telefono']) ?>" placeholder="Ej: +54 11 1234-5678">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Instagram (URL completo)</label>
                                    <div class="input-group custom-input">
                                        <span class="input-group-text"><i class="fa-brands fa-instagram"></i></span>
                                        <input type="text" name="instagram" class="form-control" value="<?= htmlspecialchars($config['instagram']) ?>" placeholder="https://instagram.com/tupagina">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Facebook (URL completo)</label>
                                    <div class="input-group custom-input">
                                        <span class="input-group-text"><i class="fa-brands fa-facebook"></i></span>
                                        <input type="text" name="facebook" class="form-control" value="<?= htmlspecialchars($config['facebook']) ?>" placeholder="https://facebook.com/tupagina">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: HORARIOS -->
                    <div class="tab-pane fade" id="tab-horarios">
                        <div class="config-card">
                            <h4><i class="fa-solid fa-business-time me-2"></i> Horarios Generales del Local</h4>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Hora de Apertura</label>
                                    <div class="input-group custom-input">
                                        <span class="input-group-text"><i class="fa-solid fa-sun"></i></span>
                                        <input type="time" name="hora_apertura" class="form-control" value="<?= substr($config['hora_apertura'], 0, 5) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Hora de Cierre</label>
                                    <div class="input-group custom-input">
                                        <span class="input-group-text"><i class="fa-solid fa-moon"></i></span>
                                        <input type="time" name="hora_cierre" class="form-control" value="<?= substr($config['hora_cierre'], 0, 5) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Intervalo entre Turnos</label>
                                    <div class="input-group custom-input">
                                        <span class="input-group-text"><i class="fa-solid fa-stopwatch"></i></span>
                                        <select name="intervalo_turnos" class="form-select border-0">
                                            <option value="15" <?= $config['intervalo_turnos'] == 15 ? 'selected' : '' ?>>15 Minutos</option>
                                            <option value="30" <?= $config['intervalo_turnos'] == 30 ? 'selected' : '' ?>>30 Minutos</option>
                                            <option value="45" <?= $config['intervalo_turnos'] == 45 ? 'selected' : '' ?>>45 Minutos</option>
                                            <option value="60" <?= $config['intervalo_turnos'] == 60 ? 'selected' : '' ?>>60 Minutos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: DISEÑO WEB -->
                    <div class="tab-pane fade" id="tab-disenio">

                        <!-- Título + Slogan -->
                        <div class="config-card">
                            <h4><i class="fa-solid fa-heading me-2"></i> Título y Slogan</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Título de la Página (pestaña del navegador)</label>
                                    <div class="input-group custom-input">
                                        <span class="input-group-text"><i class="fa-solid fa-globe"></i></span>
                                        <input type="text" name="titulo_pagina" class="form-control" value="<?= htmlspecialchars($config['titulo_pagina']) ?>" placeholder="Ej: Reservar Turno | Barber Kings">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Slogan / Frase Destacada</label>
                                    <div class="input-group custom-input">
                                        <span class="input-group-text"><i class="fa-solid fa-quote-right"></i></span>
                                        <input type="text" name="slogan" class="form-control" value="<?= htmlspecialchars($config['slogan']) ?>" placeholder='Ej: "No es solo un corte, es tu identidad."'>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quiénes Somos -->
                        <div class="config-card">
                            <h4><i class="fa-solid fa-users me-2"></i> Sección "Quiénes Somos"</h4>
                            <div class="row g-3 align-items-start">
                                <div class="col-md-8">
                                    <label class="form-label">Texto descriptivo del local</label>
                                    <div class="custom-input">
                                        <textarea name="quienes_somos" class="form-control" rows="5" placeholder="Contá la historia de tu barbería, qué la hace especial, tu equipo..."><?= htmlspecialchars($config['quienes_somos']) ?></textarea>
                                    </div>
                                    <small class="text-white-50 mt-1 d-block">Este texto aparece en la sección central de la página principal.</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Logo / Foto de la sección</label>
                                    <label class="upload-label">
                                        <input type="file" name="logo_url" accept="image/*" onchange="previewImg(this, 'preview-logo')">
                                        <div class="logo-preview-wrap mx-auto">
                                            <?php if (!empty($config['logo_url'])): ?>
                                                <img id="preview-logo" src="../<?= htmlspecialchars($config['logo_url']) ?>" alt="Logo">
                                            <?php else: ?>
                                                <div class="text-center text-white-50 p-3" id="preview-logo-placeholder">
                                                    <i class="fa-solid fa-cloud-arrow-up fs-3 mb-2 d-block"></i>
                                                    <small>Subir logo</small>
                                                </div>
                                                <img id="preview-logo" src="" style="display:none; width:100%; height:100%; object-fit:contain;" alt="Logo">
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-center text-white-50 small mt-2 mb-0">Clic para cambiar</p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Banners del Carrusel -->
                        <div class="config-card">
                            <h4><i class="fa-solid fa-images me-2"></i> Banners del Carrusel Principal</h4>
                            <p class="text-white-50 small mb-3">Subí hasta 3 imágenes horizontales (se recomienda 1920x600 px). Si no subís una nueva imagen, se mantiene la actual.</p>
                            <div class="row g-3">
                                <?php
                                $bannerFields = ['banner1', 'banner2', 'banner3'];
                                $bannerLabels = ['Banner Principal', 'Banner 2', 'Banner 3'];
                                foreach ($bannerFields as $i => $field):
                                    $currentBanner = $config[$field] ?? '';
                                ?>
                                <div class="col-md-4">
                                    <label class="form-label"><?= $bannerLabels[$i] ?></label>
                                    <label class="upload-label">
                                        <input type="file" name="<?= $field ?>" accept="image/*" onchange="previewImg(this, 'preview-<?= $field ?>')">
                                        <?php if (!empty($currentBanner)): ?>
                                            <img id="preview-<?= $field ?>" src="../<?= htmlspecialchars($currentBanner) ?>" class="banner-preview" alt="Banner">
                                        <?php else: ?>
                                            <div class="banner-placeholder" id="preview-<?= $field ?>-placeholder">
                                                <i class="fa-solid fa-cloud-arrow-up fs-4"></i>
                                                <span>Clic para subir imagen</span>
                                            </div>
                                            <img id="preview-<?= $field ?>" src="" class="banner-preview" style="display:none;" alt="Banner">
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div><!-- end tab-pane diseño -->
                </div><!-- end tab-content -->

                <!-- BOTÓN GUARDAR -->
                <div class="text-end mt-2 mb-4">
                    <button type="button" id="btn-guardar-config" class="btn-guardar">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Guardar Configuración
                    </button>
                </div>

            </form>

        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Preview de imágenes antes de subir
function previewImg(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Ocultar placeholder si existe
            const placeholder = document.getElementById(previewId + '-placeholder');
            if (placeholder) placeholder.style.display = 'none';

            const img = document.getElementById(previewId);
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Confirmación antes de guardar
document.getElementById('btn-guardar-config').addEventListener('click', function() {
    Swal.fire({
        title: '¿Guardar Configuración?',
        text: 'Los cambios se reflejarán de inmediato en el sitio público.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#475569',
        confirmButtonText: '<i class="fa-solid fa-floppy-disk me-1"></i> Guardar',
        cancelButtonText: 'Cancelar',
        background: '#111827',
        color: '#f1f5f9',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-config').submit();
        }
    });
});
</script>
</body>
</html>
