<?php
session_start();

// Procesar cierre de sesión (se sigue usando GET para no romper el
// flujo existente; se podría mejorar a POST en el futuro)
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    // también eliminamos la cookie de sesión para asegurarnos de que no
    // pueda reutilizarse accidentalmente en la siguiente petición.
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
    // regresar al punto de entrada principal
    header('Location: /ControlDeMaestros/');
    exit;
}

// Redirección automática si ya hay sesión activa
// Antes enviábamos a index.php pero eso generaba un bucle cuando
// el controlador reincorporaba este archivo. Ahora apuntamos
// directamente al dashboard correspondiente usando rutas absolutas.
if (!empty($_SESSION['maestro'])) {
    if (!empty($_SESSION['maestro']['admin'])) {
        header('Location: /ControlDeMaestros/vistas/pantallas/admin');
    } else {
        header('Location: /ControlDeMaestros/vistas/pantallas/maestro-dashboard');
    }
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/ControlDeMaestros/controladores/maestroControlador.php';

$errorLogin = '';

// Procesar inicio de sesión
if (isset($_POST['login'])) {
    // Sanitizamos ligeramente las entradas (el controlador se encargará
    // de normalizar la CURP). No convertimos aquí a mayúsculas para que
    // el administrador pueda iniciar sesión con la palabra "admin" sin
    // que se transforme en "ADMIN".
    $correo = trim($_POST['correo'] ?? '');
    $curp   = trim($_POST['curp'] ?? '');
    
    $user = MaestroControlador::ctrLogin($correo, $curp);
    
    if ($user) {
        $_SESSION['maestro'] = $user;
        // Redirigimos directamente al dashboard correspondiente para
        // evitar una segunda petición innecesaria.
        if (!empty($user['admin'])) {
            header('Location: /ControlDeMaestros/vistas/pantallas/admin');
        } else {
            header('Location: /ControlDeMaestros/vistas/pantallas/maestro-dashboard');
        }
        exit;
    } else {
        $errorLogin = 'Credenciales inválidas. Si no está registrado, contacte al administrador.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión - Sistema Escolar</title>
    <base href="/ControlDeMaestros/"> 

    <link rel="stylesheet" href="/ControlDeMaestros/vistas/css/inicio.css">
    <link rel="stylesheet" href="/ControlDeMaestros/vistas/css/maestros.css">
</head>
<body>

    <div class="layout">
        <div class="contenido">
            <h2>🎓 Inicio de Sesión</h2>
            
            <?php if ($errorLogin): ?>
                <p class="error"><?php echo htmlspecialchars($errorLogin, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <div class="forms-container">
                <div class="form-wrapper">
                    <form action="" method="POST" class="form-login">
                        <input type="email" name="correo" placeholder="Correo electrónico" required>
                        <input type="text" name="curp" placeholder="CURP" required maxlength="18" minlength="18" style="text-transform: uppercase;"> <!-- el estilo sigue forzando mayúsculas en pantalla -->
                        <button type="submit" name="login">Iniciar sesión</button>
                    </form>
                </div>

                <div class="register-prompt">
                    <p><strong>¿Es administrador?</strong> Ingrese sus credenciales de administrador.</p>
                    <p><strong>¿Es maestro?</strong> Ingrese su correo y CURP registrados.</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>