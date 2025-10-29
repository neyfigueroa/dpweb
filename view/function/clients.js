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
            title: "Error campos vacios!",
            icon: "error",
            draggable: true
        });
        return false;
    }
    if (tipo == "nuevo") {
        registrarCliente();
    }
    if (tipo == "actualizar") {
        actualizarCliente();
    }
    return true;
}

if (document.querySelector('#frm_client')) {
    let frm_client = document.querySelector('#frm_client');
    frm_client.onsubmit = function (e) {
        e.preventDefault();
        validar_form("nuevo");
    }
}

async function registrarCliente() {
    try {
        const datos = new FormData(frm_client);
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=registrar', {
            method: 'POST',
            body: datos
        });
        let json = await respuesta.json();
        if (json.status) {
            Swal.fire('Éxito', json.msg, 'success');
            document.getElementById('frm_client').reset();
            view_clients();
        } else {
            Swal.fire('Error', json.msg, 'error');
        }
    } catch (e) {
        console.log("Error al registrar Cliente:" + e);
    }
}

async function view_clients() {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_clientes', {
            method: 'POST'
        });
        let json = await respuesta.json();
        let content_clients = document.getElementById('content_clients');
        content_clients.innerHTML = '';

        const rolesMap = {
            'administrador': 'Administrador',
            'cliente': 'Cliente',
            'proveedor': 'Proveedor',
            'vendedor': 'Vendedor'
        };

        if (json.status && json.data) {
            json.data.forEach((user, index) => {
                let fila = document.createElement('tr');
                fila.classList.add('text-center');
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${user.nro_identidad || ''}</td>
                    <td>${user.razon_social || ''}</td>
                    <td>${user.correo || ''}</td>
                    <td>${rolesMap[user.rol] || 'Desconocido'}</td>
                    <td>${user.estado || 'Activo'}</td>
                    <td>
                        <a href="${base_url}edit-client/${user.id}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button onclick="eliminarClients(${user.id})" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                `;
                content_clients.appendChild(fila);
            });
        } else {
            content_clients.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">${json.msg || 'No hay clientes registrados'}</td>
                </tr>
            `;
        }
    } catch (e) {
        console.log("Error al ver Clientes: " + e);
        document.getElementById('content_clients').innerHTML = `
            <tr>
                <td colspan="7" class="text-center">Error al cargar los clientes</td>
            </tr>
        `;
    }
}

async function edit_client(id) {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=obtener_usuario&id=' + id);
        let json = await respuesta.json();
        if (!json.id) {
            Swal.fire('Error', json.msg || 'Usuario no encontrado', 'error');
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
        console.log('Error al editar: ' + error);
        Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
    }
}

if (document.querySelector('#frm_edit_cliente')) {
    let frm_user = document.querySelector('#frm_edit_cliente');
    frm_user.onsubmit = function (e) {
        e.preventDefault();
        validar_form("actualizar");
    }
}

async function actualizarCliente() {
    const datos = new FormData(frm_edit_cliente);
    let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=actualizar_usuario', {
        method: 'POST',
        body: datos
    });
    let json = await respuesta.json();
    if (json.status) {
        await Swal.fire('Éxito', json.msg, 'success');
            window.location.href = base_url + 'cliente'; // Redirigir a la lista
    } else {
        Swal.fire('Error', json.msg, 'error');
    }
}

async function eliminarClients(id) {
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
                if (json.status) {
                    Swal.fire("Eliminado!", json.msg, "success");
                    view_clients();
                } else {
                    Swal.fire("Error!", json.msg, "error");
                }
            } catch (e) {
                console.log("Error al eliminar:", e);
            }
        }
    });
}

if (document.getElementById('content_clients')) {
    view_clients();
}

//
if (document.getElementById('btn_guardar_cambios')) {
    document.getElementById('btn_guardar_cambios').addEventListener('click', function () {
        actualizarUsuario(); // Llama a la función que hará el update
    });
}