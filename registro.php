<?php
// Formulario de registro para clientes nuevos.
require_once 'includes/init.php';

if (AuthController::usuario() !== null) {
    redirigir(AuthController::paginaInicio());
}

$controlador = new UsuarioController();
$errores = [];
$datos = $controlador->leerFormulario([]); // campos vacíos para el primer acceso

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = $controlador->leerFormulario($_POST);
    $errores = $controlador->registrar($_POST);

    if (empty($errores)) {
        mensaje('Registro completado. Ya puedes iniciar sesión.');
        redirigir('login.php');
    }
}

require 'includes/cabecera.php';
?>
<section class="acceso">
<h2>Registro de cliente</h2>
<p>Los campos marcados con * son obligatorios.</p>
<form method="post" action="registro.php" class="formulario">
    <label>Nombre *
        <input type="text" name="nombre" value="<?= e($datos['nombre']) ?>" required>
    </label>
    <label>Email *
        <input type="email" name="email" value="<?= e($datos['email']) ?>" required>
    </label>
    <label>Contraseña * (mínimo 6 caracteres)
        <input type="password" name="password" minlength="6" required>
    </label>
    <label>Teléfono
        <input type="tel" name="telefono" value="<?= e($datos['telefono']) ?>">
    </label>
    <label>Dirección
        <input type="text" name="direccion" value="<?= e($datos['direccion']) ?>">
    </label>
    <button type="submit">Registrarme</button>
</form>
<p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
</section>
<?php require 'includes/pie.php'; ?>
