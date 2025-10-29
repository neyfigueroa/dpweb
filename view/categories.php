<!-- INICIO DE CUERPO DE PAGINA -->
    <div class="container-fluid">
        <div class="card">
            <h5 class="card-header"><i class="bi bi-plus-circle"></i> Registrar Categoría</h5>
            <form id="frm_categories">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label"><i class="bi bi-tag"></i> Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="detalle" class="form-label"><i class="bi bi-info-circle"></i> Detalle:</label>
                                <input type="text" class="form-control" id="detalle" name="detalle" required>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i> Registrar</button>
                        <button type="reset" class="btn btn-info btn-sm" id="btn_limpiar_categoria"><i class="bi bi-arrow-clockwise"></i> Limpiar</button>
                        <button type="button" class="btn btn-danger btn-sm" id="btn_cancelar_categoria"><i class="bi bi-x-circle"></i> Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!-- FIN DE CUERPO DE PAGINA -->
<script src="<?php echo BASE_URL; ?>view/function/categories.js"></script>
<script>
    // Agregar event listeners para los botones
    document.addEventListener('DOMContentLoaded', function() {
        // Botón Limpiar
        const btnLimpiar = document.getElementById('btn_limpiar_categoria');
        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', function() {
                document.getElementById('frm_categories').reset();
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
        const btnCancelar = document.getElementById('btn_cancelar_categoria');
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
                        window.location.href = base_url + 'categoria';
                    }
                });
            });
        }
    });
</script>
