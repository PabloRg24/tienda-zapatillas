<?php
// Cabecera común: menú según el rol y mensajes guardados en sesión.
$usuario = AuthController::usuario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneaker Shop - Tienda de Zapatillas</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="js/main.js" defer></script>
</head>
<body>
<header>
    <a href="index.php" class="logo">
        <img src="img/logo.jpg" alt="Sneaker Shop - Tienda de Zapatillas">
    </a>
    <nav>
        <?php if ($usuario === null): ?>
            <a href="login.php">Iniciar sesión</a>
            <a href="registro.php">Registrarse</a>
        <?php elseif ($usuario['rol'] === 'admin'): ?>
            <a href="admin_productos.php">Productos</a>
            <a href="admin_usuarios.php">Usuarios</a>
            <span>Hola, <?= e($usuario['nombre']) ?></span>
            <a href="logout.php">Salir</a>
        <?php else: ?>
            <a href="catalogo.php">Catálogo</a>
            <a href="carrito.php">Carrito (<?= CarritoController::unidades() ?>)</a>
            <span>Hola, <?= e($usuario['nombre']) ?></span>
            <a href="logout.php">Salir</a>
        <?php endif; ?>
    </nav>
</header>
<main>
<?php if (isset($_SESSION['mensaje'])): ?>
    <p class="aviso"><?= e($_SESSION['mensaje']) ?></p>
    <?php unset($_SESSION['mensaje']); // se muestra una sola vez ?>
<?php endif; ?>
<?php if (!empty($errores)): ?>
    <ul class="errores">
        <?php foreach ($errores as $error): ?>
            <li><?= e($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
