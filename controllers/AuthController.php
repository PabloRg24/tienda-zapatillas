<?php
// Lógica de autenticación: login, logout y control de acceso por rol usando la sesión.

class AuthController
{
    // Comprueba email y contraseña. Si son correctos guarda el usuario en la sesión.
    public static function login(string $email, string $password): bool
    {
        $usuario = (new Usuario())->buscarPorEmail($email);

        if ($usuario === null || !password_verify($password, $usuario['password'])) {
            return false;
        }

        session_regenerate_id(true); // nuevo id de sesión tras el login (seguridad)
        $_SESSION['usuario'] = [
            'id'     => (int) $usuario['id'],
            'nombre' => $usuario['nombre'],
            'rol'    => $usuario['rol'],
        ];
        $_SESSION['carrito'] = [];
        return true;
    }

    // Cierra la sesión y borra todos sus datos.
    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    // Usuario conectado o null si nadie ha iniciado sesión.
    public static function usuario(): ?array
    {
        return $_SESSION['usuario'] ?? null;
    }

    // Página de inicio según el rol del usuario.
    public static function paginaInicio(): string
    {
        return self::usuario()['rol'] === 'admin' ? 'admin_productos.php' : 'catalogo.php';
    }

    // Corta el acceso si no hay sesión o si el rol no es el requerido.
    public static function requerirRol(string $rol): void
    {
        $usuario = self::usuario();
        if ($usuario === null) {
            redirigir('login.php');
        }
        if ($usuario['rol'] !== $rol) {
            redirigir(self::paginaInicio());
        }
    }
}
