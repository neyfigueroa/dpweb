<?php
require_once("../library/conexion.php");
class VentaModel
{
    private $conexion;
    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    // Buscar un registro temporal por id_producto
    public function buscarTemporalPorId($id_producto)
    {
        $consulta = "SELECT * FROM temporal_venta WHERE id_producto = '$id_producto' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        if ($sql && $sql->num_rows > 0) {
            return $sql->fetch_object();
        }
        return false;
    }

    // Elimina todos los registros temporales
    public function eliminarTemporales()
    {
        $consulta = "DELETE FROM temporal_venta";
        return $this->conexion->query($consulta);
    }

    // Alias para compatibilidad: limpiar temporal
    public function limpiarTemporal()
    {
        return $this->eliminarTemporales();
    }

    // Eliminar un temporal por su id
    public function eliminarTemporalById($id)
    {
        $consulta = "DELETE FROM temporal_venta WHERE id='$id'";
        return $this->conexion->query($consulta);
    }

    public function registrar_temporal($id_producto, $precio, $cantidad)
    {
        $consulta = "INSERT INTO temporal_venta (id, id_producto, precio, cantidad) VALUES (NULL, '$id_producto', '$precio', '$cantidad')";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            return $this->conexion->insert_id;
        }
        return 0;
    }
    public function actualizarCantidadTemporal($id_producto, $cantidad)
    {
        $consulta = "UPDATE temporal_venta SET cantidad='$cantidad' WHERE id_producto='$id_producto'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }
    public function actualizarCantidadTemporalByid($id, $cantidad)
    {
        $consulta = "UPDATE temporal_venta SET cantidad='$cantidad' WHERE id='$id'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }
    public function buscarTemporales()
    {
        $arr_temporal = array();
        $consulta = "SELECT tv.*, p.nombre FROM temporal_venta tv INNER JOIN producto p ON tv.id_producto = p.id";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_temporal, $objeto);
        }
        return $arr_temporal;
    }
    public function listarVentas_Temporal(){

    }

    //---------------------- VENTAS REGISTRADAS (OFICIALES)----------------

    public function buscar_ultima_venta(){
        $consulta = "SELECT codigo FROM venta ORDER BY id DESC LIMIT 1";
        $sql = $this->conexion->query($consulta);
        return $sql->fetch_object();
    }
    public function registrar_venta($correlativo, $fecha_venta, $id_cliente, $id_vendedor){
        $consulta = "INSERT INTO venta (codigo, fecha_hora, id_cliente, id_vendedor) VALUES ('$correlativo', '$fecha_venta', '$id_cliente', '$id_vendedor')";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            return $this->conexion->insert_id;
        }
        return 0;
    }
    public function registrar_detalle_venta($id_venta, $id_producto, $precio, $cantidad){
        $consulta = "INSERT INTO detalle_venta (id_venta, id_producto, precio, cantidad) VALUES ('$id_venta', '$id_producto', '$precio', '$cantidad')";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }
}