<?php
require_once "../config/config.php";

//encapsula toda la logica para conectarse a la base de datos 
class Conexion{
    //este es un metodo estatico 
    public static function connect(){
        //se utiliza la clase mysqli para conectarse a la base de datos usando los datos del archivo config.php
        $mysql = new mysqli(BD_HOST, BD_USER, BD_PASSWORD, BD_NAME);
        //se define el charset normalmente utf8, para evitar errores con tildes 
        $mysql->set_charset(BD_CHARSET);
        //se configura la zona horaria 
        date_default_timezone_set("America/Lima");
        //verifica si hubo un error al intentar conectarse, si es asi muestra un mensaje de error 
        if (mysqli_connect_errno()) {
            echo "Error de Conexion:".mysqli_connect_errno();
        }
        //finalmente retorna el objeto de conexion 
        return $mysql;
    }
}





