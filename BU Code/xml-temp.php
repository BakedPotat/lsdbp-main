<!DOCTYPE html>
<html>
<head>
    <title>Enviar JSON a API y Descargar XML</title>
</head>
<body>
    <form method="post">
        <button type="submit" name="enviarBtn">Enviar JSON</button>
    </form>

    <?php
    if (isset($_POST['enviarBtn'])) {
        $jsonData = '{
          "FacturaElectronica": {
            "CodigoVenta": "123456789456",
            "FechaEmision": "2023-07-17T21:48:00",
            "CedulaEmisor": "123456789",
            "Receptor": {
              "NombreReceptor": "leito Perez",
              "TipoIdentificacionReceptor": "01",
              "NumeroCedulaReceptor": "2013457821",
              "NumTelefonoReceptor": "87549865",
              "CorreoElectronicoReceptor": "pperez@gmail.com"
            },
            "MedioPago": "02",
            "DetalleServicio": {
              "LineaDetalle": [
                {
                  "NumeroLinea": 1,
                  "Codigo": "AKA87",
                  "Cantidad": 2,
                  "Detalle": "Fichas VIP",
                  "PrecioUnitario": 2400.00000,
                  "MontoTotal": 4800.00000,
                  "Descuento": {
                    "MontoDescuento": 500.00000,
                    "NaturalezaDescuento": "Porque me cae bien"
                  },
                  "SubTotal": 4300.00000,
                  "Impuesto": {
                    "Codigo": "01",
                    "CodigoTarifa": "08",
                    "Tarifa": 13.00,
                    "Monto": 546.00000
                  },
                  "ImpuestoNeto": 546.00000,
                  "MontoTotalLinea": 4846.00000
                }
              ]
            },
            "ResumenFactura": {
              "CodigoTipoMoneda": {
                "CodigoMoneda": "CRC",
                "TipoCambio": 1.00000
              },
              "TotalVenta": 4800.00000,
              "TotalDescuentos": 500.00000,
              "TotalVentaNeta": 4800.00000,
              "TotalImpuesto": 546.00000,
              "TotalComprobante": 4800.00000
            }
          }
        }';

        $apiUrl = "https://jellyfish-app-s4g2w.ondigitalocean.app/factura";

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
            'Accept: application/xml' // Solicitar respuesta en formato XML
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        // Generar un archivo XML y ofrecerlo para descarga
        $xmlFilename = "respuesta_api.xml";
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="' . $xmlFilename . '"');
        echo $response;
        exit; // Detener la ejecución del resto de la página
    }
    ?>
</body>
</html>





<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!-- Con op de descarga -->

<!DOCTYPE html>
<html>
<head>
    <title>Factura Electrónica</title>
</head>
<body>
    <?php
    $jsonData = '{
      "FacturaElectronica": {
        "CodigoVenta": "123456789456",
        "FechaEmision": "2023-07-17T21:48:00",
        "CedulaEmisor": "ABC117200436",
        "Receptor": {
          "NombreReceptor": "leito Perez",
          "TipoIdentificacionReceptor": "01",
          "NumeroCedulaReceptor": "2013457821",
          "NumTelefonoReceptor": "87549865",
          "CorreoElectronicoReceptor": "pperez@gmail.com"
        },
        "MedioPago": "02",
        "DetalleServicio": {
          "LineaDetalle": [
            {
              "NumeroLinea": 1,
              "Codigo": "AKA87",
              "Cantidad": 2,
              "Detalle": "Fichas VIP",
              "PrecioUnitario": 2400.00000,
              "MontoTotal": 4800.00000,
              "Descuento": {
                "MontoDescuento": 500.00000,
                "NaturalezaDescuento": "Porque me cae bien"
              },
              "SubTotal": 4300.00000,
              "Impuesto": {
                "Codigo": "01",
                "CodigoTarifa": "08",
                "Tarifa": 13.00,
                "Monto": 546.00000
              },
              "ImpuestoNeto": 546.00000,
              "MontoTotalLinea": 4846.00000
            }
          ]
        },
        "ResumenFactura": {
          "CodigoTipoMoneda": {
            "CodigoMoneda": "CRC",
            "TipoCambio": 1.00000
          },
          "TotalVenta": 4800.00000,
          "TotalDescuentos": 500.00000,
          "TotalVentaNeta": 4800.00000,
          "TotalImpuesto": 546.00000,
          "TotalComprobante": 4800.00000
        }
      }
    }';

    $apiUrl = "https://jellyfish-app-s4g2w.ondigitalocean.app/factura";

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData),
        'Accept: application/xml' // Solicitar respuesta en formato XML
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    // Generar un archivo XML y ofrecerlo para descarga
    $xmlFilename = "respuesta_api.xml";
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="' . $xmlFilename . '"');
    echo $response;
    exit; // Detener la ejecución del resto de la página
    ?>
</body>
</html>



