<?php
// Lógica del carrito (RF03). El carrito vive en la sesión:
// $_SESSION['carrito'] = [id_producto => cantidad, ...]

class CarritoController
{
    // Suma la cantidad indicada del producto al carrito. Devuelve false si los datos no son válidos.
    public static function agregar(int $productoId, int $cantidad): bool
    {
        if ($cantidad < 1 || $cantidad > 10) {
            return false; // cantidad fuera del rango permitido (1-10)
        }
        if ((new Producto())->buscarPorId($productoId) === null) {
            return false; // el producto no existe
        }
        $_SESSION['carrito'][$productoId] = ($_SESSION['carrito'][$productoId] ?? 0) + $cantidad;
        return true;
    }

    // Quita el producto entero del carrito.
    public static function quitar(int $productoId): void
    {
        unset($_SESSION['carrito'][$productoId]);
    }

    public static function vaciar(): void
    {
        $_SESSION['carrito'] = [];
    }

    // Número total de unidades (para mostrarlo en el menú).
    public static function unidades(): int
    {
        return array_sum($_SESSION['carrito'] ?? []);
    }

    // Combina la sesión con los datos de la BD y calcula subtotales.
    public static function lineas(): array
    {
        $carrito = $_SESSION['carrito'] ?? [];
        $lineas = [];
        foreach ((new Producto())->buscarVarios(array_keys($carrito)) as $p) {
            $cantidad = $carrito[$p['id']];
            $lineas[] = [
                'producto'  => $p,
                'cantidad'  => $cantidad,
                'subtotal'  => $p['precio'] * $cantidad,
            ];
        }
        return $lineas;
    }

    public static function total(array $lineas): float
    {
        return array_sum(array_column($lineas, 'subtotal'));
    }

    // Guarda el pedido en la BD y vacía el carrito. Devuelve el id del pedido o null si estaba vacío.
    public static function confirmar(): ?int
    {
        $lineas = self::lineas();
        if (empty($lineas)) {
            return null;
        }

        $datos = [];
        foreach ($lineas as $l) {
            $datos[] = [
                'producto_id' => $l['producto']['id'],
                'cantidad'    => $l['cantidad'],
                'precio'      => $l['producto']['precio'], // precio del momento de la compra
            ];
        }

        $pedidoId = (new Pedido())->crear(AuthController::usuario()['id'], $datos, self::total($lineas));
        self::vaciar();
        return $pedidoId;
    }
}
