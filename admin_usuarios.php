<?php
// Panel del administrador: crear, modificar y eliminar usuarios clientes y administradores (RF01).
require_once 'includes/init.php';
AuthController::requerirRol('admin');

$controlador = new UsuarioController();
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['accion'] ?? '') === 'eliminar') {
        mensaje($controlador->eliminar((int) $_POST['id']));
        redirigir('admin_usuarios.php');
    }

    $errores = $controlador->guardar($_POST);
    if (empty($errores)) {
        mensaje(empty($_POST['id']) ? 'Usuario creado.' : 'Usuario actualizado.');
        redirigir('admin_usuarios.php');
    }
}

// Datos del formulario: los enviados (si hubo error), los del usuario a editar (?editar=id) o vacíos.
$vacio = ['id' => '', 'nombre' => '', 'email' => '', 'telefono' => '', 'direccion' => '', 'rol' => 'cliente'];
if (!empty($errores)) {
    $form = array_merge($vacio, $_POST);
} elseif (isset($_GET['editar'])) {
    $form = $controlador->buscar((int) $_GET['editar']) ?? $vacio;
} else {
    $form = $vacio;
}

$usuarios = $controlador->listar();

require 'includes/cabecera.php';
?>
<div class="centrado"><h2>Gestión de usuarios</h2></div>

<section class="acceso">
<h3><?= $form['id'] ? 'Editar usuario' : 'Nuevo usuario' ?></h3>
<form method="post" action="admin_usuarios.php" class="formulario">
    <input type="hidden" name="id" value="<?= e((string) $form['id']) ?>">
    <label>Nombre *
        <input type="text" name="nombre" value="<?= e($form['nombre']) ?>" required>
    </label>
    <label>Email *
        <input type="email" name="email" value="<?= e($form['email']) ?>" required>
    </label>
    <label>Contraseña <?= $form['id'] ? '(dejar vacía para no cambiarla)' : '* (mínimo 6 caracteres)' ?>
        <input type="password" name="password" minlength="6" <?= $form['id'] ? '' : 'required' ?>>
    </label>
    <label>Teléfono
        <input type="tel" name="telefono" value="<?= e($form['telefono']) ?>">
    </label>
    <label>Dirección
        <input type="text" name="direccion" value="<?= e($form['direccion']) ?>">
    </label>
    <label>Rol *
        <select name="rol">
            <option value="cliente" <?= $form['rol'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
            <option value="admin" <?= $form['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
        </select>
    </label>
    <button type="submit">Guardar</button>
    <?php if ($form['id']): ?>
        <a href="admin_usuarios.php">Cancelar</a>
    <?php endif; ?>
</form>
</section>

<h3 class="centrado">Listado</h3>
<table>
    <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Rol</th><th>Acciones</th></tr>
    <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= e($u['nombre']) ?></td>
            <td><?= e($u['email']) ?></td>
            <td><?= e($u['telefono']) ?></td>
            <td><?= e($u['rol']) ?></td>
            <td class="acciones">
                <a href="admin_usuarios.php?editar=<?= $u['id'] ?>">Editar</a>
                <form method="post" action="admin_usuarios.php" data-confirmar="¿Eliminar este usuario?">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <button type="submit" class="peligro">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require 'includes/pie.php'; ?>
