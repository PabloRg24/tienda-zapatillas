<?php
// Panel del administrador: crear, modificar y eliminar zapatillas (RF02).
require_once 'includes/init.php';
AuthController::requerirRol('admin');

$controlador = new ProductoController();
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['accion'] ?? '') === 'eliminar') {
        $controlador->eliminar((int) $_POST['id']);
        mensaje('Producto eliminado.');
        redirigir('admin_productos.php');
    }

    $errores = $controlador->guardar($_POST);
    if (empty($errores)) {
        mensaje(empty($_POST['id']) ? 'Producto creado.' : 'Producto actualizado.');
        redirigir('admin_productos.php');
    }
}

// Datos del formulario: los enviados (si hubo error), los del producto a editar (?editar=id) o vacíos.
$vacio = ['id' => '', 'nombre' => '', 'marca' => '', 'precio' => '', 'descripcion' => '', 'imagen_url' => ''];
if (!empty($errores)) {
    $form = array_merge($vacio, $_POST);
} elseif (isset($_GET['editar'])) {
    $form = $controlador->buscar((int) $_GET['editar']) ?? $vacio;
} else {
    $form = $vacio;
}

$productos = $controlador->listar();

require 'includes/cabecera.php';
?>
<div class="centrado"><h2>Gestión de productos</h2></div>

<section class="acceso">
<h3><?= $form['id'] ? 'Editar producto' : 'Nuevo producto' ?></h3>
<form method="post" action="admin_productos.php" class="formulario">
    <input type="hidden" name="id" value="<?= e((string) $form['id']) ?>">
    <label>Nombre *
        <input type="text" name="nombre" value="<?= e($form['nombre']) ?>" required>
    </label>
    <label>Marca *
        <input type="text" name="marca" value="<?= e($form['marca']) ?>" required>
    </label>
    <label>Precio (€) *
        <input type="number" name="precio" step="0.01" min="0.01" value="<?= e((string) $form['precio']) ?>" required>
    </label>
    <label>Descripción
        <textarea name="descripcion" rows="3"><?= e($form['descripcion']) ?></textarea>
    </label>
    <label>Imagen: URL o archivo de img/, ej: img/zapatilla.jpg (vacía = imagen por defecto)
        <input type="text" name="imagen_url" value="<?= e($form['imagen_url']) ?>">
    </label>
    <button type="submit">Guardar</button>
    <?php if ($form['id']): ?>
        <a href="admin_productos.php">Cancelar</a>
    <?php endif; ?>
</form>
</section>

<h3 class="centrado">Listado</h3>
<table>
    <tr><th>ID</th><th>Imagen</th><th>Nombre</th><th>Marca</th><th>Precio</th><th>Acciones</th></tr>
    <?php foreach ($productos as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><img class="miniatura" src="<?= e(ProductoController::imagen($p)) ?>" alt=""></td>
            <td><?= e($p['nombre']) ?></td>
            <td><?= e($p['marca']) ?></td>
            <td><?= number_format($p['precio'], 2, ',', '.') ?> €</td>
            <td class="acciones">
                <a href="admin_productos.php?editar=<?= $p['id'] ?>">Editar</a>
                <form method="post" action="admin_productos.php" data-confirmar="¿Eliminar este producto?">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <button type="submit" class="peligro">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require 'includes/pie.php'; ?>
