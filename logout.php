<?php
// Cierra la sesión y vuelve al login.
require_once 'includes/init.php';

AuthController::logout();
redirigir('login.php');
