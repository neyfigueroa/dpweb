//

// Helper to keep backward compatibility with older onclick names
function agregarAlCarrito(id) {
  // delegate to venta logic if available
  if (typeof agregar_producto_venta === "function") {
    agregar_producto_venta(id);
  } else if (typeof agregar_producto_temporal === "function") {
    agregar_producto_temporal(id);
  } else {
    console.warn("Función para agregar al carrito no disponible");
  }
}

function validar_form() {
  let formId = document.getElementById("frm_produc")
    ? "frm_produc"
    : "frm_edit_produc";
  let form = document.getElementById(formId);
  let codigo = document.getElementById("codigo").value;
  let nombre = document.getElementById("nombre").value;
  let detalle = document.getElementById("detalle").value;
  let precio = document.getElementById("precio").value;
  let stock = document.getElementById("stock").value;
  let fecha_vencimiento = document.getElementById("fecha_vencimiento").value;
  let imagen = document.getElementById("imagen").value;
  let id_categoria = document.getElementById("id_categoria").value;

  if (
    codigo == "" ||
    nombre == "" ||
    detalle == "" ||
    precio == "" ||
    stock == "" ||
    fecha_vencimiento == "" ||
    id_categoria == "" ||
    (imagen == "" && formId === "frm_produc")
  ) {
    Swal.fire({
      title: "ERROR",
      text: "¡Ups! Hay campos vacíos.",
      icon: "error",
    });
    return;
  }

  // Validaciones adicionales
  if (parseFloat(precio) < 0) {
    Swal.fire({
      title: "¡Error!",
      text: "El precio no puede ser negativo.",
      icon: "error",
    });
    return;
  }

  if (parseInt(stock) < 0) {
    Swal.fire({
      title: "¡Error!",
      text: "El stock no puede ser negativo.",
      icon: "error",
    });
    return;
  }

  if (parseInt(id_categoria) < 1) {
    Swal.fire({
      title: "¡Error!",
      text: "El ID de categoría debe ser un número positivo.",
      icon: "error",
    });
    return;
  }

  if (form.dataset.edit === "true") {
    actualizarProducto();
  } else {
    registrarProducto();
  }
}

if (document.querySelector("#frm_produc")) {
  let frm_produc = document.querySelector("#frm_produc");
  frm_produc.onsubmit = function (e) {
    e.preventDefault();
    validar_form();
  };
}

if (document.querySelector("#frm_edit_produc")) {
  let frm_edit_produc = document.querySelector("#frm_edit_produc");
  frm_edit_produc.onsubmit = function (e) {
    e.preventDefault();
    validar_form();
  };
}

async function registrarProducto() {
  try {
    const datos = new FormData(document.getElementById("frm_produc"));
    //enviar datos a controlador
    let respuesta = await fetch(
      base_url + "control/ProductoController.php?tipo=registrar",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    let json = await respuesta.json();
    if (json.status) {
      Swal.fire({
        title: json.msg,
        icon: "success",
        draggable: true,
      });
      document.getElementById("frm_produc").reset();
    } else {
      Swal.fire({
        title: json.msg,
        icon: "error",
        draggable: true,
      });
    }
  } catch (e) {
    console.error("Error al registrar producto:", e);
    Swal.fire({
      title: "Error",
      text: "Error al registrar: " + e.message,
      icon: "error",
    });
  }
}

async function actualizarProducto() {
  try {
    const datos = new FormData(document.getElementById("frm_edit_produc"));
    const idProducto = document.getElementById("id_producto").value;

    if (idProducto) {
      datos.append("id_producto", idProducto);
    } else {
      Swal.fire({
        title: "Error",
        text: "No se encontró el ID del producto.",
        icon: "error",
      });
      return;
    }

    for (let pair of datos.entries()) {
      console.log(`${pair[0]}: ${pair[1]}`);
    }

    let respuesta = await fetch(
      base_url + "control/ProductoController.php?tipo=actualizar_producto",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );

    let json = await respuesta.json();
    console.log("Respuesta de actualización:", json);
    if (json.status) {
      Swal.fire({
        title: json.msg,
        icon: "success",
        draggable: true,
      });
      location.href = base_url + "produc"; // Redirige si deseas
      view_productos();
    } else {
      Swal.fire({
        title: json.msg,
        icon: "error",
        draggable: true,
      });
    }
  } catch (e) {
    console.error("Error al actualizar producto:", e);
    Swal.fire({
      title: "Error",
      text: "Error al actualizar: " + e.message,
      icon: "error",
    });
  }
}

async function obtenerProductoPorId(id) {
  try {
    let respuesta = await fetch(
      base_url + "control/ProductoController.php?tipo=obtener_producto&id=" + id
    );
    let producto = await respuesta.json();
    console.log("Producto recibido:", producto); // Verifica en la consola
    if (producto) {
      document.getElementById("id_producto").value = producto.id || "";
      document.getElementById("codigo").value = producto.codigo || "";
      document.getElementById("nombre").value = producto.nombre || "";
      document.getElementById("detalle").value = producto.detalle || "";
      document.getElementById("precio").value = producto.precio || "";
      document.getElementById("stock").value = producto.stock || "";
      document.getElementById("fecha_vencimiento").value =
        producto.fecha_vencimiento || "";
      document.getElementById("id_categoria").value =
        producto.id_categoria || ""; // Asegurar asignación
      document.getElementById("id_persona").value = producto.id_proveedor || "";
      if (producto.imagen) {
        // Opcional: Mostrar imagen
      }
      document.getElementById("frm_edit_produc").dataset.edit = "true";
    } else {
      console.error("Producto no encontrado:", id);
      Swal.fire({
        title: "Error",
        text: "Producto no encontrado.",
        icon: "error",
      });
    }
  } catch (e) {
    console.error("Error al obtener producto por ID", e);
    Swal.fire({
      title: "Error",
      text: "No se pudo cargar el producto.",
      icon: "error",
    });
  }
}

async function view_productos() {
  try {
    let respuesta = await fetch(
      base_url + "control/ProductoController.php?tipo=ver_productos",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
      }
    );

    let json = await respuesta.json();
    console.log("Productos recibidos:", json); // Agrega esto para depurar
    let content_productos = document.getElementById("content_productos");
    if (content_productos) {
      content_productos.innerHTML = "";
      json.data.forEach((producto, index) => {
        let fila = document.createElement("tr");
        fila.classList.add("text-center");
        fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${producto.codigo || ""}</td>
                    <td>${producto.nombre || ""}</td>
                    <td>${producto.detalle || ""}</td>
                    <td>${producto.precio || ""}</td>
                    <td>${producto.stock || ""}</td>
                    <td><img src="${base_url}${
          producto.imagen || "view/img/default.jpg"
        }" alt="Imagen del producto" width="50" height="50" style="object-fit: cover;"></td>
                    <td>${producto.fecha_vencimiento || ""}</td>
                    <td>${producto.categoria || ""}</td>
                    <td><svg id="barcode${producto.id}"></svg></td>
                    <td>
                    <a href="${base_url}edit-producto/${
          producto.id
        }" class="btn btn-outline-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                    </a>
                    <a href="javascript:void(0)" onclick="eliminarProducto(${
                      producto.id
                    })" class="btn btn-outline-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                    </svg>
                    </a>
                    </td>
                    `;
        content_productos.appendChild(fila);
        JsBarcode("#barcode" + producto.id, "" + producto.codigo, {
          width: 2,
          height: 40,
        });
      });
    }
  } catch (e) {
    console.error("Error al ver productos:", e);
  }
}

if (document.getElementById("content_productos")) {
  view_productos();
}

if (document.getElementById("btn_guardar")) {
  document.getElementById("btn_guardar").addEventListener("click", function () {
    validar_form();
  });
}

async function eliminarProducto(id) {
  Swal.fire({
    title: "¿Estás seguro?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        let respuesta = await fetch(
          base_url +
            "control/ProductoController.php?tipo=eliminar_producto&id=" +
            id,
          {
            method: "POST",
            mode: "cors",
            cache: "no-cache",
          }
        );

        let json = await respuesta.json();
        if (json.status) {
          Swal.fire("Eliminado!", json.msg, "success");
          view_productos();
        } else {
          Swal.fire("Error!", json.msg, "error");
        }
      } catch (e) {
        console.error("Error al eliminar producto:", e);
        Swal.fire(
          "Error!",
          "Ocurrió un error al eliminar el producto.",
          "error"
        );
      }
    }
  });
}
// cargar categoria
async function cargarCategorias() {
  let r = await fetch(
    base_url + "control/CategoriaController.php?tipo=ver_categorias"
  );
  let j = await r.json();
  let h = '<option value="">Seleccione una categoría</option>';
  j.data.forEach((c) => (h += `<option value="${c.id}">${c.nombre}</option>`));
  document.getElementById("id_categoria").innerHTML = h;
}

// cargar proveedor
async function cargarProveedores() {
  let r = await fetch(
    base_url + "control/UsuarioController.php?tipo=ver_proveedores"
  );
  let j = await r.json();
  let h = '<option value="">Seleccione un proveedor</option>';
  j.data.forEach(
    (p) => (h += `<option value="${p.id}">${p.razon_social}</option>`)
  );
  document.getElementById("id_persona").innerHTML = h;
}

async function cargarProductosTienda() {
  try {
    const respuesta = await fetch(
      base_url + "control/ProductoController.php?tipo=ver_productos",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
      }
    );

    const productos = await respuesta.json(); // ya viene como array []
    const contenedor = document.getElementById("contenedor_productos");

    if (!productos || productos.length === 0) {
      contenedor.innerHTML =
        '<div class="col-12 text-center"><h4 class="text-muted">No hay productos disponibles</h4></div>';
      return;
    }

    let html = "";

    productos.data.forEach((p) => {
      const imagen = p.imagen
        ? base_url + p.imagen.replace("../", "")
        : "https://via.placeholder.com/300x200?text=Sin+Imagen";

      html += `
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="${imagen}" class="card-img-top" alt="${
        p.nombre
      }" style="height: 220px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary">${p.nombre}</h5>
                        <p class="card-text text-muted small"><strong>Código:</strong> ${
                          p.codigo || ""
                        }</p>
                        <p class="card-text text-muted small">${
                          p.detalle ?? "Sin descripción disponible"
                        }</p>
                        <p class="card-text text-success fw-bold fs-5">$${parseFloat(
                          p.precio
                        ).toFixed(2)}</p>
                        <p class="card-text"><span class="badge bg-secondary">${
                          p.categoria
                        }</span></p>
                        <div class="mt-auto d-flex gap-1">
                            <a href="${base_url}producto-detalle/${
        p.id
      }" class="btn btn-outline-primary btn-sm flex-fill">Ver Detalles</a>
                            <button onclick="agregar_producto_venta(${
                              p.id
                            })" class="btn btn-success btn-sm flex-fill">Carrito</button>
                        </div>
                    </div>
                </div>
            </div>`;
    });

    contenedor.innerHTML = html;
  } catch (error) {
    console.error(error);
    const contenedor = document.getElementById("contenedor_productos");
    if (contenedor) {
      contenedor.innerHTML =
        '<div class="text-center text-danger">Error al cargar los productos</div>';
    }
  }
}

if (document.getElementById("contenedor_productos")) {
  // Función para cargar productos en la lista de productos (produc.php)
  async function cargarProductosLista() {
    try {
      const respuesta = await fetch(
        base_url + "control/ProductoController.php?tipo=ver_productos",
        {
          method: "POST",
          mode: "cors",
          cache: "no-cache",
        }
      );

      const productos = await respuesta.json();
      let html = "";

      if (productos.data && productos.data.length > 0) {
        productos.data.forEach((p) => {
          const imagen = p.imagen
            ? base_url + p.imagen.replace("../", "")
            : "https://via.placeholder.com/50x50?text=Sin+Imagen";
          const barcodeId = "barcode-" + p.id;

          html += `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.codigo}</td>
                    <td>${p.nombre}</td>
                    <td>${p.detalle}</td>
                    <td>$${parseFloat(p.precio).toFixed(2)}</td>
                    <td>${p.stock}</td>
                    <td><img src="${imagen}" alt="${
            p.nombre
          }" width="50" height="50"></td>
                    <td>${p.fecha_vencimiento}</td>
                    <td>${p.categoria}</td>
                    <td><svg id="${barcodeId}"></svg></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editarProducto(${
                          p.id
                        })"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${
                          p.id
                        })"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
        });
      } else {
        html =
          '<tr><td colspan="11" class="text-center">No hay productos disponibles</td></tr>';
      }

      document.getElementById("content_productos").innerHTML = html;

      // Generar códigos de barras después de cargar la tabla
      productos.data.forEach((p) => {
        const barcodeId = "barcode-" + p.id;
        JsBarcode("#" + barcodeId, p.codigo, {
          format: "CODE128",
          width: 1,
          height: 30,
          displayValue: false,
        });
      });
    } catch (error) {
      console.error("Error al cargar productos:", error);
      document.getElementById("content_productos").innerHTML =
        '<tr><td colspan="11" class="text-center text-danger">Error al cargar productos</td></tr>';
    }
  }

  cargarProductosTienda();
}

async function lista_productos_venta() {
  let dato = document.getElementById("busqueda venta").value;
  const datos = new FormData();
  datos.append("dato", dato);

  try {
    let respuesta = await fetch(
      base_url + "control/ProductoController.php?tipo=buscar_productos_venta",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "dato=" + encodeURIComponent(busqueda),
      }
    );

    const json = await respuesta.json();
    console.log("Productos encontrados:", json);

    let html = "";
    if (json.status && json.data.length > 0) {
      json.data.forEach((p) => {
        const imagen = p.imagen ? base_url + p.imagen : "view/img/default.jpg";
        html += `
                <div class="card m-2 col-3">
                    <img src="${imagen}" alt="${
          p.nombre
        }" width="100%" height="150px" style="object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">${p.nombre}</h5>
                        <p class="card-text"><strong>Código:</strong> ${
                          p.codigo || ""
                        }</p>
                        <p class="card-text">${
                          p.detalle ?? "Sin descripción"
                        }</p>
                        <p class="card-text text-success fw-bold">$${parseFloat(
                          p.precio
                        ).toFixed(2)}</p>
                        <p class="card-text"><span class="badge bg-secondary">${
                          p.categoria
                        }</span></p>
                        <div class="d-flex gap-2">
                            <button onclick="agregarAlCarrito(${
                              p.id
                            })" class="btn btn-primary">Agregar</button>
                            <a href="${base_url}producto-detalle/${
          p.id
        }" class="btn btn-outline-secondary">Ver Detalles</a>
                        </div>
                    </div>
                </div>`;
      });
    } else {
      html =
        '<div class="col-12 text-center"><h4 class="text-muted">No se encontraron productos</h4></div>';
    }

    document.getElementById("productos_venta").innerHTML = html;
  } catch (error) {
    console.error("Error al buscar productos:", error);
    document.getElementById("productos_venta").innerHTML =
      '<div class="col-12 text-center text-danger">Error al buscar productos</div>';
  }
}
async function productos_ventas() {
  const busqueda = document.getElementById("busqueda_venta").value;
  try {
    const respuesta = await fetch(
      base_url + "control/ProductoController.php?tipo=buscar_productos_venta",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "dato=" + encodeURIComponent(busqueda),
      }
    );

    const json = await respuesta.json();
    console.log("Productos encontrados:", json);

    let html = "";
    if (json.status && json.data.length > 0) {
      json.data.forEach((p) => {
        const imagen = p.imagen ? base_url + p.imagen : "view/img/default.jpg";
        html += `
                <div class="card m-2 col-3">
                    <img src="${imagen}" alt="${
          p.nombre
        }" width="100%" height="150px" style="object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">${p.nombre}</h5>
                        <p class="card-text"><strong>Código:</strong> ${
                          p.codigo || ""
                        }</p>
                        <p class="card-text">${
                          p.detalle ?? "Sin descripción"
                        }</p>
                        <p class="card-text text-success fw-bold">$${parseFloat(
                          p.precio
                        ).toFixed(2)}</p>
                        <p class="card-text"><span class="badge bg-secondary">${
                          p.categoria
                        }</span></p>
                        <div class="d-flex gap-2">
                            <button onclick="agregarAlCarrito(${
                              p.id
                            })" class="btn btn-primary">Agregar</button>
                            <a href="${base_url}producto-detalle/${
          p.id
        }" class="btn btn-outline-secondary">Ver Detalles</a>
                        </div>
                    </div>
                </div>`;
      });
    } else {
      html =
        '<div class="col-12 text-center"><h4 class="text-muted">No se encontraron productos</h4></div>';
    }

    document.getElementById("productos_venta").innerHTML = html;
  } catch (error) {
    console.error("Error al buscar productos:", error);
    document.getElementById("productos_venta").innerHTML =
      '<div class="col-12 text-center text-danger">Error al buscar productos</div>';
  }
}

async function listar_productos_venta() {
  try {
    let dato = document.getElementById("busqueda_venta").value;
    const datos = new FormData();
    datos.append("dato", dato);
    let respuesta = await fetch(
      base_url + "control/ProductoController.php?tipo=buscar_productos_venta",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    json = await respuesta.json();
    contenidot = document.getElementById("productos_venta");
    if (json.status) {
      let cont = 1;
      contenidot.innerHTML = ``;
      let firstProduct = null;
      json.data.forEach((producto) => {
        if (!firstProduct) firstProduct = producto;
        let producto_list = ``;
        producto_list += `<div class="card m-2 col-12">
                                <img src="${
                                  base_url + producto.imagen
                                }" alt="" width="100%" height="150px" style="object-fit: cover;">
                                <p class="card-text"><strong>Código:</strong> ${
                                  producto.codigo || ""
                                }</p>
                                <p class="card-text">${producto.nombre}</p>
                                <p>Precio: S/. ${parseFloat(
                                  producto.precio
                                ).toFixed(2)}</p>
                                <p>Stock: ${producto.stock}</p>
                                <div class="d-flex gap-2">
                                  <button onclick="agregar_producto_temporal(${
                                    producto.id
                                  }, ${
          producto.precio
        }, 1)" class="btn btn-primary">Agregar</button>
                                  <a href="${base_url}producto-detalle/${
          producto.id
        }" class="btn btn-outline-secondary">Ver Detalles</a>
                                </div>
                            </div>`;

        let nueva_fila = document.createElement("div");
        nueva_fila.className = "div col-md-3 col-sm-6 col-xs-12";
        nueva_fila.innerHTML = producto_list;
        cont++;
        contenidot.appendChild(nueva_fila);
      });

      // asignar valores del primer producto para el uso por teclado (Enter)
      if (firstProduct) {
        const idInput = document.getElementById("id_producto_venta");
        const precioInput = document.getElementById("producto_precio_venta");
        const cantidadInput = document.getElementById(
          "producto_cantidad_venta"
        );
        if (idInput) idInput.value = firstProduct.id;
        if (precioInput) precioInput.value = firstProduct.precio;
        if (cantidadInput) cantidadInput.value = 1;
      }
    }
  } catch (e) {
    console.log("error en mostrar producto " + e);
  }
}
if (document.getElementById("productos_venta")) {
  listar_productos_venta();
}
