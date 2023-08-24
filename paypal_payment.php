<?php
// Verificar la autenticación del usuario aquí
session_start();

if (!isset($_SESSION["user_id"])) {
    // Redirigir a la página de inicio de sesión si el usuario no está autenticado
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pago con PayPal</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AX5lrareglBI2kqnBHHIk8WAq4l6RWH2aTNiy26wjZ6ppopQII0T9AQ-RPSKNRGbfr8DTpCbNF3vmBgx"></script>
</head>
<body>
    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '10.00'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Realizar una petición al servidor para actualizar la suscripción y fecha
                    fetch('update_membership.php', {
                        method: 'POST',
                        credentials: 'same-origin',
                    }).then(response => {
                        if (response.ok) {
                            alert('Pago exitoso, se va descargar su factura y será redirigido en 3 segundos');
                            window.location.href = 'xml-receipt.php'; // Primer redireccionamiento

                            setTimeout(function() {
                                window.location.href = 'index.php'; // Segundo redireccionamiento
                            }, 3000); // Redireccionar después de 3 segundos
                        } else {
                            alert('Error en el pago');
                        }
                    });
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
