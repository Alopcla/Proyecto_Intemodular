<?php
session_start();
/**
 * Funcion para conectar a bdd
 * @return object conexion*/
function conectarDB()
{
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "prueba-pagos";

    $conexion = mysqli_connect($host, $user, $pass, $db);

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    return $conexion;
}
/**
 * Funcion que registra la compra de la entrada en bdd
 * @param array datos de tipo asociativo.
 */
function registrarCompra($datos)
{
    //VALIDACIÓN PREVIA ---
    // Verificamos que los campos obligatorios no estén vacíos
    if (empty($datos['dia']) || empty($datos['metod']) || empty($datos['nombre']) || empty($datos['tarjeta'])) {
        return ["status" => false, "msg" => "Por favor, rellena todos los campos obligatorios."];
    }

    // Validar que el número de tarjeta sea numérico
    if (!is_numeric($datos['tarjeta'])) {
        return ["status" => false, "msg" => "El número de tarjeta debe contener solo dígitos."];
    }

    $db = conectarDB();

    // DETERMINAR CLIENTE ---
    $id_cliente = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;

    // INSERCIÓN EN 'Entrada' ---
    $sqlEntrada = "INSERT INTO Entrada (numCliente, tipo, DiaVisita) VALUES (?, ?, ?)";
    $stmtEntrada = mysqli_prepare($db, $sqlEntrada);

    mysqli_stmt_bind_param($stmtEntrada, "iss", $id_cliente, $datos['metod'], $datos['dia']);
    $resEntrada = mysqli_stmt_execute($stmtEntrada);

    if (!$resEntrada) {
        return ["status" => false, "msg" => "Error al registrar la entrada: " . mysqli_error($db)];
    }

    // INSERCIÓN EN 'InfoPago' (Si el cliente lo requiere, esto lo añadiré mañana si me da tiempo) ---
    if (isset($datos['save'])) {
        $sqlPago = "INSERT INTO InfoPago (numCliente, tipo, numTarjeta, mCaducidad, aCaducidad, codigoSeguridad) 
                    VALUES (?, ?, ?, ?, ?, ?)";

        $stmtPago = mysqli_prepare($db, $sqlPago);

        // Convertimos a tipos correctos para bind_param
        $numTarjeta = $datos['tarjeta'];
        $mes = (int)$datos['mes'];
        $anio = (int)$datos['anio'];
        $cvv = (int)$datos['cvv'];

        mysqli_stmt_bind_param($stmtPago, "isiiii", $id_cliente, $datos['metod'], $numTarjeta, $mes, $anio, $cvv);
        mysqli_stmt_execute($stmtPago);
        mysqli_stmt_close($stmtPago);
    }

    mysqli_stmt_close($stmtEntrada);
    mysqli_close($db);

    return ["status" => true, "msg" => "¡Pago procesado con éxito!"];
}
