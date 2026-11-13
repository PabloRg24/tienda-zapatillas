<?php
// Lógica de productos: validación y CRUD del administrador (RF02).

class ProductoController
{
    private Producto $modelo;

    public function __construct()
    {
        $this->modelo = new Producto();
    }

    // Recoge y limpia los campos del formulario ($_POST).
    private function leerFormulario(array $post): array
    {
        return [
            'nombre'      => trim($post['nombre'] ?? ''),
            'marca'       => trim($post['marca'] ?? ''),
            'precio'      => str_replace(',', '.', trim($post['precio'] ?? '')),
            'descripcion' => trim($post['descripcion'] ?? ''),
            'imagen_url'  => trim($post['imagen_url'] ?? ''),
        ];
    }

    private function validar(array $d): array
    {
        $errores = [];
        if ($d['nombre'] === '') {
            $errores[] = 'El nombre es obligatorio.';
        }
        if ($d['marca'] === '') {
            $errores[] = 'La marca es obligatoria.';
        }
        if (!is_numeric($d['precio']) || $d['precio'] <= 0) {
            $errores[] = 'El precio debe ser un número mayor que 0.';
        }
        // La imagen puede ser una URL completa o un archivo de la carpeta img/ (ej: img/zapatilla.jpg).
        $esUrl = filter_var($d['imagen_url'], FILTER_VALIDATE_URL) !== false;
        $esLocal = preg_match('#^img/[\w.-]+$#', $d['imagen_url']) && is_file(__DIR__ . '/../' . $d['imagen_url']);
        if ($d['imagen_url'] !== '' && !$esUrl && !$esLocal) {
            $errores[] = 'La imagen debe ser una URL válida o un archivo existente en img/.';
        }
        // Mismo límite que la columna imagen_url (VARCHAR(1000)) para que MySQL no falle.
        if (strlen($d['imagen_url']) > 1000) {
            $errores[] = 'La URL de la imagen es demasiado larga (máximo 1000 caracteres).';
        }
        return $errores;
    }

    // Alta si no llega id; modificación si llega.
    public function guardar(array $post): array
    {
        $id = (int) ($post['id'] ?? 0);
        $d = $this->leerFormulario($post);
        $errores = $this->validar($d);
        if (empty($errores)) {
            $id === 0 ? $this->modelo->crear($d) : $this->modelo->actualizar($id, $d);
        }
        return $errores;
    }

    public function eliminar(int $id): void
    {
        $this->modelo->eliminar($id);
    }

    public function listar(): array
    {
        return $this->modelo->listar();
    }

    public function buscar(int $id): ?array
    {
        return $this->modelo->buscarPorId($id);
    }

    // Imagen a mostrar: la URL guardada o una por defecto.
    public static function imagen(array $producto): string
    {
        return $producto['imagen_url'] ?: 'img/sin-imagen.svg';
    }
}
