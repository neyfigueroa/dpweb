<?php
// Se importa el modelo llamado views_model.php que contiene la lógica para cargar vistas.
require_once "./model/views_model.php";
// Se define una clase llamada viewsControl que hereda de viewModel.
// Esta clase se encarga de controlar qué vista se mostrará y qué plantilla cargar.
class viewsControl extends viewModel
{
      // Este método simplemente carga el archivo de la plantilla principal del sitio
    public function getPlantillaControl()
    {
             // Carga la plantilla ubicada en ./view/plantilla.php
        return require_once "./view/plantilla.php";
    }
    //  Determinar qué vista mostrar al usuario
    public function getViewControl()
    {
          // Inicia la sesión para verificar si el usuario está logueado
        session_start();
        
        // Si el usuario tiene una sesión activa (ya inició sesión)
        if (isset($_SESSION['ventas_id'])) {
 // Si existe el parámetro GET llamado "views"
            if (isset($_GET["views"])) {
                $ruta = explode("/", $_GET["views"]);
    // Llama al método get_view heredado desde viewModel para obtener la ruta de la vista
                $response = viewModel::get_view($ruta[0]);
            } else {
    // Si no hay vista específica, por defecto se carga "index.php"
                $response = "index.php";
            }
        }else{

    // Si el usuario NO está logueado, se le envía a la vista de login
            $response ="login";
        }
     // Devuelve el nombre o ruta de la vista que se debe mostrar
        return $response;
    }
}
