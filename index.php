<?php
// Punto de entrada: envía a cada usuario a su página de inicio.
require_once 'includes/init.php';

redirigir(AuthController::usuario() === null ? 'login.php' : AuthController::paginaInicio());
