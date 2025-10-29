     function validar_form() {
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;
    if (nombre == "" || detalle == "") {
        Swal.fire({
            title: "ERROR?",
            text: "¡Ups! Hay campos vacíos.",
            icon: "question"
        });
        return;
    }
    registrarCategoria();
}

if (document.querySelector('#frm_categories')) {
    let frm_categories = document.querySelector('#frm_categories');
    frm_categories.onsubmit = function (e) {
        e.preventDefault();
        validar_form();
    }
}

async function registrarCategoria() {
    try {
        const datos = new FormData(frm_categories);
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=registrar', {
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
           
            document.getElementById('frm_categories').reset();
        } else {
            Swal.fire({
                title: json.msg,
                icon: "error",
                draggable: false
            });
      
        }
    } catch (e) {
        console.log("Error al registrar Categoría: " + e);
    }
}

//
async function actualizarCategoria() {
    try {
        const datos = new FormData(document.querySelector('#frm_edit_categories'));
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=actualizar_categoria', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        if (json.status) {
            Swal.fire("¡Éxito!", json.msg, "success");
            location.href = base_url + 'categoria';

        } else {
            Swal.fire("¡Error!", json.msg, "error");
        }
    } catch (e) {
        console.log("Error al actualizar categoría: ", e);
        Swal.fire("¡Error!", "Ocurrió un error al actualizar la categoría.", "error");
    }
}

async function obtenerCategoriaPorId(id) {
    try {
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=obtener_categoria&id=' + id);
        let categoria = await respuesta.json();
        document.getElementById('id_categoria').value = categoria.id || '';
        document.getElementById('nombre').value = categoria.nombre || '';
        document.getElementById('detalle').value = categoria.detalle || '';
    } catch (e) {
        console.error("Error al obtener categoría por ID", e);
        Swal.fire("¡Error!", "No se pudo cargar la categoría.", "error");
    }
}

async function view_categories() {
   try {
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias', {
            method: 'GET',
            mode: 'cors',
            cache: 'no-cache'
        });

        let json = await respuesta.json();
        let content_categories = document.getElementById('content_categories');
        content_categories.innerHTML = '';

        if (json.status) {
            json.data.forEach((categoria, index) => {
                let fila = document.createElement('tr');
                fila.classList.add('text-center');
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${categoria.nombre}</td>
                    <td>${categoria.detalle}</td>
                    <td>
                    <a href="${base_url}edit-categoria/${categoria.id}" class="btn btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                    </a>
                    <a href="javascript:void(0)" onclick="eliminarCategoria(${categoria.id})" class="btn btn-outline-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                        </svg>
                    </a>
                </td>
            `;
            content_categories.appendChild(fila);
       });
        } else {
            Swal.fire("¡Aviso!", json.msg || "No se encontraron categorías.", "warning");
        }
    } catch (e) {
        console.error("Error al ver Categoría: ", e);
        Swal.fire("¡Error!", "No se pudieron cargar las categorías.", "error");
    }
}

if (document.getElementById('content_categories')) {
    view_categories();
}

//
if (document.getElementById('btn_guardarCategoria')) {
    document.getElementById('btn_guardarCategoria').addEventListener('click', function () {
        actualizarCategoria(); // Llama a la función que hará el update
    });
}

async function eliminarCategoria(id) {
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
                let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=eliminar_categoria&id=' + id, {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache'
                });

                let json = await respuesta.json();
                if (json.status) {
                    Swal.fire("¡Eliminado!", json.msg, "success");
                    view_categories();
                } else {
                    Swal.fire("¡Error!", json.msg, "error");
                }
            } catch (e) {
                console.log("Error al eliminar categoría: ", e);
                Swal.fire("¡Error!", "Ocurrió un error al eliminar la categoría.", "error");
            }
        }
    });
}