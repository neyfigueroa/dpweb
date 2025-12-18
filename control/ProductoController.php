<?php
// =======================================================
// CONTROLADOR: productosController.php
// Este archivo recibe las peticiones del cliente (vistas o AJAX)
// y utiliza el modelo ProductoModel.php para interactuar con la BD.
// =======================================================

// Deshabilitar la salida de errores HTML para evitar interferir con JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Se incluye el modelo del producto
require_once("../model/ProductoModel.php");

// Instancia del modelo
$objProducto = new ProductoModel();

// Tipo de operación que viene por GET (?tipo=...)
$tipo = $_GET['tipo'] ?? ''; // Evita error si no se envía tipo


// =======================================================
// 1. REGISTRAR PRODUCTO
// =======================================================
if ($tipo == 'registrar') {

    // Captura de los datos del formulario (con validación)
    $codigo = $_POST['codigo'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $detalle = $_POST['detalle'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? '';
    $id_categoria = $_POST['id_categoria'] ?? '';
    $id_proveedor = $_POST['id_persona'] ?? ''; // Proveedor opcional

    // Validar campos obligatorios
    if ($codigo == "" || $nombre == "" || $detalle == "" || $precio == "" || $stock == "" || $fecha_vencimiento == "" || $id_categoria == "") {
        echo json_encode(['status' => false, 'msg' => 'Error, campos vacíos']);
        exit;
    }

    // Validar que se haya subido una imagen
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => false, 'msg' => 'Error, imagen no recibida']);
        exit;
    }

    // Validar si el código ya existe
    if ($objProducto->existeCodigo($codigo) > 0) {
        echo json_encode(['status' => false, 'msg' => 'Error, el código ya existe']);
        exit;
    }

    // Validar extensión y tamaño de imagen
    $file = $_FILES['imagen'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $extPermitidas = ['jpg', 'jpeg', 'png'];
    if (!in_array($ext, $extPermitidas)) {
        echo json_encode(['status' => false, 'msg' => 'Formato de imagen no permitido']);
        exit;
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['status' => false, 'msg' => 'La imagen supera 5MB']);
        exit;
    }

    // Crear carpeta de subida si no existe
    $carpetaUploads = "../Uploads/productos/";
    if (!is_dir($carpetaUploads)) {
        @mkdir($carpetaUploads, 0775, true);
    }

    // Asignar nombre único a la imagen
    $nombreUnico = uniqid('prod_') . '.' . $ext;
    $rutaFisica = $carpetaUploads . $nombreUnico;
    $rutaRelativa = "uploads/productos/" . $nombreUnico;

    // Mover archivo al servidor
    if (!move_uploaded_file($file['tmp_name'], $rutaFisica)) {
        echo json_encode(['status' => false, 'msg' => 'No se pudo guardar la imagen']);
        exit;
    }

    // Validar categoría antes de registrar
    require_once("../model/CategoriaModel.php");
    $objCategoria = new CategoriaModel();
    $categoria = $objCategoria->obtenerCategoriaPorId($id_categoria);
    if (!$categoria) {
        echo json_encode(['status' => false, 'msg' => 'Error, la categoría no existe']);
        exit;
    }

    // Registrar producto
    $id = $objProducto->registrar($codigo, $nombre, $detalle, $precio, $stock, $fecha_vencimiento, $rutaRelativa, $id_categoria, $id_proveedor);

    if ($id > 0) {
        echo json_encode(['status' => true, 'msg' => 'Registrado correctamente', 'id' => $id, 'img' => $rutaRelativa]);
    } else {
        @unlink($rutaFisica); // Si falla el registro, eliminar imagen subida
        echo json_encode(['status' => false, 'msg' => 'Error, fallo en registro']);
    }
    exit;
}


// =======================================================
// 2. VER PRODUCTOS (lista completa)
// =======================================================
if ($tipo == "ver_productos" || $tipo == "mostrar_productos") {
    // Obtiene todos los productos de la base de datos
    $productos = $objProducto->verProductos();
    // Retorna en formato JSON para usarse con JS o AJAX
    echo json_encode(['status' => true, 'msg' => '', 'data' => $productos]);
    exit;
}


// =======================================================
// 3. OBTENER PRODUCTO POR ID
// =======================================================
if ($tipo == 'obtener_producto') {
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? 0;
    $producto = $objProducto->obtenerProductoPorId($id);
    echo json_encode($producto);
    exit;
}


// =======================================================
// 4. ACTUALIZAR PRODUCTO
// =======================================================
if ($tipo == "actualizar_producto") {

    $data = $_POST;
    error_log("Datos recibidos en controlador: " . print_r($data, true));

    $nombre = $data['nombre'];
    $id_actual = $data['id_producto'];
    $id_categoria = $data['id_categoria'] ?? NULL;
    $id_proveedor = $data['id_persona'] ?? NULL;

    // Validar nombre duplicado
    $verificar = $objProducto->buscarPorNombre($nombre);
    if ($verificar && $verificar['id'] != $id_actual) {
        echo json_encode(['status' => false, 'msg' => 'Error, producto ya existe.']);
        exit;
    }

    // Validar categoría
    require_once("../model/CategoriaModel.php");
    $objCategoria = new CategoriaModel();
    if ($id_categoria && !$objCategoria->obtenerCategoriaPorId($id_categoria)) {
        echo json_encode(['status' => false, 'msg' => 'Error, la categoría no existe']);
        exit;
    }

    // Obtener producto actual para manejar imagen
    $productoActual = $objProducto->obtenerProductoPorId($id_actual);

    // Si se sube nueva imagen, reemplazar
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {

        // Eliminar imagen anterior si existe
        if ($productoActual && isset($productoActual['imagen']) && $productoActual['imagen'] != '' && file_exists("../" . $productoActual['imagen'])) {
            unlink("../" . $productoActual['imagen']);
        }

        // Subir nueva imagen
        $target_dir = "../Uploads/productos/";
        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0775, true);
        }

        $ext = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
        $extPermitidas = ['jpg', 'jpeg', 'png'];
        if (!in_array($ext, $extPermitidas)) {
            echo json_encode(['status' => false, 'msg' => 'Formato de imagen no permitido']);
            exit;
        }

        if ($_FILES['imagen']['size'] > 5 * 1024 * 1024) {
            echo json_encode(['status' => false, 'msg' => 'La imagen supera 5MB']);
            exit;
        }

        $nombreUnico = uniqid('prod_') . '.' . $ext;
        $rutaFisica = $target_dir . $nombreUnico;
        $data['imagen'] = "uploads/productos/" . $nombreUnico;

        if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaFisica)) {
            echo json_encode(['status' => false, 'msg' => 'No se pudo guardar la imagen']);
            exit;
        }

    } else {
        // Mantener imagen actual si no se sube nueva
        $data['imagen'] = $productoActual['imagen'];
    }

    // Actualizar registro
    $data['id_categoria'] = $id_categoria;
    $data['id_proveedor'] = $id_proveedor;
    $actualizado = $objProducto->actualizarProducto($data);

    echo json_encode(['status' => $actualizado, 'msg' => $actualizado ? 'Producto actualizado correctamente' : 'Error al actualizar']);
    exit;
}


// =======================================================
// 5. ELIMINAR PRODUCTO
// =======================================================
if ($tipo == "eliminar_producto") {
    $id = $_GET['id'] ?? 0;

    // Obtener producto para eliminar imagen del servidor
    $producto = $objProducto->obtenerProductoPorId($id);
    if ($producto && isset($producto['imagen']) && $producto['imagen'] != '' && file_exists("../" . $producto['imagen'])) {
        unlink("../" . $producto['imagen']);
    }

    // Eliminar registro de la base de datos
    $result = $objProducto->eliminarProducto($id);
    echo json_encode($result);
    exit;
    
}  
if ($tipo == "buscar_productos_venta") {
    $dato = $_POST['dato'];
    $respuesta = array('status' => false,'msg' => 'fallo en el controlador', 'data' => []);
    $productos = $objProducto->buscarProductoByNombreOrCodigo($dato);
        $respuesta = array('status' => true, 'msg' => '', 'data' => $productos);
    echo json_encode($respuesta);
    exit;
}

?>
