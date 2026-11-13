<?php
// Se incluye al principio de cada página: inicia la sesión y carga las clases.

session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/ProductoController.php';
require_once __DIR__ . '/../controllers/CarritoController.php';

// Escapa texto antes de mostrarlo en HTML (evita inyección de código).
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

// Guarda un mensaje en sesión para mostrarlo en la siguiente página.
function mensaje(string $texto): void
{
    $_SESSION['mensaje'] = $texto;
}

// Redirige a otra página y detiene el script.
function redirigir(string $pagina): void
{
    header('Location: ' . $pagina);
    exit;
}
