<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include("../crud/conexion.php");

header('Content-Type: application/json');


/*
========================================
RECIBIR JSON
========================================
*/

$data = json_decode(file_get_contents("php://input"), true);


/*
========================================
VALIDAR DATA
========================================
*/

if(!$data){

    echo json_encode([
        "success" => false,
        "message" => "No se recibieron datos"
    ]);

    exit;
}


/*
========================================
VALIDAR CARRITO
========================================
*/

if(empty($data['carrito'])){

    echo json_encode([
        "success" => false,
        "message" => "Carrito vacío"
    ]);

    exit;
}


$carrito = $data['carrito'];
$total = $data['total'];


try{

    /*
    ========================================
    INICIAR TRANSACCIÓN
    ========================================
    */

    mysqli_begin_transaction($conn);



    /*
    ========================================
    CREAR VENTA
    ========================================
    */

    $sqlVenta = "
        INSERT INTO ventas(total)
        VALUES(?)
    ";

    $stmtVenta =
        mysqli_prepare($conn, $sqlVenta);

    mysqli_stmt_bind_param(
        $stmtVenta,
        "d",
        $total
    );

    mysqli_stmt_execute($stmtVenta);

    $idVenta = mysqli_insert_id($conn);




    /*
    ========================================
    VALIDAR STOCK DE TODOS
    ========================================
    */

    $erroresStock = [];

    foreach($carrito as $item){

        $idProducto = $item['id'];
        $cantidad = $item['cantidad'];

        $sqlStock = "
            SELECT stock_actual, nombre
            FROM productos
            WHERE id_producto = ?
            AND activo = 1
        ";

        $stmtStock =
            mysqli_prepare($conn, $sqlStock);

        mysqli_stmt_bind_param(
            $stmtStock,
            "i",
            $idProducto
        );

        mysqli_stmt_execute($stmtStock);

        $resultStock =
            mysqli_stmt_get_result($stmtStock);

        $producto =
            mysqli_fetch_assoc($resultStock);


        /*
        ========================================
        PRODUCTO NO EXISTE
        ========================================
        */

        if(!$producto){

            $erroresStock[] =
                "Producto inexistente";
        }


        /*
        ========================================
        STOCK INSUFICIENTE
        ========================================
        */

        else if($producto['stock_actual'] < $cantidad){

            $erroresStock[] =

                $producto['nombre']

                . " → Disponible: "

                . $producto['stock_actual'];
        }
    }



    /*
    ========================================
    SI HAY ERRORES
    ========================================
    */

    if(!empty($erroresStock)){

        throw new Exception(

            "Stock insuficiente:\n\n"

            . implode("\n", $erroresStock)
        );
    }




    /*
    ========================================
    GUARDAR DETALLE + DESCONTAR
    ========================================
    */

    foreach($carrito as $item){

        $idProducto = $item['id'];
        $cantidad = $item['cantidad'];
        $precio = $item['precio'];

        $subtotal = $precio * $cantidad;



        /*
        ========================================
        INSERTAR DETALLE
        ========================================
        */

        $sqlDetalle = "
            INSERT INTO detalle_venta(

                id_venta,
                id_producto,
                cantidad,
                precio_unitario,
                subtotal

            )
            VALUES(?,?,?,?,?)
        ";

        $stmtDetalle =
            mysqli_prepare($conn, $sqlDetalle);

        mysqli_stmt_bind_param(
            $stmtDetalle,
            "iiidd",
            $idVenta,
            $idProducto,
            $cantidad,
            $precio,
            $subtotal
        );

        mysqli_stmt_execute($stmtDetalle);




        /*
        ========================================
        DESCONTAR STOCK
        ========================================
        */

        $sqlUpdate = "
            UPDATE productos
            SET stock_actual =
                stock_actual - ?
            WHERE id_producto = ?
        ";

        $stmtUpdate =
            mysqli_prepare($conn, $sqlUpdate);

        mysqli_stmt_bind_param(
            $stmtUpdate,
            "ii",
            $cantidad,
            $idProducto
        );

        mysqli_stmt_execute($stmtUpdate);
    }




    /*
    ========================================
    CONFIRMAR TRANSACCIÓN
    ========================================
    */

    mysqli_commit($conn);



    echo json_encode([
        "success" => true,
        "message" => "La venta se registró correctamente"
    ]);


}catch(Exception $e){

    /*
    ========================================
    ROLLBACK
    ========================================
    */

    mysqli_rollback($conn);


    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>