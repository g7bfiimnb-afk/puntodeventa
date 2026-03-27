<?php 
session_start(); 
// Si no hay sesión, mandamos a login. Si hay, al dashboard
$vista = (isset($_SESSION['usuario_id'])) ? "dashboard" : "login";

if($vista == "dashboard"){
    $p = (isset($_GET['p'])) ? $_GET['p'] : "inicio";
    // AGREGAMOS 'proveedores' y 'comprador' a la lista para que PHP permita cargar la vista
    $paginas_permitidas = ['inicio', 'productos', 'ventas', 'compras', 'reportes', 'proveedores', 'comprador'];
    if(!in_array($p, $paginas_permitidas)) $p = "inicio";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>POS Abarrotes - El Güero</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="./vistas/css/main.css">
</head>
<body>

<?php if($vista == "login"): ?>
    <div class="login-container">
        <div class="card shadow-lg card-login">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-users fa-4x text-primary"></i>
                    <h4 class="mt-3">Abarrotes "El guero"</h4>
                    <p class="text-muted">Inicia sesión</p>
                </div>
                <form id="form_login_ajax">
                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" name="usuario_login" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" name="password_login" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                </form>
            </div>
        </div>
    </div>

<?php else: ?>
    <?php $esComprador = isset($_SESSION['usuario_rol']) && strtolower($_SESSION['usuario_rol']) == 'comprador'; ?>
    <div id="wrapper" class="<?php echo $esComprador ? 'toggled' : ''; ?>">
        
        <?php if(!$esComprador): ?>
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">SISTEMA POS</div>
            <div class="list-group list-group-flush">
                <a href="index.php?p=inicio" class="list-group-item list-group-item-action">Inicio</a>
                <a href="index.php?p=productos" class="list-group-item list-group-item-action">Productos</a>
                <a href="index.php?p=ventas" class="list-group-item list-group-item-action">Ventas</a>
                <a href="index.php?p=proveedores" class="list-group-item list-group-item-action">Proveedores</a>
                <a href="index.php?p=reportes" class="list-group-item list-group-item-action">Reportes</a>
                <a href="ajax/Logout.php" class="list-group-item list-group-item-action text-danger">Salir</a>
            </div>
        </div>
        <?php endif; ?>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container-fluid">
                    <span class="navbar-brand">Abarrotes / <small><?php echo ucfirst($p); ?></small></span>
                    <div class="ml-auto">
                        <span class="badge badge-info p-2"><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?> (<?php echo ucfirst($esComprador ? 'Comprador' : 'Administrador'); ?>)</span>
                    </div>
                </div>
            </nav>

            <div class="<?php echo ($p == 'comprador') ? '' : 'container-fluid p-4'; ?>">
                <?php 
                    $archivo = "./vistas/pantallas/" . $p . ".php";
                    if(file_exists($archivo)) include $archivo;
                    else echo "<div class='alert alert-warning'>La pantalla <b>$p</b> no existe.</div>";
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
    <script src="./vistas/js/productos.js"></script>
    <script src="./vistas/js/proveedores.js"></script>
    <script src="./vistas/js/ventas.js"></script>
    <script src="./vistas/js/comprador.js"></script>
    <script src="./vistas/js/carrito_logica.js"></script>
<?php endif; ?>
</body>
</html>