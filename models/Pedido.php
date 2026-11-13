<?php
// Modelo Pedido: guarda una compra y sus líneas en la base de datos.

class Pedido
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    // Crea el pedido y sus líneas dentro de una transacción:
    // o se guarda todo, o no se guarda nada.
    // $lineas = [['producto_id' => 1, 'cantidad' => 2, 'precio' => 89.99], ...]
    public function crear(int $usuarioId, array $lineas, float $total): int
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)');
            $stmt->execute([$usuarioId, $total]);
            $pedidoId = (int) $this->db->lastInsertId();

            $stmt = $this->db->prepare(
                'INSERT INTO pedido_detalle (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)'
            );
            foreach ($lineas as $l) {
                $stmt->execute([$pedidoId, $l['producto_id'], $l['cantidad'], $l['precio']]);
            }

            $this->db->commit();
            return $pedidoId;
        } catch (PDOException $ex) {
            $this->db->rollBack(); // deshace lo insertado si algo falla
            throw $ex;
        }
    }
}
