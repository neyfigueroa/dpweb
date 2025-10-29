<?php
require_once("../library/conexion.php");
class CategoriaModel
{
    private $conexion;
    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }
    public function registrar($nombre, $detalle)
    {
        $consulta = "INSERT INTO categoria (nombre, detalle) VALUES ('$nombre', '$detalle')";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            $sql = $this->conexion->insert_id;
        } else {
            $sql = 0;
        }
        return $sql;
    }
    public function existeCategoria($nombre)
    {
        $consulta = "SELECT * FROM categoria WHERE nombre='$nombre'";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }

    // ver categoria 

    public function verCategorias()
    {
        $arr_categorias = array();
        $consulta = "SELECT * FROM categoria";
        $result = $this->conexion->query($consulta);
        while ($objeto = $result->fetch_object()) {
            array_push($arr_categorias, $objeto);
        }
        return $arr_categorias;
    }

    public function obtenerCategoriaPorId($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM categoria WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function buscarPorNombre($nombre)
    {
        $stmt = $this->conexion->prepare("SELECT id FROM categoria WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function actualizarCategoria($data)
    {
        $stmt = $this->conexion->prepare("UPDATE categoria SET nombre = ?, detalle = ? WHERE id = ?");
        $stmt->bind_param("ssi", $data['nombre'], $data['detalle'], $data['id_categoria']);
        return $stmt->execute();
    }
//eliminar catecogoria
    public function eliminarCategoria($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM categoria WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return ["status" => true, "msg" => "Categoría eliminada correctamente"];
        } else {
            return ["status" => false, "msg" => "Error al eliminar la categoría"];
        }
    }
}