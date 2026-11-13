<?php
// Carrito del cliente: ver, quitar artículos y confirmar la compra (RF03).
require_once 'includes/init.php';
AuthController::requerirRol('cliente');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'quitar') {
        CarritoController::quitar((int) ($_POST['id'] ?? 0));
    } elseif ($accion === 'vaciar') {
        CarritoController::vaciar();
    } elseif ($accion === 'confirmar') {
        $pedidoId = CarritoController::confirmar();
        mensaje($pedidoId === null
            ? 'El carrito está vacío.'
            : "Compra confirmada. Número de pedido: $pedidoId.");
    }
    redirigir('carrito.php');
}

$lineas = CarritoController::lineas();
$total = CarritoController::total($lineas);

require 'includes/cabecera.php';
?>
<h2>Carrito</h2>
<?php if (empty($lineas)): ?>
    <p>El carrito está vacío. <a href="catalogo.php">Ver catálogo</a></p>
<?php else: ?>
    <table>
        <tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th></th></tr>
        <?php foreach ($lineas as $l): ?>
            <tr>
                <td><?= e($l['producto']['nombre']) ?></td>
                <td><?= number_format($l['producto']['precio'], 2, ',', '.') ?> €</td>
                <td><?= $l['cantidad'] ?></td>
                <td><?= number_format($l['subtotal'], 2, ',', '.') ?> €</td>
                <td>
                    <form method="post" action="carrito.php">
                        <input type="hidden" name="accion" value="quitar">
                        <input type="hidden" name="id" value="<?= $l['producto']['id'] ?>">
                        <button type="submit" class="secundario">Quitar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr><th colspan="3">Total</th><th><?= number_format($total, 2, ',', '.') ?> €</th><th></th></tr>
    </table>

    <div class="acciones">
        <form method="post" action="carrito.php" data-confirmar="¿Vaciar el carrito?">
            <input type="hidden" name="accion" value="vaciar">
            <button type="submit" class="secundario">Vaciar carrito</button>
        </form>
        <form method="post" action="carrito.php" data-confirmar="¿Confirmar la compra?">
            <input type="hidden" name="accion" value="confirmar">
            <button type="submit">Confirmar compra</button>
        </form>
    </div>
<?php endif; ?>
<?php require 'includes/pie.php'; ?>
