<?php
// Lógica de usuarios: validación, registro de clientes y CRUD del administrador (RF01).

class UsuarioController
{
    private Usuario $modelo;

    public function __construct()
    {
        $this->modelo = new Usuario();
    }

    // Recoge y limpia los campos del formulario ($_POST).
    public function leerFormulario(array $post): array
    {
        return [
            'nombre'    => trim($post['nombre'] ?? ''),
            'email'     => trim($post['email'] ?? ''),
            'password'  => $post['password'] ?? '',
            'telefono'  => trim($post['telefono'] ?? ''),
            'direccion' => trim($post['direccion'] ?? ''),
            'rol'       => ($post['rol'] ?? '') === 'admin' ? 'admin' : 'cliente',
        ];
    }

    // Obligatorios: nombre, email y contraseña (en edición la contraseña es opcional).
    // Opcionales: teléfono y dirección.
    public function validar(array $d, ?int $id = null): array
    {
        $errores = [];
        if ($d['nombre'] === '') {
            $errores[] = 'El nombre es obligatorio.';
        }
        if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email no es válido.';
        }
        if ($id === null && strlen($d['password']) < 6) {
            $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
        }
        if ($id !== null && $d['password'] !== '' && strlen($d['password']) < 6) {
            $errores[] = 'La nueva contraseña debe tener al menos 6 caracteres.';
        }
        // El email no puede pertenecer a otro usuario.
        $existente = $this->modelo->buscarPorEmail($d['email']);
        if ($existente !== null && (int) $existente['id'] !== $id) {
            $errores[] = 'Ya existe un usuario con ese email.';
        }
        return $errores;
    }

    // Registro público: siempre crea un cliente.
    public function registrar(array $post): array
    {
        $d = $this->leerFormulario($post);
        $d['rol'] = 'cliente';
        $errores = $this->validar($d);
        if (empty($errores)) {
            $this->modelo->crear($d);
        }
        return $errores;
    }

    // Alta o modificación desde el panel de administración.
    public function guardar(array $post): array
    {
        $id = (int) ($post['id'] ?? 0) ?: null;
        $d = $this->leerFormulario($post);
        $errores = $this->validar($d, $id);
        if (empty($errores)) {
            $id === null ? $this->modelo->crear($d) : $this->modelo->actualizar($id, $d);
        }
        return $errores;
    }

    // Elimina un usuario. El administrador no puede borrarse a sí mismo.
    public function eliminar(int $id): string
    {
        if ($id === AuthController::usuario()['id']) {
            return 'No puedes eliminar tu propio usuario.';
        }
        $this->modelo->eliminar($id);
        return 'Usuario eliminado.';
    }

    public function listar(): array
    {
        return $this->modelo->listar();
    }

    public function buscar(int $id): ?array
    {
        return $this->modelo->buscarPorId($id);
    }
}
