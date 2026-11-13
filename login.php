<?php
// Formulario de acceso para usuarios registrados.
require_once 'includes/init.php';

// Si ya hay sesión iniciada no tiene sentido volver a entrar.
if (AuthController::usuario() !== null) {
    redirigir(AuthController::paginaInicio());
}

$errores = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (AuthController::login($email, $password)) {
        redirigir(AuthController::paginaInicio());
    }
    $errores[] = 'Email o contraseña incorrectos.';
}

require 'includes/cabecera.php';
?>
<section class="acceso">
<h2>Iniciar sesión</h2>
<form method="post" action="login.php" class="formulario">
    <label>Email *
        <input type="email" name="email" value="<?= e($email) ?>" required>
    </label>
    <label>Contraseña *
        <input type="password" name="password" required>
    </label>
    <button type="submit">Entrar</button>
    <!-- Datos de prueba visibles solo para la demostración del trabajo -->
    <p class="demo">
        <strong>Cuenta de administrador (demo)</strong><br>
        Email: admin@tienda.com<br>
        Contraseña: admin123
    </p>
    <p class="demo">
        <strong>Cuenta de cliente (demo)</strong><br>
        Email: cliente@tienda.com<br>
        Contraseña: cliente123
    </p>
</form>
<p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
</section>
<?php require 'includes/pie.php'; ?>
