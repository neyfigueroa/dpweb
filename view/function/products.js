//
function validar_form() {
    let formId = document.getElementById("frm_produc") ? "frm_produc" : "frm_edit_produc";
    let form = document.getElementById(formId);
    let codigo = document.getElementById("codigo").value;
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;
    let precio = document.getElementById("precio").value;
    let stock = document.getElementById("stock").value;
    let fecha_vencimiento = document.getElementById("fecha_vencimiento").value;
    let imagen = document.getElementById("imagen").value;
    let id_categoria = document.getElementById("id_categoria").value;

    if (codigo == "" || nombre == "" || detalle == "" || precio == "" || stock == "" || fecha_vencimiento == "" || id_categoria == "" || (imagen == "" && formId === "frm_produc")) {
        Swal.fire({
            title: "ERROR",
            text: "¡Ups! Hay campos vacíos.",
            icon: "error"
        });
        return;
    }

    // Validaciones adicionales
    if (parseFloat(precio) < 0) {
        Swal.fire({
            title: "¡Error!",
            text: "El precio no puede ser negativo.",
            icon: "error"
        });
        return;
    }

    if (parseInt(stock) < 0) {
        Swal.fire({
            title: "¡Error!",
            text: "El stock no puede ser negativo.",
            icon: "error"
        });
        return;
    }

    if (parseInt(id_categoria) < 1) {
        Swal.fire({
            title: "¡Error!",
            text: "El ID de categoría debe ser un número positivo.",
            icon: "error"
        });
        return;
    }

    if (form.dataset.edit === "true") {
        actualizarProducto();
    } else {
        registrarProducto();
    }
}

if (document.querySelector('#frm_produc')) {
    let frm_produc = document.querySelector('#frm_produc');
    frm_produc.onsubmit = function (e) {
        e.preventDefault();
        validar_form();
    };
}

if (document.querySelector('#frm_edit_produc')) {
    let frm_edit_produc = document.querySelector('#frm_edit_produc');
    frm_edit_produc.onsubmit = function (e) {
        e.preventDefault();
        validar_form();
    };
}

async function registrarProducto() {
    try {
        const datos = new FormData(document.getElementById('frm_produc'));
        //enviar datos a controlador
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        if (json.status) {
            Swal.fire({
                title: json.msg,
                icon: "success",
                draggable: true
            });
            document.getElementById('frm_produc').reset();
        } else {
            Swal.fire({
                title: json.msg,
                icon: "error",
                draggable: true
            });
        }
    } catch (e) {
        console.error("Error al registrar producto:", e);
        Swal.fire({
            title: "Error",
            text: "Error al registrar: " + e.message,
            icon: "error"
        });
    }
}

async function actualizarProducto() {
    try {
        const datos = new FormData(document.getElementById('frm_edit_produc'));
        const idProducto = document.getElementById('id_producto').value;

        if (idProducto) {
            datos.append('id_producto', idProducto);
        } else {
            Swal.fire({
                title: "Error",
                text: "No se encontró el ID del producto.",
                icon: "error"
            });
            return;
        }

        for (let pair of datos.entries()) {
            console.log(`${pair[0]}: ${pair[1]}`);
        }

        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=actualizar_producto', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        console.log("Respuesta de actualización:", json);
        if (json.status) {
            Swal.fire({
                title: json.msg,
                icon: "success",
                draggable: true
            });
            location.href = base_url + 'produc';  // Redirige si deseas
            view_productos();
        } else {
            Swal.fire({
                title: json.msg,
                icon: "error",
                draggable: true
            });
        }
    } catch (e) {
        console.error("Error al actualizar producto:", e);
        Swal.fire({
            title: "Error",
            text: "Error al actualizar: " + e.message,
            icon: "error"
        });
    }
}

async function obtenerProductoPorId(id) {
    try {
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=obtener_producto&id=' + id);
        let producto = await respuesta.json();
        console.log("Producto recibido:", producto); // Verifica en la consola
        if (producto) {
            document.getElementById('id_producto').value = producto.id || '';
            document.getElementById('codigo').value = producto.codigo || '';
            document.getElementById('nombre').value = producto.nombre || '';
            document.getElementById('detalle').value = producto.detalle || '';
            document.getElementById('precio').value = producto.precio || '';
            document.getElementById('stock').value = producto.stock || '';
            document.getElementById('fecha_vencimiento').value = producto.fecha_vencimiento || '';
            document.getElementById('id_categoria').value = producto.id_categoria || ''; // Asegurar asignación
            document.getElementById('id_persona').value = producto.id_proveedor || '';
            if (producto.imagen) {
                // Opcional: Mostrar imagen
            }
            document.getElementById("frm_edit_produc").dataset.edit = "true";
        } else {
            console.error("Producto no encontrado:", id);
            Swal.fire({
                title: "Error",
                text: "Producto no encontrado.",
                icon: "error"
            });
        }
    } catch (e) {
        console.error("Error al obtener producto por ID", e);
        Swal.fire({
            title: "Error",
            text: "No se pudo cargar el producto.",
            icon: "error"
        });
    }
}

async function view_productos() {
    try {
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=ver_productos', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
        });

        let json = await respuesta.json();
        console.log("Productos recibidos:", json); // Agrega esto para depurar
        let content_productos = document.getElementById('content_productos');
        if (content_productos) {
            content_productos.innerHTML = '';
            json.forEach((producto, index) => {
                let fila = document.createElement('tr');
                fila.classList.add('text-center');
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${producto.codigo || ''}</td>
                    <td>${producto.nombre || ''}</td>
                    <td>${producto.detalle || ''}</td>
                    <td>${producto.precio || ''}</td>
                    <td>${producto.stock || ''}</td>
                    <td><img src="${base_url}${producto.imagen || 'view/img/default.jpg'}" alt="Imagen del producto" width="50" height="50" style="object-fit: cover;"></td>
                    <td>${producto.fecha_vencimiento || ''}</td>
                    <td>${producto.categoria || ''}</td>
                    <td>
                    <a href="${base_url}edit-producto/${producto.id}" class="btn btn-outline-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                    </a>
                    <a href="javascript:void(0)" onclick="eliminarProducto(${producto.id})" class="btn btn-outline-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                    </svg>
                    </a>
                    </td>
                    `;
                content_productos.appendChild(fila);
            });
        }
    } catch (e) {
        console.error("Error al ver productos:", e);
    }
}

if (document.getElementById('content_productos')) {
    view_productos();
}

if (document.getElementById('btn_guardar')) {
    document.getElementById('btn_guardar').addEventListener('click', function () {
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
        cancelButtonText: "Cancelar"
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=eliminar_producto&id=' + id, {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache'
                });

                let json = await respuesta.json();
                if (json.status) {
                    Swal.fire("Eliminado!", json.msg, "success");
                    view_productos();
                } else {
                    Swal.fire("Error!", json.msg, "error");
                }
            } catch (e) {
                console.error("Error al eliminar producto:", e);
                Swal.fire("Error!", "Ocurrió un error al eliminar el producto.", "error");
            }
        }
    });
}
// cargar categoria
async function cargarCategorias() {
  let r = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias');
  let j = await r.json();
  let h = '<option value="">Seleccione una categoría</option>';
  j.data.forEach(c => h += `<option value="${c.id}">${c.nombre}</option>`);
  document.getElementById("id_categoria").innerHTML = h;
}

// cargar proveedor
async function cargarProveedores() {
  let r = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_proveedores');
  let j = await r.json();
  let h = '<option value="">Seleccione un proveedor</option>';
  j.data.forEach(p => h += `<option value="${p.id}">${p.razon_social}</option>`);
  document.getElementById("id_persona").innerHTML = h;
}
