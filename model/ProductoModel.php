<?php
// Se incluye el archivo de conexión para poder interactuar con la base de datos
require_once("../library/conexion.php");

// Clase con todas las funciones relacionadas con la tabla producto 
class ProductoModel
{
    // Atributo privado para almacenar la conexión con la base de datos
    private $conexion;

    function __construct()
    {
        // Se crea una nueva instancia de la clase Conexion
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    // ============================
    // VALIDAR EXISTENCIA POR CÓDIGO
    // ============================
    public function existeCodigo($codigo)
    {
        $codigo = $this->conexion->real_escape_string($codigo);
        $consulta = "SELECT id FROM producto WHERE codigo='$codigo' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }

    // ============================
    // REGISTRAR PRODUCTO
    // ============================
    public function registrar($codigo, $nombre, $detalle, $precio, $stock, $fecha_vencimiento, $imagen, $id_categoria = NULL, $id_proveedor = NULL)
    {
        $stmt = $this->conexion->prepare("INSERT INTO producto (codigo, nombre, detalle, precio, stock, fecha_vencimiento, imagen, id_categoria, proveedor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            error_log("Error preparing statement: " . $this->conexion->error);
            return 0;
        }
        // Convertir id_proveedor a NULL si está vacío
        $id_proveedor = ($id_proveedor === '' || $id_proveedor === null) ? null : $id_proveedor;
        $stmt->bind_param("ssssissis", $codigo, $nombre, $detalle, $precio, $stock, $fecha_vencimiento, $imagen, $id_categoria, $id_proveedor);
        $sql = $stmt->execute();
        if (!$sql) {
            error_log("Error executing statement: " . $stmt->error);
            $stmt->close();
            return 0;
        }
        $id = $sql ? $this->conexion->insert_id : 0;
        $stmt->close();
        return $id;
    }

    // ============================
    // VALIDAR EXISTENCIA POR NOMBRE
    // ============================
    public function existeProducto($nombre)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM producto WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $sql = $stmt->get_result();
        return $sql->num_rows;
    }

    // ============================
    // VER TODOS LOS PRODUCTOS
    // ============================
    public function verProductos()
    {
        $arr_productos = array();
        $consulta = "SELECT p.*, c.nombre AS categoria 
                     FROM producto p 
                     LEFT JOIN categoria c ON p.id_categoria = c.id";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_productos, $objeto);
        }
        return $arr_productos;
    }

    // ============================
    // VER PRODUCTOS CON IMAGEN (PARA CLIENTE)
    // ============================
    public function verProductosConImagen()
    {
        $arr_productos = array();
        $consulta = "SELECT p.id, p.nombre, p.detalle, p.precio, p.imagen, c.nombre AS categoria 
                     FROM producto p 
                     LEFT JOIN categoria c ON p.id_categoria = c.id 
                     WHERE p.stock > 0 
                     ORDER BY p.nombre ASC";
        $sql = $this->conexion->query($consulta);
        while ($fila = $sql->fetch_assoc()) {
            $fila['imagen'] = !empty($fila['imagen']) ? $fila['imagen'] : 'view/img/default.jpg';
            $arr_productos[] = $fila;
        }
        return $arr_productos;
    }

    // ============================
    // OBTENER PRODUCTO POR ID
    // ============================
    public function obtenerProductoPorId($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM producto WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();
        error_log("Producto obtenido por ID $id: " . print_r($producto, true));
        return $producto;
    }

    // ============================
    // BUSCAR PRODUCTO POR NOMBRE
    // ============================
    public function buscarPorNombre($nombre)
    {
        $stmt = $this->conexion->prepare("SELECT id FROM producto WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // ============================
    // ACTUALIZAR PRODUCTO
    // ============================
    public function actualizarProducto($data)
    {
        error_log("Datos recibidos en modelo: " . print_r($data, true));
        // Preparar variables (bind_param requiere variables, no expresiones)
        $codigo = isset($data['codigo']) ? $data['codigo'] : null;
        $nombre = isset($data['nombre']) ? $data['nombre'] : null;
        $detalle = isset($data['detalle']) ? $data['detalle'] : null;
        $precio = isset($data['precio']) ? $data['precio'] : null;
        $stock = isset($data['stock']) ? (int)$data['stock'] : null;
        $fecha_vencimiento = isset($data['fecha_vencimiento']) ? $data['fecha_vencimiento'] : null;
        $imagen = isset($data['imagen']) ? $data['imagen'] : null;
        $id_categoria = (isset($data['id_categoria']) && $data['id_categoria'] !== '') ? (int)$data['id_categoria'] : null;
        $id_proveedor = (isset($data['id_proveedor']) && $data['id_proveedor'] !== '') ? (int)$data['id_proveedor'] : null;
        $id_producto = isset($data['id_producto']) ? (int)$data['id_producto'] : null;

        // Usar la columna correcta 'proveedor' en el UPDATE
        $stmt = $this->conexion->prepare("UPDATE producto SET codigo = ?, nombre = ?, detalle = ?, precio = ?, stock = ?, fecha_vencimiento = ?, imagen = ?, id_categoria = ?, proveedor = ? WHERE id = ?");
        if ($stmt === false) {
            error_log("Error al preparar la consulta: " . $this->conexion->error);
            return false;
        }

        // Tipos: s = string, d = double, i = integer
        $stmt->bind_param(
            "sssdissiii",
            $codigo,
            $nombre,
            $detalle,
            $precio,
            $stock,
            $fecha_vencimiento,
            $imagen,
            $id_categoria,
            $id_proveedor,
            $id_producto
        );

        $resultado = $stmt->execute();
        if ($resultado === false) {
            error_log("Error al ejecutar la consulta: " . $stmt->error);
        }
        $stmt->close();
        return $resultado;
    }

    // ============================
    // ELIMINAR PRODUCTO
    // ============================
    public function eliminarProducto($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM producto WHERE id = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return [
            "status" => $resultado,
            "msg" => $resultado ? "Producto eliminado correctamente" : "Error al eliminar el producto"
        ];
    }
    // ============================
    // buscar producto por nombre codigo 
    // ============================
     public function buscarProductoByNombreOrCodigo ($dato)
    {
        $arr_productos= array();
       $consulta = "SELECT * FROM producto WHERE codigo LIKE '$dato%' OR nombre LIKE '%$dato%' OR detalle LIKE '%$dato%' ";
       $sql = $this->conexion->query($consulta);
       while ($objeto = $sql->fetch_object()) {
        array_push($arr_productos,$objeto);
       }
        return $arr_productos;
    }
}
