<?php
include 'database1.php'; // Conexión a la base de datos

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$contacto = $_POST['contacto'];

// Validar los datos antes de la inserción
if (empty($nombre) || empty($contacto) || empty($direccion)) {
    echo "Por favor, completa todos los campos.";
    exit;
}

// Preparar la consulta para insertar el producto con los datos recibidos
$stmt = $conexion->prepare("INSERT INTO proveedor (nombre, direccion, contacto ) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $direccion, $contacto);

// Ejecutar la consulta
if ($stmt->execute()) {
    header("Location: registrarProveedor.php?mensaje=Proveedor Registrado");
    exit();
} else {
    header("Location: registrarProveedor.php?mensaje=Error al Registrar");
    exit();
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>