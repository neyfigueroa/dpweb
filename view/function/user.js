function validar_form() {
    // Captura los valores de los campos del formulario
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
    // Verifica si alguno de los campos está vacío
    if (nro_documento == "" || razon_social == "" || telefono == "" || correo == "" || departamento == "" || provincia == "" || distrito == "" || cod_postal == "" || direccion == "" || rol == "") {

        Swal.fire({
            title: "ERROR?",
            text: "¡Ups! Hay campos vacíos.",
            icon: "question"
        });

        return; // Detiene la función para no enviar el formulario
    }


    // Si todos los campos están llenos, llama a la función que registra el usuario
    registrarUsuario();
}
// Se verifica si existe en el documento un elemento con el id frm_user.
if (document.querySelector('#frm_user')) {
    // Se guarda una referencia al formulario con id frm_user en una variable llamada frm_user.
    let frm_user = document.querySelector('#frm_user');
    frm_user.onsubmit = function (e) {   //Se asigna una función al evento onsubmit del formulario.
        e.preventDefault();   //Este método evita que el formulario se envíe de forma tradicional (recargando la página).
        validar_form(); //Llama a la función
    }
}

async function registrarUsuario() {
    try {
        //capturar campos de formulario (HTML)
        const datos = new FormData(frm_user);
        //enviar datos a controlador
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        // Convierte la respuesta a formato JSON
        let json = await respuesta.json();
        //validamos que json.status sea = true
        if (json.status) {
            Swal.fire({
                icon: 'success',
                title: 'Usuario registrado',
                text: json.msg,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = base_url + 'users';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: json.msg
            });
        }
    } catch (e) {
        console.log("Error al registrar Usuario:" + e);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor.'
        });
    }

}
//

async function actualizarUsuario() {
    try {
        const datos = new FormData(frm_user);
        console.log([...datos]);
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=actualizar_usuario', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        if (json.status) {
            Swal.fire({
                icon: 'success',
                title: 'Usuario actualizado',
                text: json.msg,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = base_url + 'users';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: json.msg
            });
        }
    } catch (e) {
        console.log("Error al actualizar usuario:", e);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor.'
        });
    }
}



async function iniciar_sesion() {
    // Captura los valores del input usuario y contraseña
    let usuario = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    if (usuario == "" || password == "") {
        //alert("Error, campos vacios!")
        Swal.fire({
            icon: "error",
            title: "Error, campos vacios!"

        });
        return;
    }
    try {
        // Captura los datos del formulario de login
        const datos = new FormData(frm_login);
        // Envía los datos al backend para validar
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=iniciar_sesion', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        // -----------------------------------
        let json = await respuesta.json();
        //validamos que json.status sea = true
        if (json.status) { //true
            location.replace(base_url + 'new-user');
        } else {
            alert(json.msg);
        }
    } catch (error) {
        console.log(error);
    }
}

//
async function obtenerUsuarioPorId(id) {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=obtener_usuario&id=' + id);
        let usuario = await respuesta.json();
        document.getElementById('id_persona').value = usuario.id || '';
        document.getElementById('nro_identidad').value = usuario.nro_identidad || '';
        document.getElementById('razon_social').value = usuario.razon_social || '';
        document.getElementById('telefono').value = usuario.telefono || '';
        document.getElementById('correo').value = usuario.correo || '';
        document.getElementById('departamento').value = usuario.departamento || '';
        document.getElementById('provincia').value = usuario.provincia || '';
        document.getElementById('distrito').value = usuario.distrito || '';
        document.getElementById('cod_postal').value = usuario.cod_postal || '';
        document.getElementById('direccion').value = usuario.direccion || '';
        document.getElementById('rol').value = usuario.rol || '';//  aqui agregue
    } catch (e) {
        console.error("Error al obtener usuario por ID", e); //  AHORA ESTÁ BIEN
    }
}


async function view_users() {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_usuarios', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
        });

        let json = await respuesta.json();
        let content_users = document.getElementById('content_users');
        content_users.innerHTML = '';

        // Mapeo de roles a texto
        const rolesMap = {
            'administrador': 'Administrador',
            'cliente': 'Cliente',
            'proveedor': 'Proveedor',
            'vendedor': 'Vendedor'
        };


        json.forEach((user, index) => {
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
                       <a href="` + base_url + `update/` + user.id + `" class="btn btn-outline-primary">
                   <i class="bi bi-pencil-square"></i></a>
                    <a href="javascript:void(0)" onclick="eliminarUsuario(${user.id})" class="btn btn-outline-danger">
                      <i class="bi bi-trash3"></i></a>
                </td>


            `;
            content_users.appendChild(fila);
        });

    } catch (e) {
        console.log("Error al ver Usuario: " + e);
    }
}

if (document.getElementById('content_users')) {
    view_users();
}

//
if (document.getElementById('btn_guardar_cambios')) {
    document.getElementById('btn_guardar_cambios').addEventListener('click', function () {
        actualizarUsuario(); // Llama a la función que hará el update
    });
}

// Agregar event listeners para los botones
document.addEventListener('DOMContentLoaded', function() {
    // Botón Limpiar
    const btnLimpiar = document.getElementById('btn_limpiar');
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function() {
            document.getElementById('frm_user').reset();
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
    const btnCancelar = document.getElementById('btn_cancelar');
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
                    window.location.href = base_url + 'users';
                }
            });
        });
    }
});

// eliminar usuario
async function eliminarUsuario(id) {
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
                let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=eliminar_usuario&id=' + id, {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache'
                });

                let json = await respuesta.json();
                if (json.status) {
                    Swal.fire("Eliminado!", json.msg, "success");
                    view_users(); //  Recarga la tabla
                } else {
                    Swal.fire("Error!", json.msg, "error");
                }
            } catch (e) {
                console.log("Error al eliminar usuario:", e);
            }
        }
    });
}
