<?php
include 'database1.php'; // Conexión a la base de datos

// Verificar si se pasó el código del proveedor
if (isset($_GET['codProveedor'])) {
    $codProveedor = $_GET['codProveedor'];

    // Consulta para obtener los datos del proveedor
    $query = "SELECT * FROM proveedor WHERE codProveedor = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $codProveedor);
    $stmt->execute();
    $result = $stmt->get_result();

    // Comprobar si el proveedor existe
    if ($result->num_rows > 0) {
        $proveedor = $result->fetch_assoc();
    } else {
        die("Proveedor no encontrado.");
    }
} else {
    die("Código de proveedor no especificado.");
}

// Actualización de los datos del proveedor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $contacto = $_POST['contacto'];

    // Consulta para actualizar los datos
    $updateQuery = "UPDATE proveedor SET nombre = ?, direccion = ?, contacto = ? WHERE codProveedor = ?";
    $updateStmt = $conexion->prepare($updateQuery);
    $updateStmt->bind_param("sssi", $nombre, $direccion, $contacto, $codProveedor);
    
    if ($updateStmt->execute()) {
        header("Location: Proveedores.php"); // Redirige al listado de productos
        exit();
    } else {
        echo "Error al actualizar los datos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Proveedor</title>
</head>
<body>
    <h1>Actualizar Proveedor</h1>
    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?= $proveedor['nombre'] ?>" required><br><br>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?= $proveedor['direccion'] ?>" required><br><br>

        <label for="contacto">Contacto:</label>
        <input type="text" id="contacto" name="contacto" value="<?= $proveedor['contacto'] ?>" required><br><br>

        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
<style>
    /* Estilos generales de la página */
body {
    font-family: Arial, sans-serif; /* Fuente limpia y legible */
    background-color: #f4f4f9; /* Fondo gris claro */
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh; 
    background-image: url('images/bag5.jpg'); 
    background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
    background-position: center;  /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
}

/* Título principal */
h1 {
    font-size: 2rem;
    color: black;
    margin-top: 20px;
    text-align: center;
}

/* Estilo para el formulario */
form {
    background-color: white; /* Fondo blanco para el formulario */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Sombra suave */
    width: 100%;
    max-width: 500px; /* Limitar el ancho máximo */
    margin-top: 20px;
}

/* Estilo de las etiquetas y los campos de entrada */
label {
    font-size: 1rem;
    color: #333;
    margin-bottom: 5px;
    display: block;
}

/* Estilo de los campos de entrada */
input[type="text"], input[type="number"] {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 15px;
    box-sizing: border-box;
}

/* Estilo del botón */
button {
    background-color: #007bff; /* Color azul para el botón */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3; /* Azul más oscuro cuando se pasa el ratón */
}

/* Añadir espaciado al formulario y a los elementos */
form input, form button {
    margin-top: 10px;
}

/* Enfoque en los campos de entrada */
input:focus {
    border-color: #007bff; /* Cambiar el borde a azul cuando el campo esté enfocado */
    outline: none;
}

</style>