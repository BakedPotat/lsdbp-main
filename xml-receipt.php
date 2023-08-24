<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos 
$servername = "192.168.43.246";
$username = "lsdbp";
$password = "Coope2022";
$dbname = "db_users";




// Crear una conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];

// Consulta para obtener los datos del usuario desde la base de datos
$sql = "SELECT username, cedula, telefono, correo FROM usuarios WHERE id = '$user_id'";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Datos del usuario obtenidos de la base de datos
    $nombreReceptor = $row["username"];
    $numeroCedulaReceptor = $row["cedula"];
    $numTelefonoReceptor = $row["telefono"];
    $correoElectronicoReceptor = $row["correo"];
} else {
    echo "No se encontraron datos del usuario en la base de datos.";
    exit();
}

// Obtener la hora del servidor
$server_time = date("Y-m-d\TH:i:s");


// Generar un número aleatorio único
$uniqueNumber = uniqid();

// Formatear la hora del servidor
$serverTimeFormatted = date("His");

// Crear el código de venta
$codigoVenta = "FAC-" . $serverTimeFormatted . "-" . $nombreReceptor . "-" . $uniqueNumber;


// Construir el arreglo con los datos de la factura
$facturaData = array(
    "FacturaElectronica" => array(
        "CodigoVenta" => $codigoVenta,
        "FechaEmision" => $server_time,
        "CedulaEmisor" => "ABC117200436",
        "Receptor" => array(
            "NombreReceptor" => $nombreReceptor,
            "TipoIdentificacionReceptor" => "01",
            "NumeroCedulaReceptor" =>  $numeroCedulaReceptor,
            "NumTelefonoReceptor" =>  $numTelefonoReceptor,
            "CorreoElectronicoReceptor" => $correoElectronicoReceptor
        ),
        "MedioPago" => "02",
        "DetalleServicio" => array(
            "LineaDetalle" => array(
                array(
                    "NumeroLinea" => 1,
                    "Codigo" => "Membership",
                    "Cantidad" => 1,
                    "Detalle" => "1-month Membership",
                    "PrecioUnitario" => 10.00000,
                    "MontoTotal" => 10.00000,
                    "Descuento" => array(
                        "MontoDescuento" => 10.00000,
                        "NaturalezaDescuento" => "Porque me cae bien"
                    ),
                    "SubTotal" => 10.00000,
                    "Impuesto" => array(
                        "Codigo" => "01",
                        "CodigoTarifa" => "08",
                        "Tarifa" => 0.00,
                        "Monto" => 0.00000
                    ),
                    "ImpuestoNeto" => 0.00000,
                    "MontoTotalLinea" => 0.00000
                )
            )
        ),
        "ResumenFactura" => array(
            "CodigoTipoMoneda" => array(
                "CodigoMoneda" => "USD",
                "TipoCambio" => 1.00000
            ),
            "TotalVenta" => 10.00000,
            "TotalDescuentos" => 0.00000,
            "TotalVentaNeta" => 10.00000,
            "TotalImpuesto" => 10.00000,
            "TotalComprobante" => 10.00000
        )
    )
);


// Convertir el arreglo a una cadena JSON
$jsonDataString = json_encode($facturaData, JSON_PRETTY_PRINT);

$apiUrl = "https://jellyfish-app-s4g2w.ondigitalocean.app/factura";


$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataString); // Enviar la cadena JSON en el cuerpo de la solicitud
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonDataString),
    'Accept: application/xml' // Solicitar respuesta en formato XML
));





$response = curl_exec($ch);
curl_close($ch);



// Guardar el XML en la base de datos
$xmlContent = $response;

$sqlGuardarXML = "INSERT INTO xml_registros (user_id, codigo_venta_unico, xml_content) VALUES ('$user_id', '$codigoVenta', '$xmlContent')";

/*
if ($conn->query($sqlGuardarXML) === TRUE) {
    echo "El XML se ha guardado en la base de datos.";
} else {
    echo "Error al guardar el XML en la base de datos: " . $conn->error;
}
*/



// Generar un archivo XML y ofrecerlo para descarga
$xmlFilename = "factura.xml";
header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="' . $xmlFilename . '"');
echo $response;
exit; // Detener la ejecución del resto de la página


$conn->close();
?>

