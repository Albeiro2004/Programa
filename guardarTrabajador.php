<?php
include 'database1.php'; // Conexión a la base de datos

// Obtener los datos del formulario
$identidad = $_POST['identidad'];
$nombre = $_POST['nombre'];
$puesto = $_POST['puesto'];
$sueldo = $_POST['sueldo'];

// Validar los datos antes de la inserción
if (empty($nombre) || empty($identidad) || empty($puesto)) {
    echo "Por favor, completa todos los campos.";
    exit;
}

// Preparar la consulta para insertar el producto con los datos recibidos
$stmt = $conexion->prepare("INSERT INTO trabajador (identidad, nombre, puesto, sueldo ) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issd", $identidad, $nombre, $puesto, $sueldo);

// Ejecutar la consulta
if ($stmt->execute()) {
    header("Location: registrarTrabajador.php?mensaje=Empleado Registrado");
    exit();
} else {
    header("Location: registrarTrabajador.php?mensaje=Error al Registrar");
    exit();
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>