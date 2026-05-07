let carrito = [];


/*
========================================
AGREGAR PRODUCTO
========================================
*/

function agregarProducto(id, nombre, precio){

    let existe = carrito.find(p => p.id == id);

    if(existe){

        existe.cantidad++;

    }else{

        carrito.push({
            id,
            nombre,
            precio,
            cantidad:1
        });
    }

    renderCarrito();
}



/*
========================================
RENDER CARRITO
========================================
*/

function renderCarrito(){

    let tbody =
        document.querySelector("#tablaVenta tbody");

    tbody.innerHTML = "";

    let total = 0;

    carrito.forEach((item,index)=>{

        let subtotal =
            item.precio * item.cantidad;

        total += subtotal;

        tbody.innerHTML += `

            <tr>

                <td>
                    ${item.nombre}
                </td>

                <td>

                    <button onclick="restarCantidad(${index})">
                        -
                    </button>

                    ${item.cantidad}

                    <button onclick="sumarCantidad(${index})">
                        +
                    </button>

                </td>

                <td>
                    $${item.precio}
                </td>

                <td>
                    $${subtotal.toFixed(2)}
                </td>

                <td>

                    <button
                        class="btn-danger"
                        onclick="eliminarProducto(${index})">

                        ❌

                    </button>

                </td>

            </tr>
        `;
    });

    document.getElementById("total")
        .innerText = total.toFixed(2);
}



/*
========================================
SUMAR CANTIDAD
========================================
*/

function sumarCantidad(index){

    carrito[index].cantidad++;

    renderCarrito();
}



/*
========================================
RESTAR CANTIDAD
========================================
*/

function restarCantidad(index){

    carrito[index].cantidad--;

    if(carrito[index].cantidad <= 0){

        carrito.splice(index,1);
    }

    renderCarrito();
}



/*
========================================
ELIMINAR PRODUCTO
========================================
*/

function eliminarProducto(index){

    carrito.splice(index,1);

    renderCarrito();
}



/*
========================================
GUARDAR VENTA
========================================
*/

function guardarVenta(){

    // VALIDAR CARRITO
    if(carrito.length === 0){

        Swal.fire({
            icon:'warning',
            title:'Carrito vacío'
        });

        return;
    }


    Swal.fire({

        title: 'Confirmar venta',

        text: '¿Desea registrar la venta?',

        icon: 'question',

        showCancelButton: true,

        confirmButtonText: 'Sí, vender',

        cancelButtonText: 'Cancelar'

    }).then((result)=>{

        if(result.isConfirmed){

            procesarVenta();
        }
    });
}



/*
========================================
PROCESAR VENTA
========================================
*/

function procesarVenta(){

    let total =
        document.getElementById("total")
        .innerText;

    fetch("guardar_venta.php",{

        method:"POST",

        headers:{
            "Content-Type":"application/json"
        },

        body:JSON.stringify({
            carrito,
            total
        })

    })

    .then(async res => {

        let text = await res.text();

        console.log(text);

        return JSON.parse(text);
    })

    .then(data => {

        // ÉXITO
        if(data.success){

            Swal.fire({

                icon:'success',

                title:'Venta registrada',

                text:data.message,

                showDenyButton:true,

                confirmButtonText:'OK',

                denyButtonText:'🖨️ Imprimir Ticket'

            }).then((result)=>{

                // IMPRIMIR
                if(result.isDenied){

                    Swal.close();

                    setTimeout(()=>{

                        imprimirTicket();

                    },300);

                }else{

                    carrito = [];

                    renderCarrito();
                }
            });

        }else{

            Swal.fire({

                icon:'error',

                title:'Error',

                text:data.message
            });
        }
    })

    .catch(error => {

        console.error(error);

        Swal.fire({

            icon:'error',

            title:'Error de conexión'
        });
    });
}



/*
========================================
BUSCADOR AJAX
========================================
*/

const buscador =
    document.getElementById("buscar");


buscador.addEventListener("keyup", ()=>{

    let texto =
        buscador.value;

    buscarProductos(texto);
});



function buscarProductos(texto){

    fetch(

        "buscar_productos.php?q="

        + encodeURIComponent(texto)

    )

    .then(res=>res.json())

    .then(data=>{

        renderProductos(data);
    })

    .catch(error=>{

        console.error(error);
    });
}



/*
========================================
RENDER PRODUCTOS AJAX
========================================
*/

function renderProductos(productos){

    let tbody =
        document.getElementById("tbodyProductos");

    tbody.innerHTML = "";


    productos.forEach(producto=>{

        tbody.innerHTML += `

            <tr>

                <td>
                    ${producto.nombre}
                </td>

                <td>
                    ${producto.tipo}
                </td>

                <td>
                    ${producto.stock_actual}
                </td>

                <td>
                    $${producto.precio_venta}
                </td>

                <td>

                    <button
                        class="btn"

                        onclick="agregarProducto(

                            ${producto.id_producto},

                            '${producto.nombre.replace(/'/g, "\\'")}',

                            ${producto.precio_venta}

                        )">

                        ➡️

                    </button>

                </td>

            </tr>
        `;
    });
}



/*
========================================
IMPRIMIR TICKET
========================================
*/

function imprimirTicket(){

    window.print();

    window.onafterprint = () => {

        carrito = [];

        renderCarrito();
    };
}