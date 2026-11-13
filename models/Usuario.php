<?php
// Modelo Usuario: consultas SQL sobre la tabla usuarios.

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    // Devuelve todos los usuarios (sin la contraseña).
    public function listar(): array
    {
        return $this->db->query('SELECT id, nombre, email, telefono, direccion, rol FROM usuarios ORDER BY id')->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    // Inserta un usuario guardando la contraseña cifrada.
    public function crear(array $d): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nombre, email, password, telefono, direccion, rol) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $d['nombre'], $d['email'], password_hash($d['password'], PASSWORD_DEFAULT),
            $d['telefono'] ?: null, $d['direccion'] ?: null, $d['rol'],
        ]);
    }

    // Actualiza un usuario. La contraseña solo se cambia si se ha escrito una nueva.
    public function actualizar(int $id, array $d): void
    {
        $stmt = $this->db->prepare(
            'UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, direccion = ?, rol = ? WHERE id = ?'
        );
        $stmt->execute([$d['nombre'], $d['email'], $d['telefono'] ?: null, $d['direccion'] ?: null, $d['rol'], $id]);

        if ($d['password'] !== '') {
            $stmt = $this->db->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
            $stmt->execute([password_hash($d['password'], PASSWORD_DEFAULT), $id]);
        }
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM usuarios WHERE id = ?');
        $stmt->execute([$id]);
    }
}
