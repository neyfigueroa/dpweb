<div class="container">
   <div class="mt-3" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
    <div class="d-flex justify-content-between align-items-center">
    <h4 class="mb-0"><i class="bi bi-truck-fill"></i> Lista de Proveedores</h4>
          <a class="btn btn-custom d-flex align-items-center" href="<?= BASE_URL ?>new-proveedor">
     <i class="bi bi-plus-circle"></i> Nuevo Proveedor
  </a>

    </div>

 </div>
       <div class="d-flex justify-content-end mb-3">
</div>

<table class="table table-bordered border-primary table-striped">
    <thead class="table-dark">
        <tr class="text-center">
            <th><i class="bi bi-hash"></i> Nro</th>
            <th><i class="bi bi-card-text"></i> DNI</th>
            <th><i class="bi bi-person"></i> Nombres y Apellidos</th>
            <th><i class="bi bi-envelope"></i> Correo</th>
            <th><i class="bi bi-shield-check"></i> Rol</th>
            <th><i class="bi bi-toggle-on"></i> Estado</th>
            <th><i class="bi bi-gear"></i> Acciones</th>
        </tr>
    </thead>
    <tbody id="content_proveedores">

    </tbody>
</table>
</div>
<script src="<?= BASE_URL ?>view/function/proveedor.js"></script>
<!--script>view_users();</script-->

