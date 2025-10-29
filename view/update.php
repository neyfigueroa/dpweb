<!-- INICIO DE CUERPO DE PAGINA -->
    <div class="container-fluid">
        <div class="card">
            <h5 class="card-header"><i class="bi bi-pencil-square"></i> Actualizar Usuario</h5>
            <form id="frm_user" action="" method="">
                <input type="hidden" id="id_persona" name="id_persona">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nro_identidad" class="form-label"><i class="bi bi-card-text"></i> Nro de Documento:</label>
                                <input type="number" class="form-control" id="nro_identidad" name="nro_identidad" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="razon_social" class="form-label"><i class="bi bi-building"></i> Razón social:</label>
                                <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label"><i class="bi bi-telephone"></i> Teléfono:</label>
                                <input type="number" class="form-control" id="telefono" name="telefono" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="correo" class="form-label"><i class="bi bi-envelope"></i> Correo:</label>
                                <input type="email" class="form-control" id="correo" name="correo" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="departamento" class="form-label"><i class="bi bi-geo-alt"></i> Departamento:</label>
                                <input type="text" class="form-control" id="departamento" name="departamento" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="provincia" class="form-label"><i class="bi bi-geo-alt"></i> Provincia:</label>
                                <input type="text" class="form-control" id="provincia" name="provincia" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="distrito" class="form-label"><i class="bi bi-geo-alt"></i> Distrito:</label>
                                <input type="text" class="form-control" id="distrito" name="distrito" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cod_postal" class="form-label"><i class="bi bi-mailbox"></i> Código postal:</label>
                                <input type="number" class="form-control" id="cod_postal" name="cod_postal" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="direccion" class="form-label"><i class="bi bi-house"></i> Dirección:</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rol" class="form-label"><i class="bi bi-shield-check"></i> Rol:</label>
                                <select class="form-control" name="rol" id="rol" required>
                                    <option disabled selected>Seleccionar rol</option>
                                    <option value="administrador">Administrador</option>
                                    <option value="cliente">Cliente</option>
                                    <option value="proveedor">Proveedor</option>
                                    <option value="vendedor">Vendedor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-success btn-sm" id="btn_guardar_cambios"><i class="bi bi-check-circle"></i> Guardar Cambios</button>
                        <a href="<?php echo BASE_URL; ?>users" class="btn btn-danger btn-sm"><i class="bi bi-x-circle"></i> Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!-- FIN DE CUERPO DE PAGINA -->


 <script src="<?php echo BASE_URL; ?>view/function/user.js"></script>

<script>

document.addEventListener('DOMContentLoaded', () => {
    let partes = window.location.pathname.split('/');
    let id = partes[partes.length - 1];

    if (!isNaN(id)) {
        obtenerUsuarioPorId(id); // Cargar los datos si estamos editando
    }
});
</script>
