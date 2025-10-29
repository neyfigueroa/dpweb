<!-- INICIO DE CUERPO DE PAGINA -->
<div class="container-fluid">
    <div class="card">
        <h5 class="card-header"><i class="bi bi-pencil-square"></i> Actualizar Categoría</h5>
        <form id="frm_edit_categories">
            <input type="hidden" id="id_categoria" name="id_categoria" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
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
                    <button type="submit" class="btn btn-success btn-sm" id="btn_guardarCategoria"><i class="bi bi-check-circle"></i> Actualizar</button>
                    <a href="<?php echo BASE_URL; ?>categoria" class="btn btn-danger btn-sm"><i class="bi bi-x-circle"></i> Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- FIN DE CUERPO DE PAGINA -->
<script src="<?php echo BASE_URL; ?>view/function/categories.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let partes = window.location.pathname.split('/');
    let id = partes[partes.length - 1];

    if (!isNaN(id)) {
        obtenerCategoriaPorId(id);
    }
});
</script>
