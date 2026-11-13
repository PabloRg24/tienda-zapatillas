<?php
// Capa de acceso a datos: conexión única a MySQL mediante PDO.

class Database
{
    // Datos de conexión de XAMPP por defecto (usuario root sin contraseña).
    private const DSN = 'mysql:host=localhost;dbname=tienda_zapatillas;charset=utf8mb4';
    private const USUARIO = 'root';
    private const PASSWORD = '';

    private static ?PDO $conexion = null;

    // Devuelve siempre la misma conexión para no abrir una nueva en cada consulta.
    public static function conectar(): PDO
    {
        if (self::$conexion === null) {
            self::$conexion = new PDO(self::DSN, self::USUARIO, self::PASSWORD, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // los errores SQL lanzan excepción
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // filas como arrays asociativos
            ]);
        }
        return self::$conexion;
    }
}
