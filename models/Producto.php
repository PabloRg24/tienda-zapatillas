<?php
// Modelo Producto: consultas SQL sobre la tabla productos.

class Producto
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function listar(): array
    {
        return $this->db->query('SELECT * FROM productos ORDER BY id')->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM productos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    // Devuelve varios productos a la vez (se usa para pintar el carrito).
    public function buscarVarios(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        // Genera tantos "?" como ids haya: (?, ?, ?)
        $marcas = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id IN ($marcas)");
        $stmt->execute(array_values($ids));
        return $stmt->fetchAll();
    }

    public function crear(array $d): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO productos (nombre, marca, precio, descripcion, imagen_url) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$d['nombre'], $d['marca'], $d['precio'], $d['descripcion'], $d['imagen_url']]);
    }

    public function actualizar(int $id, array $d): void
    {
        $stmt = $this->db->prepare(
            'UPDATE productos SET nombre = ?, marca = ?, precio = ?, descripcion = ?, imagen_url = ? WHERE id = ?'
        );
        $stmt->execute([$d['nombre'], $d['marca'], $d['precio'], $d['descripcion'], $d['imagen_url'], $id]);
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM productos WHERE id = ?');
        $stmt->execute([$id]);
    }
}
