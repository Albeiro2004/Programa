<?php
include 'database1.php'; // Conexión a la base de datos

// Verificar si se pasó el código del proveedor
if (isset($_GET['identidad'])) {
    $idTrabajador = $_GET['identidad'];

    // Consulta para obtener los datos del proveedor
    $query = "SELECT * FROM trabajador WHERE identidad = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idTrabajador);
    $stmt->execute();
    $result = $stmt->get_result();
    // Comprobar si el proveedor existe
    if ($result->num_rows > 0) {
        $trabajador = $result->fetch_assoc();
    } else {
        die("Empleado no encontrado.");
    }
} else {
    die("Código de Empleado no especificado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $puesto = $_POST['puesto'];
    $sueldo = $_POST['sueldo'];

    // Consulta para actualizar los datos
    $updateQuery = "UPDATE trabajador SET nombre = ?, puesto = ?, sueldo = ? WHERE identidad = ?";
    $updateStmt = $conexion->prepare($updateQuery);
    $updateStmt->bind_param("ssdi", $nombre, $puesto, $sueldo, $idTrabajador);
    
    if ($updateStmt->execute()) {
        header("Location: Trabajadores.php"); 
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
    <title>Actualizar Empleado</title>
</head>
<body>
    <h1>Empleado</h1>
    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?= $trabajador['nombre'] ?>" required><br><br>

        <label for="puesto">Puesto:</label>
        <input type="text" id="puesto" name="puesto" value="<?= $trabajador['puesto'] ?>" required><br><br>

        <label for="sueldo">Contacto:</label>
        <input type="number" id="sueldo" name="sueldo" value="<?= $trabajador['sueldo'] ?>" required><br><br>

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