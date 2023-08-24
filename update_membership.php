<?php
session_start();

if (isset($_SESSION["user_id"])) {
    // Conexión a la base de datos
    $conn = new mysqli("192.168.43.246", "lsdbp", "Coope2022", "db_users");


    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    $user_id = $_SESSION["user_id"];
    
    // Actualizar la suscripción y la fecha
    $update_query = "UPDATE usuarios SET suscripcion_activa = 1, fecha_suscripcion = NOW() WHERE id = '$user_id'";
    $result = $conn->query($update_query);
    
    $conn->close();
}
?>
