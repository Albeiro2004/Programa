<?php
session_start(); // Continuar la sesión

// Verificar si el acceso está permitido
if (!isset($_SESSION['accesoPermitido']) || !$_SESSION['accesoPermitido']) {
    header("Location: Accesos.php"); // Redirigir si no tiene acceso
    exit;
}

include 'database1.php'; // Conexión a la base de datos

// Obtener los datos del usuario
if (isset($_GET['id'])) {
    $idUsuario = $_GET['id'];
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if (!$usuario) {
        echo "Usuario no encontrado.";
        exit;
    }
} else {
    echo "No se proporcionó un ID de usuario.";
    exit;
}

// Actualizar usuario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombreUsuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $updateQuery = "UPDATE usuarios SET usuario = ?, clave = ? WHERE id = ?";
    $stmt = $conexion->prepare($updateQuery);
    $stmt->bind_param("ssi", $nombreUsuario, $clave, $idUsuario);

    if ($stmt->execute()) {
        echo "Usuario actualizado exitosamente.";
        header("Location: Usuarios.php"); 
    } else {
        echo "Error al actualizar el usuario: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Actualizar Usuario</h1>
    <form method="POST">
        <label for="usuario">Nombre de Usuario:</label>
        <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>" required><br><br>

        <label for="clave">Correo Electrónico:</label>
        <input type="password" id="clave" name="clave" value="<?= htmlspecialchars($usuario['clave']) ?>" required><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
