<?php
session_start();
// If already logged in as maintainer, redirect to maintainer config page
if (isset($_SESSION['admin_id']) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'maintainer') {
    header('Location: configuracion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Mantenimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {background:#121212;color:#fff;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;font-family:'Inter',sans-serif;}
        .login-card{background:#1e1e1e;border-radius:15px;padding:40px;width:100%;max-width:400px;box-shadow:0 10px 30px rgba(0,0,0,0.5);border:1px solid #333;animation:fadeIn .5s ease-in-out;}
        @keyframes fadeIn{from{opacity:0;transform:translateY(-20px)}to{opacity:1;transform:translateY(0)}}
        .login-card h2{text-align:center;margin-bottom:30px;font-weight:700;color:#f1f1f1;}
        .form-control{background:#2c2c2c;border:1px solid #444;color:#fff;padding:12px 15px;}
        .form-control::placeholder{color:#aaa;}
        .form-control:focus{background:#333;border-color:#6c757d;color:#fff;box-shadow:0 0 0 .25rem rgba(255,255,255,.1);}
        .btn-login{background:linear-gradient(135deg,#0d6efd,#0b5ed7);border:none;padding:12px;font-size:16px;font-weight:600;width:100%;border-radius:8px;transition:.3s ease;}
        .btn-login:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(13,110,253,.4);background:linear-gradient(135deg,#0b5ed7,#0a53be);}
        .input-group-text{background:#2c2c2c;border:1px solid #444;color:#aaa;}
        .error-msg{background:rgba(220,53,69,.1);color:#ea868f;padding:10px;border-radius:8px;border:1px solid rgba(220,53,69,.3);margin-bottom:20px;text-align:center;font-size:14px;}
    </style>
</head>
<body>
    <div class="login-card">
        <h2><i class="fa-solid fa-wrench me-2"></i>Mantenimiento</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg">
                <?php
                if ($_GET['error'] === 'empty') echo "Por favor, complete todos los campos.";
                elseif ($_GET['error'] === 'invalid') echo "Usuario o contraseña incorrectos.";
                ?>
            </div>
        <?php endif; ?>
        <form action="actions/maintainer_login_action.php" method="POST">
            <div class="mb-4">
                <label for="usuario" class="form-label text-light">Usuario</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su usuario" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-light">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-login">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
