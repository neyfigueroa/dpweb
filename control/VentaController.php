
<?php
require_once("../model/VentaModel.php");
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
        $objVenta->actualizarCantidadTemporal($id_producto, $r_cantidad);
        $respuesta = array('status' => true, 'msg' => 'Actualizado con éxito');
    } else {
        $objVenta->registrar_temporal($id_producto, $precio, $cantidad);
        $respuesta = array('status' => true, 'msg' => 'registrado');
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

if ($tipo == "registrar_venta") {
    session_start();
    $id_cliente = $_POST['id_cliente'];
    $fecha_venta = $_POST['fecha_venta'];
    $id_vendedor = $_SESSION['ventas_id'] ?? 0;
    $ultima_venta = $objVenta->buscar_ultima_venta();
    //Lógica para registrar la venta
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    if ($ultima_venta) {
        $correlativo = $ultima_venta->codigo + 1;
    }else {
        $correlativo = 1;
    }
    //Registrar la venta oficial
    $venta = $objVenta->registrar_venta($correlativo, $fecha_venta, $id_cliente, $id_vendedor);
    if ($venta) {
        //Registrar los detalles de la venta
        $temporales = $objVenta->buscarTemporales();
        foreach ($temporales as $temporal) {
            $objVenta->registrar_detalle_venta($venta, $temporal->id_producto, $temporal->precio, $temporal->cantidad);
        }
        //Eliminar los temporales
        $objVenta->eliminarTemporales();
        $respuesta = array('status' => true, 'msg' => 'venta registrada con exito');
    } else {
        $respuesta = array('status' => false, 'msg' => 'error al registrar la venta');
    }
    echo json_encode($respuesta);
}