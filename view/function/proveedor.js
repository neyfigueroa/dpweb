function validar_form(tipo) {
    let nro_documento = document.getElementById("nro_identidad").value;
    let razon_social = document.getElementById("razon_social").value;
    let telefono = document.getElementById("telefono").value;
    let correo = document.getElementById("correo").value;
    let departamento = document.getElementById("departamento").value;
    let provincia = document.getElementById("provincia").value;
    let distrito = document.getElementById("distrito").value;
    let cod_postal = document.getElementById("cod_postal").value;
    let direccion = document.getElementById("direccion").value;
    let rol = document.getElementById("rol").value;
    if (nro_documento == "" || razon_social == "" || telefono == "" || correo == "" || departamento == "" || provincia == "" || distrito == "" || cod_postal == "" || direccion == "" || rol == "") {
        Swal.fire({
            title: "Error, campos vacíos!",
            icon: "error",
            draggable: true
        });
        return false;
    }
    if (tipo == "nuevo") {
        registrarProveedor();
    }
    if (tipo == "actualizar") {
        actualizarProveedor();
    }
    return true;
}

if (document.querySelector('#frm_proveedor')) {
    let frm_proveedor = document.querySelector('#frm_proveedor');
    frm_proveedor.onsubmit = function (e) {
        e.preventDefault();
        validar_form("nuevo");
    }
}

async function registrarProveedor() {
    try {
        const datos = new FormData(frm_proveedor);
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=registrar', {
            method: 'POST',
            body: datos
        });
        let json = await respuesta.json();
        console.log("Respuesta de registrarProveedor:", json);
        if (json.status) {
            Swal.fire('Éxito', json.msg, 'success');
            document.getElementById('frm_proveedor').reset();
            view_proveedores();
        } else {
            Swal.fire('Error', json.msg, 'error');
        }
    } catch (e) {
        console.log("Error al registrar Proveedor: " + e);
    }
}

async function view_proveedores() {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_proveedores_detallados', {
            method: 'POST'
        });
        let json = await respuesta.json();
        console.log("Respuesta de ver_proveedores_detallados:", json);
        let content_proveedores = document.getElementById('content_proveedores');
        content_proveedores.innerHTML = '';

        const rolesMap = {
            'administrador': 'Administrador',
            'cliente': 'Cliente',
            'proveedor': 'Proveedor',
            'vendedor': 'Vendedor'
        };

        if (json.status && json.data) {
            json.data.forEach((proveedor, index) => {
                console.log("Proveedor:", proveedor);
                let fila = document.createElement('tr');
                fila.classList.add('text-center');
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${proveedor.nro_identidad || ''}</td>
                    <td>${proveedor.razon_social || ''}</td>
                    <td>${proveedor.correo || ''}</td>
                    <td>${rolesMap[proveedor.rol] || 'Desconocido'}</td>
                    <td>${proveedor.estado || 'Activo'}</td>
                    <td>
                        <a href="${base_url}edit-proveedor/${proveedor.id}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button onclick="eliminarProveedor(${proveedor.id})" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                `;
                content_proveedores.appendChild(fila);
            });
        } else {
            content_proveedores.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">${json.msg || 'No hay proveedores registrados'}</td>
                </tr>
            `;
        }
    } catch (e) {
        console.log("Error al ver Proveedores: " + e);
        document.getElementById('content_proveedores').innerHTML = `
            <tr>
                <td colspan="7" class="text-center">Error al cargar los proveedores</td>
            </tr>
        `;
    }
}

async function edit_proveedor(id) {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=obtener_usuario&id=' + id);
        let json = await respuesta.json();
        console.log("Respuesta de edit_proveedor:", json);
        if (!json.id) {
            Swal.fire('Error', json.msg || 'Proveedor no encontrado', 'error');
            return;
        }
        document.getElementById('id_persona').value = json.id;
        document.getElementById('nro_identidad').value = json.nro_identidad;
        document.getElementById('razon_social').value = json.razon_social;
        document.getElementById('telefono').value = json.telefono;
        document.getElementById('correo').value = json.correo;
        document.getElementById('departamento').value = json.departamento;
        document.getElementById('provincia').value = json.provincia;
        document.getElementById('distrito').value = json.distrito;
        document.getElementById('cod_postal').value = json.cod_postal;
        document.getElementById('direccion').value = json.direccion;
        document.getElementById('rol').value = json.rol;
        // No es un modal, así que no se necesita $('#modalEdit').modal('show');
    } catch (error) {
        console.log('Error al editar proveedor: ' + error);
        Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
    }
}

if (document.querySelector('#frm_edit_proveedor')) {
    let frm_user = document.querySelector('#frm_edit_proveedor');
    frm_user.onsubmit = function (e) {
        e.preventDefault();
        validar_form("actualizar");
    }
}

async function actualizarProveedor() {
    try {
        const datos = new FormData(frm_edit_proveedor);
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=actualizar_usuario', {
            method: 'POST',
            body: datos
        });
        let json = await respuesta.json();
        console.log("Respuesta de actualizarProveedor:", json);
        if (json.status) {
              await Swal.fire('Éxito', json.msg, 'success');
            window.location.href = base_url + 'proveedor'; // Redirigir a la lista
        } else {
            Swal.fire('Error', json.msg, 'error');
        }
    } catch (e) {
        console.log("Error al actualizar Proveedor: " + e);
    }
}

async function eliminarProveedor(id) {
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
                let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=eliminar_usuario&id=' + id);
                let json = await respuesta.json();
                console.log("Respuesta de eliminarProveedor:", json);
                if (json.status) {
                    Swal.fire("Eliminado!", json.msg, "success");
                    view_proveedores();
                } else {
                    Swal.fire("Error!", json.msg, "error");
                }
            } catch (e) {
                console.log("Error al eliminar proveedor: ", e);
            }
        }
    });
}

if (document.getElementById('content_proveedores')) {
    view_proveedores();
}

//
if (document.getElementById('btn_guardar_cambios')) {
    document.getElementById('btn_guardar_cambios').addEventListener('click', function () {
        actualizarUsuario(); // Llama a la función que hará el update
    });
}