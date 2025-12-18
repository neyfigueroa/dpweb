<?php
require_once("../library/conexion.php");
class VentaModel
{
    private $conexion;
    public $lastError = '';
    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    private function exec($consulta)
    {
        try {
            return $this->conexion->query($consulta);
        } catch (mysqli_sql_exception $e) {
            $this->lastError = $e->getMessage();
            error_log("VentaModel SQL error: " . $this->lastError);
            return false;
        }
    }

    // Attempt to ensure the `id` column is AUTO_INCREMENT on a table.
    // Returns true if OK or already auto_increment, false on failure.
    private function ensureAutoIncrement($table)
    {
        $consulta = "SHOW COLUMNS FROM $table LIKE 'id'";
        $res = $this->exec($consulta);
        if (!$res) return false;
        if ($res->num_rows <= 0) return false;
        $col = $res->fetch_assoc();
        if (isset($col['Extra']) && stripos($col['Extra'], 'auto_increment') !== false) {
            return true;
        }
        // Try to alter the column to INT NOT NULL AUTO_INCREMENT PRIMARY KEY
        $alter = "ALTER TABLE $table MODIFY id INT NOT NULL AUTO_INCREMENT PRIMARY KEY";
        $ok = $this->exec($alter);
        if (!$ok) {
            error_log("VentaModel: failed to add AUTO_INCREMENT to $table - " . $this->lastError);
            return false;
        }
        return true;
    }

    // Buscar un registro temporal por id_producto
    public function buscarTemporalPorId($id_producto)
    {
        $consulta = "SELECT * FROM temporal_venta WHERE id_producto = '$id_producto' LIMIT 1";
        $sql = $this->exec($consulta);
        if ($sql && $sql->num_rows > 0) {
            return $sql->fetch_object();
        }
        return false;
    }

    // Elimina todos los registros temporales
    public function eliminarTemporales()
    {
        $consulta = "DELETE FROM temporal_venta";
        return $this->exec($consulta);
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
        return $this->exec($consulta);
    }

    public function registrar_temporal($id_producto, $precio, $cantidad)
    {
        // Ensure table has id AUTO_INCREMENT to avoid 'Field id doesn't have a default value'
        $this->ensureAutoIncrement('temporal_venta');
        $consulta = "INSERT INTO temporal_venta (id_producto, precio, cantidad) VALUES ('$id_producto', '$precio', '$cantidad')";
        $sql = $this->exec($consulta);
        if ($sql) {
            return $this->conexion->insert_id;
        }
        return 0;
    }
    public function actualizarCantidadTemporal($id_producto, $cantidad)
    {
        $consulta = "UPDATE temporal_venta SET cantidad='$cantidad' WHERE id_producto='$id_producto'";
        $sql = $this->exec($consulta);
        return $sql;
    }
    public function actualizarCantidadTemporalByid($id, $cantidad)
    {
        $consulta = "UPDATE temporal_venta SET cantidad='$cantidad' WHERE id='$id'";
        $sql = $this->exec($consulta);
        return $sql;
    }
    public function buscarTemporales()
    {
        $arr_temporal = array();
        $consulta = "SELECT tv.*, p.nombre FROM temporal_venta tv INNER JOIN producto p ON tv.id_producto = p.id";
        $sql = $this->exec($consulta);
        if ($sql) {
            while ($objeto = $sql->fetch_object()) {
                array_push($arr_temporal, $objeto);
            }
        }
        return $arr_temporal;
    }
    public function listarVentas_Temporal(){

    }

    //---------------------- VENTAS REGISTRADAS (OFICIALES)----------------

    public function buscar_ultima_venta(){
        $consulta = "SELECT codigo FROM venta ORDER BY id DESC LIMIT 1";
        $sql = $this->exec($consulta);
        if ($sql) return $sql->fetch_object();
        return false;
    }
    public function registrar_venta($correlativo, $fecha_venta, $id_cliente, $id_vendedor){
        // Ensure venta.id exists as AUTO_INCREMENT
        $this->ensureAutoIncrement('venta');
        $consulta = "INSERT INTO venta (codigo, fecha_hora, id_cliente, id_vendedor) VALUES ('$correlativo', '$fecha_venta', '$id_cliente', '$id_vendedor')";
        $sql = $this->exec($consulta);
        if ($sql) {
            return $this->conexion->insert_id;
        }
        return 0;
    }
    public function registrar_detalle_venta($id_venta, $id_producto, $precio, $cantidad){
        // Ensure detalle_venta.id is AUTO_INCREMENT (best-effort)
        $this->ensureAutoIncrement('detalle_venta');
        $consulta = "INSERT INTO detalle_venta (id_venta, id_producto, precio, cantidad) VALUES ('$id_venta', '$id_producto', '$precio', '$cantidad')";
        $sql = $this->exec($consulta);
        return $sql;
    }
}