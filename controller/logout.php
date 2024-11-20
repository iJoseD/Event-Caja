<?php

// Iniciar la sesión
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Eliminar la sesión actual
session_destroy();

// Redirigir
header("Location: /");

exit();