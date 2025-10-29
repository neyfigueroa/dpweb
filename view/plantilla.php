<?php
// Incluye la configuración general del sistema (rutas, constantes, etc.)
require_once "./config/config.php";
// Incluye el controlador que maneja las vistas
require_once "./control/views_control.php";
// Se crea un objeto de la clase viewsControl
$view = new viewsControl();
// Se llama al método getViewControl() para obtener qué vista se debe cargar
$mostrar = $view->getViewControl();

if ($mostrar == "login" || $mostrar == "404") {
   require_once "./view/".$mostrar.".php";
}else{
    include "./view/include/header.php"; //cargamos el header
    include $mostrar;
    include "./view/include/footer.php"; // cargamos el footer
}