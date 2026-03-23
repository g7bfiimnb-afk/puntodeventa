<?php 
// 1. Iniciamos la sesión para rastrear si el usuario ya entró
session_start(); 

// 2. Lógica de acceso: Si no hay sesión, la vista es 'login', de lo contrario es 'dashboard'
$vista = (isset($_SESSION['usuario_id'])) ? "dashboard" : "login";

// 3. Lógica de Enrutamiento (Solo si ya inició sesión)
if($vista == "dashboard"){
    // Capturamos la página por la URL, por defecto es 'inicio'
    $p = (isset($_GET['p'])) ? $_GET['p'] : "inicio";
    
    // Lista de páginas permitidas para evitar accesos no deseados
    $paginas_permitidas = ['inicio', 'productos', 'ventas', 'compras', 'reportes'];
    
    if(!in_array($p, $paginas_permitidas)){
        $p = "inicio"; // Redirección por defecto
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Abarrotes - Mi Tiendita</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; font-family: 'Segoe UI', sans-serif; }
        
        /* Estilos Sidebar */
        #wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar-wrapper {
            min-height: 100vh;
            width: 260px;
            background-color: #343a40;
            transition: all 0.3s;
        }
        .sidebar-heading {
            padding: 20px;
            font-size: 1.2rem;
            color: white;
            text-align: center;
            background: #2c3136;
            border-bottom: 1px solid #4b545c;
        }
        .list-group-item {
            background: transparent;
            color: #c2c7d0;
            border: none;
            padding: 15px 25px;
        }
        .list-group-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .list-group-item.active {
            background-color: #007bff !important;
            color: white !important;
        }
        .list-group-item i { margin-right: 10px; width: 20px; text-align: center; }

        /* Contenido Principal */
        #page-content-wrapper { width: 100%; flex: 1; }
        
        /* Estilos Login */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #007bff 0%, #004a99 100%);
        }
    </style>
</head>
<body>

<?php if($vista == "login"): ?>
    
    <div class="login-container">
        <div class="card shadow-lg" style="width: 380px; border-radius: 15px;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-store-alt fa-4x text-primary"></i>
                    <h4 class="mt-3">Abarrotes "Mi Tiendita"</h4>
                    <p class="text-muted">Inicia sesión</p>
                </div>
                <form id="form_login_ajax">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Usuario</label>
                        <input type="text" name="usuario_login" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Contraseña</label>
                        <input type="password" name="password_login" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow">Entrar</button>
                </form>
            </div>
        </div>
    </div>

<?php else: ?>

    <div id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading"><strong>SISTEMA POS</strong></div>
            <div class="list-group list-group-flush">
                <a href="index.php?p=inicio" class="list-group-item list-group-item-action <?php echo ($p=='inicio')?'active':''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Inicio
                </a>
                <a href="index.php?p=productos" class="list-group-item list-group-item-action <?php echo ($p=='productos')?'active':''; ?>">
                    <i class="fas fa-boxes"></i> Productos
                </a>
                <a href="index.php?p=ventas" class="list-group-item list-group-item-action <?php echo ($p=='ventas')?'active':''; ?>">
                    <i class="fas fa-shopping-cart"></i> Ventas
                </a>
                <a href="index.php?p=compras" class="list-group-item list-group-item-action <?php echo ($p=='compras')?'active':''; ?>">
                    <i class="fas fa-truck-loading"></i> Compras
                </a>
                <a href="index.php?p=reportes" class="list-group-item list-group-item-action <?php echo ($p=='reportes')?'active':''; ?>">
                    <i class="fas fa-chart-bar"></i> Reportes
                </a>
                <hr style="border-top: 1px solid #4b545c; width: 80%;">
                <a href="ajax/Logout.php" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1 text-muted">
                        Abarrotes / <small class="text-primary"><?php echo ucfirst($p); ?></small>
                    </span>
                    <div class="ml-auto">
                        <span class="badge badge-info p-2">
                            <i class="fas fa-user-circle"></i> <?php echo $_SESSION['usuario_nombre']; ?>
                        </span>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                <?php 
                    // Incluimos la pantalla seleccionada dinámicamente
                    $archivo = "./vistas/pantallas/" . $p . ".php";
                    if(file_exists($archivo)){
                        include $archivo;
                    } else {
                        echo "<div class='alert alert-warning'>La pantalla <b>$p</b> no existe.</div>";
                    }
                ?>
            </div>
        </div>
    </div>

<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if($vista == "login"): ?>
    <script src="./vistas/js/login.js"></script>
<?php else: ?>
    <?php if($p == "ventas") echo '<script src="./vistas/js/ventas.js"></script>'; ?>
    <?php endif; ?>

<?php if($vista == "dashboard"): ?>
    <?php if($p == "ventas") echo '<script src="./vistas/js/ventas.js"></script>'; ?>
    <?php if($p == "productos") echo '<script src="./vistas/js/productos.js"></script>'; ?>
<?php else: ?>
    <script src="./vistas/js/login.js"></script>
<?php endif; ?>

</body>
</html>

</body>
</html>