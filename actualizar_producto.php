<?php
// Conexión a la base de datos
include 'conexion.php';

$id_producto = $_POST['id_producto'];
$codigo = $_POST['codigo'];
$nombre = $_POST['nombre'];
$detalle = $_POST['detalle'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];
$id_categoria = $_POST['id_categoria'];
$id_proveedor = $_POST['id_proveedor'];
$fecha_vencimiento = $_POST['fecha_vencimiento'];

// Manejo de la imagen
$imagen = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $directorio = 'uploads/productos/';
    $nombreArchivo = uniqid('prod_') . '.' . pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $rutaArchivo = $directorio . $nombreArchivo;

    // Mover el archivo subido al directorio
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaArchivo)) {
        $imagen = $rutaArchivo;
    }
}

// Actualizar el producto en la base de datos
$sql = "UPDATE producto SET 
            codigo = ?, 
            nombre = ?, 
            detalle = ?, 
            precio = ?, 
            stock = ?, 
            id_categoria = ?, 
            id_proveedor = ?, 
            fecha_vencimiento = ?" . 
            ($imagen ? ", imagen = ?" : "") . " 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
if ($imagen) {
    $stmt->bind_param('sssdiisssi', $codigo, $nombre, $detalle, $precio, $stock, $id_categoria, $id_proveedor, $fecha_vencimiento, $imagen, $id_producto);
} else {
    $stmt->bind_param('sssdiissi', $codigo, $nombre, $detalle, $precio, $stock, $id_categoria, $id_proveedor, $fecha_vencimiento, $id_producto);
}

if ($stmt->execute()) {
    echo "Producto actualizado correctamente.";
    header('Location: editar_producto.php?id=' . $id_producto);
} else {
    echo "Error al actualizar el producto: " . $stmt->error;
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>
