<?php
// Incluye la conexión a la base de datos
include 'database1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura los datos del formulario
    $nombreUsuario = $_POST['usuario'];
    $password = $_POST['clave'];
    $confirmPassword = $_POST['confirClave'];

    // Verificar si las contraseñas coinciden
    if ($password === $confirmPassword) {

        // Inserta los datos en la tabla de usuarios
        $sql = "INSERT INTO usuarios (usuario, clave) VALUES ('$nombreUsuario', '$password')";

        if (mysqli_query($conexion, $sql)) {
            header("Location: registrarUsuario.php?mensaje=Usuario Creado");
            exit();
        } else {
            header("Location: registrarUsuario.php?mensaje=Error en Registro");
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            exit();
        }
    } else {
        header("Location: registrarUsuario.php?mensaje=Las Contraseñas no coinciden.");
            exit();
    }
    // Cierra la conexión
    mysqli_close($conexion);
}
?>
