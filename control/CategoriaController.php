<?php
require_once("../model/CategoriaModel.php");

$objCategoria = new CategoriaModel();

$tipo = $_GET['tipo'];

if ($tipo == 'registrar') {
    $nombre = $_POST['nombre'];
    $detalle = $_POST['detalle'];

    if ($nombre == "" || $detalle == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacíos');
    } else {
        $existeCategoria = $objCategoria->existeCategoria($nombre);
        if ($existeCategoria > 0) {
            $arrResponse = array('status' => false, 'msg' => 'Error, el nombre de la categoría ya existe');
        } else {
            $respuesta = $objCategoria->registrar($nombre, $detalle);
            if ($respuesta) {
                $arrResponse = array('status' => true, 'msg' => 'Categoría registrada correctamente');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error, fallo en el registro');
            }
        }
    }
    echo json_encode($arrResponse);
}
//

if ($tipo == "ver_categorias") {
    $categorias = $objCategoria->verCategorias(); // Método que devuelve todas las categorías
    $respuesta = count($categorias)
        ? ['status' => true, 'msg' => '', 'data' => $categorias]
        : ['status' => false, 'msg' => 'fallo el controlador'];

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit;
}



if ($tipo == "obtener_categoria") {
    header('Content-Type: application/json');
    $id = $_GET['id'];
    $modelo = new CategoriaModel();
    $categoria = $modelo->obtenerCategoriaPorId($id);
    echo json_encode($categoria);
    exit;
}
if ($tipo == 'obtener_producto') {
    header('Content-Type: application/json');
    $id = $_GET['id'];    

    require_once '../model/ProductoModel.php';
    $modelo = new ProductoModel();
    $producto = $modelo->obtenerProductoPorId($id);

    // Depuración: Imprimir el producto antes de enviarlo
    error_log("Producto enviado por controlador: " . print_r($producto, true));
    
    echo json_encode($producto);
    exit;
}

if ($tipo == "actualizar_categoria") {
    $data = $_POST;
    $modelo = new CategoriaModel();
    $nombre = $data['nombre'];
    $id_actual = $data['id_categoria'];

    $verificar = $modelo->buscarPorNombre($nombre);
    if ($verificar && $verificar['id'] != $id_actual) {
        echo json_encode(['status' => false, 'msg' => 'Error, categoría ya existe en BD.']);
    } else {
        $actualizado = $modelo->actualizarCategoria($data);
        echo json_encode(['status' => $actualizado, 'msg' => $actualizado ? 'Categoría actualizada correctamente' : 'Error al actualizar']);
    }
}

if ($tipo == "eliminar_categoria") {
    $id = $_GET['id'] ?? 0;
    $result = $objCategoria->eliminarCategoria($id);
    echo json_encode($result);
}