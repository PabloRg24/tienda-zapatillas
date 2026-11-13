<?php
// Catálogo de zapatillas para el cliente (RF03).
require_once 'includes/init.php';
AuthController::requerirRol('cliente');

// Añadir al carrito y volver a la página (patrón POST-Redirect-GET).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad = (int) ($_POST['cantidad'] ?? 1);
    $ok = CarritoController::agregar((int) ($_POST['id'] ?? 0), $cantidad);
    mensaje($ok ? "Añadidas $cantidad unidad(es) al carrito." : 'Cantidad no válida (entre 1 y 10).');
    redirigir('catalogo.php');
}

$productos = (new ProductoController())->listar();

require 'includes/cabecera.php';
?>
<h2>Catálogo</h2>
<div class="catalogo">
    <?php foreach ($productos as $p): ?>
        <article class="tarjeta">
            <img src="<?= e(ProductoController::imagen($p)) ?>" alt="<?= e($p['nombre']) ?>">
            <h3><?= e($p['nombre']) ?></h3>
            <p class="marca"><?= e($p['marca']) ?></p>
            <p><?= e($p['descripcion']) ?></p>
            <p class="precio"><?= number_format($p['precio'], 2, ',', '.') ?> €</p>
            <form method="post" action="catalogo.php" class="anadir">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <label>Cantidad
                    <input type="number" name="cantidad" value="1" min="1" max="10" required>
                </label>
                <button type="submit">Añadir al carrito</button>
            </form>
        </article>
    <?php endforeach; ?>
</div>
<?php require 'includes/pie.php'; ?>
