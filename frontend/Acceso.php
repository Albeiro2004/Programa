<?php
session_start(); // Iniciar sesión para mantener el acceso
include 'database1.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $passwordIngresada = $_POST['password'];

    // Consulta para verificar si la contraseña existe en la tabla usuarios
    $query = "SELECT * FROM usuarios WHERE clave = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $passwordIngresada);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['accesoPermitido'] = true; // Guardar sesión
        header("Location: Usuarios.php"); // Redirigir a la página de actualización
        exit;
    } else {
        $error = "Contraseña incorrecta. Inténtelo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso a Usuarios</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <form method="POST">
        <label for="password">Ingrese la contraseña:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Entrar</button>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
