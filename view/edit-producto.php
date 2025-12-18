<!-- INICIO DE CUERPO DE PAGINA -->
<div class="container-fluid">
    <div class="card">
        <h5 class="card-header"><i class="bi bi-pencil-square"></i> Actualizar Producto</h5>
        <form id="frm_edit_produc" enctype="multipart/form-data">
            <input type="hidden" id="id_producto" name="id_producto">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo" class="form-label"><i class="bi bi-upc"></i> Código:</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label"><i class="bi bi-tag"></i> Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="detalle" class="form-label"><i class="bi bi-info-circle"></i> Detalle:</label>
                            <input type="text" class="form-control" id="detalle" name="detalle" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="precio" class="form-label"><i class="bi bi-cash"></i> Precio:</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stock" class="form-label"><i class="bi bi-boxes"></i> Stock:</label>
                            <input type="number" min="0" class="form-control" id="stock" name="stock" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fecha_vencimiento" class="form-label"><i class="bi bi-calendar-x"></i> Fecha Vencimiento:</label>
                            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="imagen" class="form-label"><i class="bi bi-image"></i> Imagen:</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_categoria" class="form-label"><i class="bi bi-tags"></i> Categoría:</label>
                            <select class="form-control" id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccione una categoría</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_persona" class="form-label"><i class="bi bi-truck"></i> Proveedor:</label>
                            <select class="form-control" id="id_persona" name="id_persona">
                                <option value="">Seleccionar Proveedor</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar"><i class="bi bi-check-circle"></i> Actualizar</button>
                    <a href="<?php echo BASE_URL; ?>produc" class="btn btn-danger btn-sm"><i class="bi bi-x-circle"></i> Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- FIN DE CUERPO DE PAGINA -->
<script src="<?php echo BASE_URL; ?>view/function/products.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        let partes = window.location.pathname.split('/');
        let id = partes[partes.length - 1];

        cargarCategorias(); // Cargar categorías primero
        cargarProveedores();

        if (!isNaN(id)) {
            obtenerProductoPorId(id); // Luego cargar el producto para seleccionar la categoría
        }
    });
</script>
