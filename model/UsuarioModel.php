<?php

require_once("../library/conexion.php");

class UsuarioModel
{

    private $conexion;
    function __construct()
    {

        $this->conexion = new Conexion();

        $this->conexion = $this->conexion->connect();
    }

    public function registrar($nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol, $password)
    {
        $consulta = "INSERT INTO persona (nro_identidad, razon_social, telefono, correo, departamento, provincia, distrito, cod_postal, direccion, rol, password) VALUES ('$nro_identidad', '$razon_social', '$telefono', '$correo', '$departamento', '$provincia', '$distrito','$cod_postal', '$direccion', '$rol', '$password')";

        $sql = $this->conexion->query($consulta);

        if ($sql) {
            $sql = $this->conexion->insert_id;
        } else {
            $sql = 0;
        }
        return $sql;
    }
    public function existePersona($nro_identidad)
    {
        $consulta = "SELECT *FROM persona WHERE nro_identidad='$nro_identidad'";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }
    // Método para buscar a una persona por su número de identidad, normalmente usado en el inicio de sesión
    public function buscarPersonaPorNroIdentidad($nro_identidad)
    {
        $consulta = "SELECT id, razon_social, password FROM persona WHERE nro_identidad='$nro_identidad' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        // devuelve los datos como un objeto
        return $sql->fetch_object();
    }


    public function verUsuarios()
    {
        $arr_usuarios = array();
        $consulta = "SELECT * FROM persona WHERE rol NOT IN ('cliente', 'proveedor')"; // Excluye clientes y proveedores
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_usuarios, $objeto);
        }
        return $arr_usuarios;
    }

    public function obtenerUsuarioPorId($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM persona WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function buscarPorDocumento($nro_identidad)
    {
        $stmt = $this->conexion->prepare("SELECT id FROM persona WHERE nro_identidad = ?");
        $stmt->bind_param("s", $nro_identidad);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function actualizarPersona($data)
    {
        $stmt = $this->conexion->prepare("UPDATE persona SET nro_identidad = ?, razon_social = ?, telefono = ?, correo = ?, departamento = ?, provincia = ?, distrito = ?, cod_postal = ?, direccion = ?, rol = ? WHERE id = ?");

        $stmt->bind_param(
            "ssssssssssi",
            $data['nro_identidad'],
            $data['razon_social'],
            $data['telefono'],
            $data['correo'],
            $data['departamento'],
            $data['provincia'],
            $data['distrito'],
            $data['cod_postal'],
            $data['direccion'],
            $data['rol'],
            $data['id_persona']
        );

        return $stmt->execute(); // Devuelve true si se actualizó correctamente, false si no
    }
    // eliminar usuario
    public function eliminarUsuario($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM persona WHERE id = ?");
        $stmt->bind_param("i", $id); // "i" porque es un número entero (id)

        if ($stmt->execute()) {
            return ["status" => true, "msg" => "Usuario eliminado correctamente"];
        } else {
            return ["status" => false, "msg" => "Error al eliminar el usuario"];
        }
    }
    // ver proveedor
    public function verProveedores()
    {
        $arr_proveedores = array();
        $consulta = "SELECT id, razon_social FROM persona WHERE rol = 'proveedor'";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            $arr_proveedores[] = $objeto;
        }
        return $arr_proveedores;
    }

    //ver cliente
    public function verClientes()
    {
        $arr_usuarios = array();
        $consulta = "SELECT * FROM persona WHERE rol = 'cliente' GROUP BY nro_identidad"; // Solo clientes, sin duplicados
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_usuarios, $objeto);
        }
        return $arr_usuarios;
    }

    // ver proveedor detallado
    public function verProveedoresDetallados()
    {
        $arr_usuarios = array();
        $consulta = "SELECT * FROM persona WHERE rol = 'proveedor' GROUP BY nro_identidad"; // Solo proveedores con todos los campos, sin duplicados
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_usuarios, $objeto);
        }
        return $arr_usuarios;
    }
}
