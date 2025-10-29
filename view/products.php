<!-- INICIO DE CUERPO DE PAGINA -->
    <div class="container-fluid">
        <div class="card">
            <h5 class="card-header"><i class="bi bi-plus-circle"></i> Registrar Producto</h5>
            <form id="frm_produc" action="" method="" enctype="multipart/form-data">
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
                                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
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
                                <select class="form-control" id="id_persona" name="id_persona" required>
                                    <option value="">Seleccionar Proveedor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i> Registrar</button>
                        <button type="reset" class="btn btn-info btn-sm" id="btn_limpiar_produc"><i class="bi bi-arrow-clockwise"></i> Limpiar</button>
                        <button type="button" class="btn btn-danger btn-sm" id="btn_cancelar_produc"><i class="bi bi-x-circle"></i> Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!-- FIN DE CUERPO DE PAGINA -->
<script src="<?php echo BASE_URL; ?>view/function/products.js"></script>
<script>
    cargarCategorias(); // Cargar categorías al iniciar la página
    cargarProveedores();

    // Agregar event listeners para los botones
    document.addEventListener('DOMContentLoaded', function() {
        // Botón Limpiar
        const btnLimpiar = document.getElementById('btn_limpiar_produc');
        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', function() {
                document.getElementById('frm_produc').reset();
                Swal.fire({
                    icon: 'info',
                    title: 'Formulario limpiado',
                    text: 'Todos los campos han sido restablecidos.',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }

        // Botón Cancelar
        const btnCancelar = document.getElementById('btn_cancelar_produc');
        if (btnCancelar) {
            btnCancelar.addEventListener('click', function() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se perderán todos los cambios no guardados.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'Continuar editando'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = base_url + 'produc';
                    }
                });
            });
        }
    });
</script>
