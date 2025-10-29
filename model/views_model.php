<?php
// se declara una clase view model
class viewModel{
     // Método estático protegido get_view
    // Recibe el nombre de la vista solicitada ($view) y devuelve su ruta si es válida.
    protected static function get_view($view){
    // Lista blanca de vistas permitidas
    // Solo estas vistas se pueden acceder directamente si existen en la carpeta /view
        $white_list = ["home", "products", "users", "new-user", "categories", "update", "edit-producto", "edit-categoria", "categoria", "produc", "new-cliente", "new-proveedor", "proveedor", "cliente", "edit-client", "edit-proveedor"];
            // Si la vista está en la lista blanca
        if (in_array($view, $white_list)) {
            // Verifica si el archivo PHP de la vista realmente existe en la carpeta /view
            if (is_file("./view/".$view.".php")) {
                 // Si existe, se devuelve la ruta completa del archivo
                $content = "./view/".$view.".php";
            }else{
                $content = "404";
            }
// Si la vista no esta en la lista blanca se verifica si es la pagina de login, si es se le asigna login como contenido 
        }elseif($view == "login"){ 
              // En este caso, se devuelve directamente "login"
            $content = "login";
        }else{
            $content = "404";
        }
        // Retorna la ruta del contenido (vista) correspondiente
         return $content;
    }
}
