<?php
require_once("../model/ventaModel.php");
require_once("../model/ProductoModel.php");

$objProducto = new ProductoModel();
$objVenta = new VentaModel();

$tipo = $_GET['tipo'];

if ($tipo == "registrarTemporal"){
    $respuesta = array('status' => false, 'msg' => 'Fallo el controlador');
    $id_producto = $_POST['id_producto'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    // Buscar si el producto ya existe en temporal
    $b_producto = $objVenta->buscarTemporalPorId($id_producto);

    if ($b_producto) {
        // sumar la cantidad enviada (por si se agrega más de 1)
        $r_cantidad = intval($b_producto->cantidad) + intval($cantidad);
        $ok = $objVenta->actualizarCantidadTemporal($id_producto, $r_cantidad);
        if ($ok) {
            $respuesta = array('status' => true, 'msg' => 'Actualizado con éxito');
        } else {
            $respuesta = array('status' => false, 'msg' => 'Error al actualizar temporal', 'error' => $objVenta->lastError);
        }
    } else {
        $id = $objVenta->registrar_temporal($id_producto, $precio, $cantidad);
        if ($id > 0) {
            $respuesta = array('status' => true, 'msg' => 'registrado');
        } else {
            $respuesta = array('status' => false, 'msg' => 'Error al registrar temporal', 'error' => $objVenta->lastError);
        }
    }
    echo json_encode($respuesta);
}
    



if ($tipo=="listar_venta_temporal") {
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $b_producto = $objVenta->buscarTemporales();
    if ($b_producto) {
        $respuesta = array('status' => true, 'data' => $b_producto);
    }else {
       $respuesta = array('status' => false, 'msg' => 'no se encontraron datos');
    }
    echo json_encode($respuesta);
}

if($tipo=="actualizar_cantidad"){
    $id = $_POST['id'];
    $cantidad =  $_POST['cantidad'];
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $consulta = $objVenta->actualizarCantidadTemporalByid($id, $cantidad);
    if ($consulta) {
        $respuesta = array('status' => true, 'msg' => 'success');
    }else {
        $respuesta = array('status' => false, 'msg' => 'error');
    }
    echo json_encode($respuesta);
}

if ($tipo == "eliminar_temporal") {
    $id = $_POST['id'];
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $consulta = $objVenta->eliminarTemporalById($id);
    if ($consulta) {
        $respuesta = array('status' => true, 'msg' => 'eliminado');
    } else {
        $respuesta = array('status' => false, 'msg' => 'error');
    }
    echo json_encode($respuesta);
}