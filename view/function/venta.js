// funciones de venta: agregar/listar/actualizar temporales
// agregar_producto_temporal puede ser invocado con (id, price, cant) o sin argumentos
async function agregar_producto_temporal(id_product = 0, price = 0, cant = 1) {
  let id, precio, cantidad;
  if (id_product == 0) {
    id = document.getElementById("id_producto_venta").value;
  } else {
    id = id_product;
  }
  if (price == 0) {
    precio = document.getElementById("producto_precio_venta").value;
  } else {
    precio = price;
  }
  if (cant == 0) {
    cantidad = document.getElementById("producto_cantidad_venta").value;
  } else {
    cantidad = cant;
  }

  const datos = new FormData();
  datos.append("id_producto", id);
  datos.append("precio", precio);
  datos.append("cantidad", cantidad);
  try {
    let respuesta = await fetch(
      base_url + "control/VentaController.php?tipo=registrarTemporal",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    const json = await respuesta.json();
    if (json.status) {
      if (json.msg == "registrado") {
        alert("el producto fue registrado");
      } else {
        alert("el producto fue actualizado");
      }
    }
    listar_temporales();
  } catch (error) {
    console.log("error en agregar producto temporal " + error);
  }
}

// Agrega producto a la venta usando su id (obtiene precio desde backend)
async function agregar_producto_venta(id_producto) {
  try {
    let r = await fetch(
      base_url +
        "control/ProductoController.php?tipo=obtener_producto&id=" +
        id_producto
    );
    let producto = await r.json();
    if (producto && producto.precio !== undefined) {
      await agregar_producto_temporal(id_producto, producto.precio, 1);
    } else {
      alert("No se pudo obtener el producto");
    }
  } catch (error) {
    console.log("error al agregar producto por id", error);
  }
}
async function listar_temporales() {
  try {
    let respuesta = await fetch(
      base_url + "control/VentaController.php?tipo=listar_venta_temporal",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
      }
    );
    const json = await respuesta.json();
    if (json.status) {
      let lista_temporal = "";
      json.data.forEach((t_venta) => {
        lista_temporal += `<tr>
                                    <td>${t_venta.nombre}</td>
                                    <td><input type="number" id="cant_${
                                      t_venta.id
                                    }" value="${
          t_venta.cantidad
        }" style="width: 60px;" onkeyup="actualizar_subtotal(${t_venta.id}, ${
          t_venta.precio
        });" onchange="actualizar_subtotal(${t_venta.id}, ${
          t_venta.precio
        });"></td>
                                    <td>S/. ${parseFloat(
                                      t_venta.precio
                                    ).toFixed(2)}</td>
                                    <td id="subtotal_${t_venta.id}">S/. ${(
          parseFloat(t_venta.cantidad) * parseFloat(t_venta.precio)
        ).toFixed(2)}</td>
                                    <td><button class="btn btn-danger btn-sm" onclick="eliminarTemporal(${
                                      t_venta.id
                                    })">Eliminar</button></td>
                                </tr>`;
      });
      document.getElementById("lista_compra").innerHTML = lista_temporal;
      act_subt_general();
    }
  } catch (error) {
    console.log("error al cargar productos temporales " + error);
  }
}

// Buscar producto por código (o texto) y agregarlo al carrito al presionar Enter
async function buscarProductoPorCodigoYAgregar() {
  const busqueda = document.getElementById("busqueda_venta").value.trim();
  if (!busqueda) return;
  try {
    const datos = new FormData();
    datos.append("dato", busqueda);
    let respuesta = await fetch(
      base_url + "control/ProductoController.php?tipo=buscar_productos_venta",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    const json = await respuesta.json();
    if (json.status && json.data.length > 0) {
      // Buscar coincidencia exacta por código (case-insensitive)
      let match = json.data.find(
        (p) =>
          p.codigo && String(p.codigo).toLowerCase() === busqueda.toLowerCase()
      );
      if (!match) {
        // si no hay coincidencia exacta, usar el primer resultado
        match = json.data[0];
      }
      if (match) {
        if (match.stock !== undefined && parseInt(match.stock) <= 0) {
          alert("Producto sin stock");
          return;
        }
        await agregar_producto_temporal(match.id, match.precio, 1);
        document.getElementById("busqueda_venta").value = "";
        listar_productos_venta();
      }
    } else {
      alert("Producto no encontrado");
    }
  } catch (error) {
    console.log("error al buscar y agregar por codigo", error);
  }
}
async function actualizar_subtotal(id, precio) {
  let cantidad = document.getElementById("cant_" + id).value;
  try {
    const datos = new FormData();
    datos.append("id", id);
    datos.append("cantidad", cantidad);
    let respuesta = await fetch(
      base_url + "control/VentaController.php?tipo=actualizar_cantidad",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    const json = await respuesta.json();
    if (json.status) {
      let subtotal = cantidad * precio;
      document.getElementById("subtotal_" + id).innerHTML =
        "S/. " + subtotal.toFixed(2);
      act_subt_general();
    }
  } catch (error) {
    console.log("error al actualizar cantidad : " + error);
  }
}

// Eliminar temporal por id
async function eliminarTemporal(id) {
  try {
    if (!confirm("¿Eliminar este item de la lista de compra?")) return;
    const datos = new FormData();
    datos.append("id", id);
    let respuesta = await fetch(
      base_url + "control/VentaController.php?tipo=eliminar_temporal",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    const json = await respuesta.json();
    if (json.status) {
      listar_temporales();
      act_subt_general();
      alert("Item eliminado");
    } else {
      alert(json.msg || "Error al eliminar");
    }
  } catch (error) {
    console.log("error al eliminar temporal", error);
  }
}

async function act_subt_general() {
  try {
    let respuesta = await fetch(
      base_url + "control/VentaController.php?tipo=listar_venta_temporal",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
      }
    );
    const json = await respuesta.json();
    if (json.status) {
      let subtotal_general = 0;
      json.data.forEach((t_venta) => {
        subtotal_general += parseFloat(t_venta.precio * t_venta.cantidad);
      });
      let igv = parseFloat(subtotal_general * 0.18).toFixed(2);
      let total = (parseFloat(subtotal_general) + parseFloat(igv)).toFixed(2);
      document.getElementById("subtotal_general").innerHTML =
        "S/. " + subtotal_general.toFixed(2);
      document.getElementById("igv_general").innerHTML = "S/. " + igv;
      document.getElementById("total").innerHTML = "S/. " + total;
    }
  } catch (error) {
    console.log("error al cargar productos temporales " + error);
  }
}

async function buscar_cliente_venta() {
  let dni = document.getElementById("cliente_dni").value;
  try {
    const datos = new FormData();
    datos.append("dni", dni);
    let respuesta = await fetch(
      base_url + "control/UsuarioController.php?tipo=buscar_por_dni",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    const json = await respuesta.json();
    if (json.status) {
      document.getElementById("cliente_nombre").value = json.data.razon_social;
      document.getElementById("id_cliente_venta").value = json.data.id;
    } else {
      alert(json.msg);
    }
  } catch (error) {
    console.log("error al buscar cliente por dni " + error);
  }
}

async function registrarVenta() {
  let id_cliente = document.getElementById("id_cliente_venta").value;
  let fecha_venta = document.getElementById("fecha_venta").value;
  if (id_cliente == "" || fecha_venta == "") {
    return alert("debe completar todos los campos");
  }

  try {
    const datos = new FormData();
    datos.append("id_cliente", id_cliente);
    datos.append("fecha_venta", fecha_venta);

    let respuesta = await fetch(
      base_url + "control/VentaController.php?tipo=registrar_venta",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    const json = await respuesta.json();
    if (json.status) {
      alert("venta registrada con exito");
      window.location.reload();
    } else {
      alert(json.msg);
    }
  } catch (error) {
    console.log("error al registrar venta " + error);
  }
}
