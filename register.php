<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cedula = $_POST["cedula"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    
    try {
        // Conexión a la base de datos 
        $conn = new mysqli("192.168.43.246", "lsdbp", "Coope2022", "db_users");


        // Verificar la conexión
        if ($conn->connect_error) {
            throw new Exception("Error en la conexión. Por favor, intenta nuevamente más tarde.");
        }

        // Insertar datos en la base de datos
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (username, password, cedula, telefono, correo) VALUES ('$username', '$hashedPassword', '$cedula', '$telefono', '$correo')";

        if ($conn->query($sql) === TRUE) {
            echo "Registro exitoso";
        } else {
            throw new Exception("Error al registrar el usuario. Por favor, intenta nuevamente más tarde.");
        }

        $conn->close();
    } catch (Exception $e) {
        echo "El usuario o el correo que ingreso ya está en uso, por favor use datos diferentes diferentes";
    }
}
?>

<form method="POST" action="">
    <input type="text" name="username" placeholder="Usuario" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <input type="text" name="cedula" placeholder="Número de cédula" required><br>
    <input type="text" name="telefono" placeholder="Número de teléfono" required><br>
    <input type="email" name="correo" placeholder="Correo electrónico" required><br>
    <button type="submit">Registrarse</button>
</form>

<form method="GET" action="login.php">
    <button type="submit">Iniciar sesión</button>
</form>
