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

    //

      public function existeCodigo($codigo)
    {
        $codigo = $this->conexion->real_escape_string($codigo);
        $consulta = "SELECT id FROM producto WHERE codigo='$codigo' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }
//
    public function registrar($codigo, $nombre, $detalle, $precio, $stock, $fecha_vencimiento, $imagen, $id_categoria = NULL, $id_proveedor = NULL)
    {
        $stmt = $this->conexion->prepare("INSERT INTO producto (codigo, nombre, detalle, precio, stock, fecha_vencimiento, imagen, id_categoria, id_proveedor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // Convertir id_proveedor a NULL si es una cadena vacía
    $id_proveedor = ($id_proveedor === '' || $id_proveedor === null) ? null : (int)$id_proveedor;
        $stmt->bind_param("ssssdssis", $codigo, $nombre, $detalle, $precio, $stock, $fecha_vencimiento, $imagen, $id_categoria, $id_proveedor);
        $sql = $stmt->execute();
        $id = $sql ? $this->conexion->insert_id : 0;
        $stmt->close();
        return $id;
    }
//
    public function existeProducto($nombre)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM producto WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $sql = $stmt->get_result();
        return $sql->num_rows;
    }

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



   public function obtenerProductoPorId($id) {
    $stmt = $this->conexion->prepare("SELECT * FROM producto WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();
    
    // Depuración: Imprimir el resultado para verificar los datos
    error_log("Producto obtenido por ID $id: " . print_r($producto, true));
    
    return $producto;
}

    public function buscarPorNombre($nombre)
    {
        $stmt = $this->conexion->prepare("SELECT id FROM producto WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

 public function actualizarProducto($data)
{
    error_log("Datos recibidos en modelo: " . print_r($data, true));
    $stmt = $this->conexion->prepare("UPDATE producto SET codigo = ?, nombre = ?, detalle = ?, precio = ?, stock = ?, fecha_vencimiento = ?, imagen = ?, id_categoria = ?, id_proveedor = ? WHERE id = ?");
    
    if ($stmt === false) {
        error_log("Error al preparar la consulta: " . $this->conexion->error);
        return false;
    }

    $id_categoria = $data['id_categoria'] ? $data['id_categoria'] : NULL;
    $id_proveedor = $data['id_proveedor'] ? (int)$data['id_proveedor'] : NULL; // Manejar id_proveedor
    $stmt->bind_param(
        "ssssdssiii", // Cambiado de "ssssdssi" a "ssssdssii" para incluir el tipo de id_producto
        $data['codigo'],
        $data['nombre'],
        $data['detalle'],
        $data['precio'],
        $data['stock'],
        $data['fecha_vencimiento'],
        $data['imagen'],
        $id_categoria,
        $id_proveedor,
        $data['id_producto']
    );

    $resultado = $stmt->execute();
    if ($resultado === false) {
        error_log("Error al ejecutar la consulta: " . $this->conexion->error);
    } else {
        error_log("Consulta ejecutada con éxito");
    }

    // Depuración después de la consulta
    $stmt->close();
    return $resultado;
}

    public function eliminarProducto($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM producto WHERE id = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return ["status" => $resultado, "msg" => $resultado ? "Producto eliminado correctamente" : "Error al eliminar el producto"];
    }
}