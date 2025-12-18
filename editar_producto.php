<?php
// ...existing code...

// Supongamos que `$producto` contiene los datos del producto desde la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>
    <form action="actualizar_producto.php" method="POST" enctype="multipart/form-data">
        <!-- ...existing form fields... -->

        <!-- Mostrar la imagen actual -->
        <div>
            <label>Imagen actual:</label>
            <?php if (!empty($producto['imagen'])): ?>
                <img src="<?php echo $producto['imagen']; ?>" alt="Imagen del producto" style="max-width: 200px; max-height: 200px;">
            <?php else: ?>
                <p>No hay imagen disponible</p>
            <?php endif; ?>
        </div>

        <!-- Campo para subir una nueva imagen -->
        <div>
            <label for="imagen">Subir nueva imagen:</label>
            <input type="file" name="imagen" id="imagen">
        </div>

        <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
        <button type="submit">Actualizar Producto</button>
    </form>
</body>
</html>