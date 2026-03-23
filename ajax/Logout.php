<?php
session_start();
session_unset(); // Limpia las variables de sesión
session_destroy(); // Destruye la sesión

// Redirigimos al index (que ahora mostrará el login por falta de sesión)
header("Location: ../index.php");
exit();