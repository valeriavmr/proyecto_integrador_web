let carrito = [];


/* ========================================
   AGREGAR PRODUCTO
======================================== */

function agregarProducto(id, nombre, precio) {

    let existe = carrito.find(p => p.id == id);

    if (existe) {

        existe.cantidad++;

    } else {

        carrito.push({
            id,
            nombre,
            precio: Number(precio),
            cantidad: 1
        });
    }

    renderCarrito();
}


/* ========================================
   RENDER CARRITO
======================================== */

function renderCarrito() {

    let tbody = document.querySelector("#tablaVenta tbody");

    if (!tbody) return;

    tbody.innerHTML = "";

    let subtotalGeneral = 0;

    carrito.forEach((item, index) => {

        let subtotal = item.precio * item.cantidad;

        subtotalGeneral += subtotal;

        tbody.innerHTML += `
            <tr>
                <td>${item.nombre}</td>

                <td>
                    <button class="btn btn-mini" onclick="restarCantidad(${index})">-</button>

                    <span style="margin:0 5px">
                        ${item.cantidad}
                    </span>

                    <button class="btn btn-mini" onclick="sumarCantidad(${index})">+</button>
                </td>

                <td>$${Number(item.precio).toLocaleString()}</td>

                <td>$${subtotal.toLocaleString()}</td>

                <td>
                    <button class="btn btn-danger btn-mini" onclick="eliminarProducto(${index})">
                        ❌
                    </button>
                </td>
            </tr>
        `;
    });

    let iva = subtotalGeneral * 0.21;

    let total = subtotalGeneral + iva;

    document.getElementById("subtotal").innerText = subtotalGeneral.toFixed(2);
    document.getElementById("iva").innerText = iva.toFixed(2);
    document.getElementById("total").innerText = total.toFixed(2);
}


/* ========================================
   CANTIDADES
======================================== */

function sumarCantidad(index) {

    carrito[index].cantidad++;

    renderCarrito();
}


function restarCantidad(index) {

    carrito[index].cantidad--;

    if (carrito[index].cantidad <= 0) {

        carrito.splice(index, 1);
    }

    renderCarrito();
}


function eliminarProducto(index) {

    carrito.splice(index, 1);

    renderCarrito();
}


/* ========================================
   GUARDAR VENTA
======================================== */

function guardarVenta() {

    if (carrito.length === 0) {

        Swal.fire({
            icon: "warning",
            title: "Carrito vacío"
        });

        return;
    }

    Swal.fire({
        title: "Confirmar venta",
        text: "¿Desea registrar la venta?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, vender",
        cancelButtonText: "Cancelar"
    }).then((result) => {

        if (result.isConfirmed) {

            procesarVenta();
        }
    });
}


function procesarVenta() {

    const total = document.getElementById("total").innerText;

    const idPersona = document.getElementById("idPersona")?.value || null;

    const idMascota = document.getElementById("mascotaSelect")?.value || null;

    fetch("guardar_venta.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify({
            carrito,
            total,
            id_cliente: idPersona,
            id_mascota: idMascota
        })
    })

    .then(async res => {

        let text = await res.text();

        console.log(text);

        return JSON.parse(text);
    })

    .then(data => {

        if (data.success) {

            Swal.fire({
                icon: "success",
                title: "Venta registrada",
                text: data.message || "La venta se registró correctamente",
                showDenyButton: true,
                confirmButtonText: "OK",
                denyButtonText: "🖨️ Imprimir Ticket"
            }).then((result) => {

                if (result.isDenied) {

                    imprimirTicket();

                } else {

                    location.reload();
                }
            });

        } else {

            Swal.fire({
                icon: "error",
                title: "Error",
                text: data.message || "No se pudo registrar la venta"
            });
        }
    })

    .catch(error => {

        console.error(error);

        Swal.fire({
            icon: "error",
            title: "Error de conexión"
        });
    });
}


/* ========================================
   BUSCAR PRODUCTOS
======================================== */

const inputBuscarProducto = document.getElementById("buscar");

if (inputBuscarProducto) {

    inputBuscarProducto.addEventListener("keyup", (e) => {

        buscarProductos(e.target.value);
    });
}


function buscarProductos(texto) {

    fetch("buscar_productos.php?q=" + encodeURIComponent(texto))

    .then(res => res.json())

    .then(data => {

        console.log("PRODUCTOS:", data);

        renderProductos(data);
    })

    .catch(error => {

        console.error("Error buscando productos:", error);
    });
}

function renderProductos(productos) {

    let tbody = document.getElementById("tbodyProductos");

    if (!tbody) return;

    tbody.innerHTML = "";

    productos.forEach(p => {

        const nombre = p.nombre || p.nombre_producto;

        const tipo = p.tipo;

        const stock = p.stock_actual ?? p.cantidad_actual_producto ?? 0;

        const precio = p.precio_venta ?? p.precio_unitario ?? 0;

        tbody.innerHTML += `
            <tr data-categoria="${tipo}">

                <td>${nombre}</td>

                <td>${tipo}</td>

                <td>${stock}</td>

                <td>$${Number(precio).toFixed(2)}</td>

                <td>
                    <button
                        class="btn"
                        onclick="agregarProducto(
                            ${p.id_producto},
                            '${String(nombre).replace(/'/g, "\\'")}',
                            ${precio}
                        )">
                        ➡️
                    </button>
                </td>

            </tr>
        `;
    });
}


/* ========================================
   BUSCAR CLIENTE
======================================== */

const inputCliente = document.getElementById("buscarCliente");

if (inputCliente) {

    inputCliente.addEventListener("keyup", (e) => {

        let texto = e.target.value;

        if (texto.length < 2) {

            document.getElementById("listaClientes").innerHTML = "";

            return;
        }

        fetch("buscar_cliente.php?q=" + encodeURIComponent(texto))

        .then(res => res.json())

        .then(data => {

            console.log("CLIENTES:", data);

            renderClientes(data);
        })

        .catch(error => {

            console.error("Error AJAX cliente:", error);
        });
    });
}


function renderClientes(clientes) {

    let lista = document.getElementById("listaClientes");

    if (!lista) return;

    lista.innerHTML = "";

    if (!clientes || clientes.length === 0) {

        lista.innerHTML = `
            <div class="item-cliente">
                Sin resultados
            </div>
        `;

        return;
    }

    clientes.forEach(c => {

        const nombreCompleto =
            `${c.nombre} ${c.apellido}`;

        lista.innerHTML += `
            <div
                class="item-cliente"
                onclick="seleccionarCliente(
                    ${c.id_persona},
                    '${nombreCompleto.replace(/'/g, "\\'")}'
                )">

                ${nombreCompleto}

            </div>
        `;
    });
}


function seleccionarCliente(id, nombreCompleto) {

    document.getElementById("buscarCliente").value = nombreCompleto;

    document.getElementById("idPersona").value = id;

    document.getElementById("ticketCliente").innerText = nombreCompleto;

    document.getElementById("listaClientes").innerHTML = "";

    cargarMascotas(id);
}


/* ========================================
   MASCOTAS
======================================== */

function cargarMascotas(idPersona) {

    fetch("ajax_mascotas.php?id_persona=" + encodeURIComponent(idPersona))

    .then(res => res.json())

    .then(data => {

        let select = document.getElementById("mascotaSelect");

        if (!select) return;

        select.innerHTML = `
            <option value="">
                🐾 Seleccionar mascota
            </option>
        `;

        data.forEach(m => {

            select.innerHTML += `
                <option value="${m.id_mascota}">
                    ${m.nombre}
                </option>
            `;
        });

        document.getElementById("ticketMascota").innerText = "-";
    })

    .catch(error => {

        console.error("Error cargando mascotas:", error);
    });
}


const mascotaSelect = document.getElementById("mascotaSelect");

if (mascotaSelect) {

    mascotaSelect.addEventListener("change", (e) => {

        let texto =
            e.target.options[e.target.selectedIndex].text;

        document.getElementById("ticketMascota").innerText =
            e.target.value ? texto : "-";
    });
}


/* ========================================
   FILTRO POR CATEGORÍA
======================================== */

const botonesFiltro = document.querySelectorAll(".filtro-btn");

botonesFiltro.forEach(btn => {

    btn.addEventListener("click", () => {

        botonesFiltro.forEach(b => {

            b.classList.remove("active");
        });

        btn.classList.add("active");

        const categoria = btn.dataset.categoria;

        const filas = document.querySelectorAll("#tbodyProductos tr");

        filas.forEach(fila => {

            if (categoria === "todos") {

                fila.style.display = "";

                return;
            }

            const categoriaFila = fila.dataset.categoria;

            fila.style.display =
                categoriaFila === categoria
                    ? ""
                    : "none";
        });
    });
});


/* ========================================
   NUEVO CLIENTE
======================================== */

function nuevoCliente() {

    window.location.href = "crear_usuario.php";
}


/* ========================================
   IMPRIMIR TICKET
======================================== */

function imprimirTicket() {

    renderCarrito();

    setTimeout(() => {

        window.print();

    }, 400);

    window.onafterprint = () => {

        setTimeout(() => {

            location.reload();

        }, 300);
    };
}


/* ========================================
   QR
======================================== */

function generarQR() {

    const qrContainer = document.getElementById("qrcode");

    if (!qrContainer) return;

    qrContainer.innerHTML = "";

    new QRCode(qrContainer, {
        text: "Tahito Veterinaria",
        width: 80,
        height: 80
    });
}


/* ========================================
   INICIAR
======================================== */

generarQR();