<!-- INICIO DE CUERPO DE PAGINA -->
    <div class="container-fluid">
        <div class="card">
            <h5 class="card-header"><i class="bi bi-person-plus"></i> Registro de Cliente</h5>
            <form id="frm_client" action="" method="">
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="departamento" class="form-label"><i class="bi bi-geo-alt"></i> Departamento:</label>
                                <input type="text" class="form-control" id="departamento" name="departamento" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="provincia" class="form-label"><i class="bi bi-geo-alt"></i> Provincia:</label>
                                <input type="text" class="form-control" id="provincia" name="provincia" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="distrito" class="form-label"><i class="bi bi-geo-alt"></i> Distrito:</label>
                                <input type="text" class="form-control" id="distrito" name="distrito" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cod_postal" class="form-label"><i class="bi bi-mailbox"></i> Código postal:</label>
                                <input type="number" class="form-control" id="cod_postal" name="cod_postal" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="direccion" class="form-label"><i class="bi bi-house"></i> Dirección:</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rol" class="form-label"><i class="bi bi-shield-check"></i> Rol:</label>
                                <select class="form-control" name="rol" id="rol" required readonly>
                                    <option value="cliente" selected>Cliente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i> Registrar</button>
                        <button type="reset" class="btn btn-info btn-sm" id="btn_limpiar_cliente"><i class="bi bi-arrow-clockwise"></i> Limpiar</button>
                        <button type="button" class="btn btn-danger btn-sm" id="btn_cancelar_cliente"><i class="bi bi-x-circle"></i> Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!-- FIN DE CUERPO DE PAGINA -->
<script src="<?php echo BASE_URL; ?>view/function/clients.js"></script>
<script>
    // Agregar event listeners para los botones
    document.addEventListener('DOMContentLoaded', function() {
        // Botón Limpiar
        const btnLimpiar = document.getElementById('btn_limpiar_cliente');
        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', function() {
                document.getElementById('frm_client').reset();
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
        const btnCancelar = document.getElementById('btn_cancelar_cliente');
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
                        window.location.href = base_url + 'cliente';
                    }
                });
            });
        }
    });
</script>
