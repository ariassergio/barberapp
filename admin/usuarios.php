<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

// 1. Obtener los usuarios junto con el nombre del barbero asignado (si lo tiene) y su rol
$sql_usuarios = "SELECT u.*, p.nombre AS nombre_profesional 
                 FROM usuarios u 
                 LEFT JOIN profesionales p ON u.id_profesional = p.id_profesional";
$resultado_usuarios = mysqli_query($conn, $sql_usuarios);

// 2. Obtener los profesionales que NO tienen un usuario creado para poder vincularlos
$sql_libres = "SELECT id_profesional, nombre FROM profesionales 
               WHERE id_profesional NOT IN (SELECT id_profesional FROM usuarios WHERE id_profesional IS NOT NULL)";
$resultado_libres = mysqli_query($conn, $sql_libres);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios del Sistema</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/usuarios.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="admin-container">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">

        <?php include 'includes/navbar.php'; ?>

        <section class="dashboard">

            <div class="usuarios-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h1 class="dashboard-title mb-1">Usuarios del Sistema</h1>
                    <p class=" text-blanco mb-0">Gestioná los accesos de administradores y las cuentas del equipo de barberos</p>
                </div>
                <!-- Alertas de Feedback (Éxito o Error) -->
                <?php if (isset($_GET['success']) && $_GET['success'] === 'creado'): ?>
                    <div class="alert alert-success bg-success text-white border-0 rounded-3 mb-4 p-3 d-flex align-items-center gap-2" role="alert">
                        <i class="fa-solid fa-circle-check fs-5"></i>
                        <span>¡Cuenta de acceso creada y configurada con éxito!</span>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger bg-danger text-white border-0 rounded-3 mb-4 p-3 d-flex align-items-center gap-2" role="alert">
                        <i class="fa-solid fa-circle-exclamation fs-5"></i>
                        <span>
                            <?php 
                                if($_GET['error'] === 'existente') echo "El nombre de usuario o el correo electrónico ya están registrados.";
                                elseif($_GET['error'] === 'db') echo "Hubo un error interno en la base de datos. Intentá nuevamente.";
                                else echo "Ocurrió un error inesperado al procesar la solicitud.";
                            ?>
                        </span>
                    </div>
                <?php endif; ?>
                <button class="new-usuario-btn" data-bs-toggle="modal" data-bs-target="#modalUsuario">
                    <i class="fa-solid fa-user-plus me-2"></i> Nuevo Usuario
                </button>
            </div>

            <div class="table-container-premium">
                <table class="table custom-premium-table align-middle m-0">
                    <thead>
                        <tr>
                            <th>Nombre de Usuario</th>
                            <th>Email</th>
                            <th>Rol / Permisos</th>
                            <th>Perfil Profesional Vinc.</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = mysqli_fetch_assoc($resultado_usuarios)) :
    if ($user['rol'] === 'maintainer') continue;
?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-table-avatar">
                                        <i class="fa-solid fa-circle-user"></i>
                                    </div>
                                    <span class="fw-bold text-white"><?= htmlspecialchars($user['usuario']); ?></span>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($user['email'] ?? 'No asignado'); ?></td>
                            <td>
                                <?php if($user['rol'] == 'admin' || $user['rol'] == 'Administrador'): ?>
                                    <span class="badge-rol admin"><i class="fa-solid fa-shield-halved me-1"></i> Admin</span>
                                <?php else: ?>
                                    <span class="badge-rol barbero"><i class="fa-solid fa-scissors me-1"></i> Barbero</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class=" text-blanco">
                                    <i class="fa-solid fa-link me-1"></i> 
                                    <?= $user['nombre_profesional'] ? htmlspecialchars($user['nombre_profesional']) : 'Ninguno (Solo Admin)'; ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn-action-table btn-edit-user"
                                        data-id="<?= $user['id_usuario']; ?>"
                                        data-usuario="<?= htmlspecialchars($user['usuario']); ?>"
                                        data-email="<?= htmlspecialchars($user['email'] ?? ''); ?>"
                                        data-rol="<?= $user['rol']; ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarUsuario">
                                    <i class="fa-solid fa-marker"></i> Modificar
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </section>

    </main>

</div>

<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal">
            <form action="actions/crear_usuario.php" method="POST" class="m-0">

                <div class="modal-header border-0">
                    <div class="modal-header-content">
                        <div class="modal-icon create-icon">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div>
                            <h4 class="modal-title mb-1">Nueva Cuenta</h4>
                            <p class="modal-subtitle mb-0">Asigná credenciales de acceso para tu personal</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de Usuario (Login)</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-signature"></i></span>
                            <input type="text" name="usuario" class="form-control" placeholder="Ej: lucas_barber" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="nombre@barberia.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña Provisoria</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Contraseña de inicio inicial" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rol de Sistema</label>
                        <select name="rol" class="form-select modern-select w-100" id="select-rol-crear" required>
                            <option value="barbero">Barbero (Acceso limitado a su agenda)</option>
                            <option value="admin">Administrador (Control total del sistema)</option>
                        </select>
                    </div>

                    <div class="mb-3" id="contenedor-vinculo-profesional">
                        <label class="form-label">Vincular con Agenda de Barbero</label>
                        <select name="id_profesional" class="form-select modern-select w-100">
                            <option value="">-- Seleccionar Profesional Libre --</option>
                            <?php 
                            mysqli_data_seek($resultado_libres, 0);
                            while($prof = mysqli_fetch_assoc($resultado_libres)): 
                            ?>
                                <option value="<?= $prof['id_profesional']; ?>"><?= htmlspecialchars($prof['nombre']); ?></option>
                            <?php endwhile; ?>
                        </select>
                        <small class=" text-blanco mt-1 d-block">Asocia este usuario con su respectiva agenda de turnos.</small>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-save">Generar Acceso</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content premium-modal">
            <form action="actions/editar_usuario.php" method="POST" class="m-0">

                <div class="modal-header border-0">
                    <div class="modal-header-content">
                        <div class="modal-icon edit-icon">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        <div>
                            <h4 class="modal-title mb-1">Modificar Acceso</h4>
                            <p class="modal-subtitle mb-0">Actualizá los datos de perfil o roles de la cuenta</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id_usuario" id="editar-user-id">

                    <div class="mb-3">
                        <label class="form-label">Nombre de Usuario</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-signature"></i></span>
                            <input type="text" name="usuario" id="editar-user-username" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" id="editar-user-email" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nueva Contraseña (Dejar vacío para no cambiar)</label>
                        <div class="input-group custom-input">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Escribir solo si se desea resetear">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-save">Guardar Cambios</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Ocultar dinámicamente la vinculación de profesional si se elige rol Admin al crear
const selectRolCrear = document.getElementById('select-rol-crear');
const contenedorVinculo = document.getElementById('contenedor-vinculo-profesional');

selectRolCrear.addEventListener('change', () => {
    if(selectRolCrear.value === 'admin') {
        contenedorVinculo.style.display = 'none';
    } else {
        contenedorVinculo.style.display = 'block';
    }
});

// Captura de datos para Modificar Usuario
const botonesEditarUser = document.querySelectorAll(".btn-edit-user");
botonesEditarUser.forEach(boton => {
    boton.addEventListener("click", () => {
        document.getElementById("editar-user-id").value = boton.dataset.id;
        document.getElementById("editar-user-username").value = boton.dataset.usuario;
        document.getElementById("editar-user-email").value = boton.dataset.email;
    });
});
</script>
</body>
</html>