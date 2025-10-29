<div class="container">
  <div class="mt-3" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
    <div class="d-flex justify-content-between align-items-center">
    <h4 class="mb-0"><i class="bi bi-tags-fill"></i> Lista de Categorías</h4>
          <a class="btn btn-custom d-flex align-items-center" href="<?= BASE_URL ?>categories">
     <i class="bi bi-plus-circle"></i> Nueva Categoría
  </a>

    </div>
 </div>
<div class="d-flex justify-content-end mb-3">
</div>
    <table class="table table-bordered border-primary table-striped">
        <thead class="table-dark">
            <tr class="text-center">
                <th><i class="bi bi-hash"></i> Nro</th>
                <th><i class="bi bi-tag"></i> Nombre</th>
                <th><i class="bi bi-info-circle"></i> Detalle</th>
                <th><i class="bi bi-gear"></i> Acciones</th>
            </tr>
        </thead>
        <tbody id="content_categories">

        </tbody>
    </table>
</div>

<script src="<?= BASE_URL ?>view/function/categories.js"></script>
<!--script>view_users();</script-->