<?php include '../crud/conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Venta de Productos</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        body{
            font-family: Arial;
            background:#f5f5f5;
            margin:0;
        }

        .container{
            padding:20px;
        }

        .grid{
            display:grid;
            grid-template-columns: 60% 38%;
            gap:2%;
        }

        .card{
            background:white;
            padding:20px;
            border-radius:10px;
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        table th, table td{
            padding:10px;
            border-bottom:1px solid #ddd;
        }

        .btn{
            background:#198754;
            color:white;
            border:none;
            padding:10px 15px;
            border-radius:5px;
            cursor:pointer;
        }

        .btn-danger{
            background:red;
        }

        /*
        ========================================
        IMPRESIÓN
        ========================================
        */

    @media print{

    body{

        background:white;
        margin:0;
        padding:0;
    }

    /*
    OCULTAR TODO
    */

    .container > h1,
    .grid > div:first-child,
    #ticket button{

        display:none !important;
    }

    /*
    MOSTRAR SOLO TICKET
    */

    #ticket{

        width:100%;
        box-shadow:none;
        border:none;
        padding:20px;
    }

    table{

        width:100%;
        border-collapse:collapse;
    }

    table th,
    table td{

        border:1px solid #ccc;
        padding:8px;
    }

    h2,
    h3{

        margin-top:0;
    }
}


    </style>
</head>

<body>

<div class="container">

    <h1>Venta de Productos</h1>

    <div class="grid">

        <!-- PRODUCTOS -->
        <div class="card">

            <input type="text" id="buscar"
                   placeholder="Buscar producto">

            <br><br>

            <table>

                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id="tbodyProductos">

                <?php

                $sql = "SELECT * FROM productos
                        WHERE activo= 1";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){

                ?>
                    <tr>
                        <td><?= $row['nombre'] ?></td>
                        <td><?= $row['tipo'] ?></td>
                        <td><?= $row['stock_actual'] ?></td>
                        <td>$ <?= $row['precio_venta'] ?></td>
                        
                        <td>

                            <button class="btn"
                                onclick="agregarProducto(
                                <?= $row['id_producto'] ?>,
                                '<?= htmlspecialchars($row['nombre'], ENT_QUOTES) ?>',
                                <?= $row['precio_venta'] ?>
                                )">

                                ➡️
                            </button>

                        </td>

                    </tr>
                <?php } ?>

                </tbody>

            </table>

        </div>

        <!-- CARRITO -->
        <div class="card" id="ticket">

            <h2>Venta Actual</h2>

            <table id="tablaVenta">

                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cant</th>
                        <th>Precio</th>
                        <th>Sub</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>

            </table>

            <h3>Total: $ <span id="total">0</span></h3>

            <button class="btn"
                    onclick="guardarVenta()">

                Confirmar Venta

            </button>

            
        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="ventas.js"></script>

</body>
</html>