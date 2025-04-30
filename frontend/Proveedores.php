<?php
include 'database1.php'; // Conexión a la base de datos

// Consulta para obtener todos los proveedores
$query = "SELECT * FROM proveedor";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores</title>

</head>
<body>
    <h1>Proveedores</h1>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Contacto</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['codProveedor'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['direccion'] ?></td>
                <td><?= $row['contacto'] ?></td>
                <td>
                    <a href="UpdateProveedor.php?codProveedor=<?= $row['codProveedor'] ?>">Actualizar Datos</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
<style>
     /* Estilo general de la página */
body {
    font-family: Arial, sans-serif; /* Fuente limpia y legible */
    background-color: #f4f4f9; /* Fondo gris claro */
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-image: url('images/bag5.jpg'); 
    background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
    background-position: center;  /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
}

/* Título principal */
h1 {
    font-size: 2rem;
    color: black;
    margin-top: 40px;
    text-align: center;
}

/* Estilo para la tabla */
table {
    border-collapse: collapse;
    width: 80%; /* Ajustar el tamaño de la tabla */
    margin-top: 20px;
    background-color: gainsboro;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Sombra sutil para la tabla */
    
}


/* Estilo de las celdas de la tabla */
th, td {
    border: 1px solid #ddd;
    padding: 15px;
    text-align: center; /* Centrar el contenido */
    font-size: 1rem;
}

th {
    background-color: #007bff; /* Fondo azul en los encabezados */
    color: white; /* Texto blanco en los encabezados */
}

tr:nth-child(even) {
    background-color: #f2f2f2; /* Fila alterna con fondo gris claro */
}

tr:hover {
    background-color: #e0e0e0; /* Fila resalta en gris cuando el ratón pasa por encima */
}

/* Estilo de los enlaces */
a {
    color: #007bff; /* Color azul para los enlaces */
    text-decoration: none; /* Eliminar subrayado */
    font-weight: bold;
    padding: 5px 5px;
    border: 1px solid #007bff;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
    font-size: 12px;
}

a:hover {
    background-color: #007bff; /* Fondo azul cuando el ratón pasa */
    color: white; /* Texto blanco cuando el ratón pasa */
    text-decoration: none; /* Eliminar subrayado cuando se pasa el ratón */
}

/* Estilo de las filas de la tabla */
tr {
    transition: background-color 0.3s ease;
}

/* Estilo para el contenedor de la tabla */
.container {
    width: 100%;
    display: flex;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
}

/* Estilo del botón de acción (si es necesario) */
button {
    background-color: #28a745; /* Verde */
    color: white;
    padding: 5px 5px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 4px;
}

button:hover {
    background-color: #218838; /* Verde oscuro en hover */
}

/* Espaciado en la tabla para un mejor ajuste */
table td, table th {
    padding: 10px 15px;
}

/* Añadir espaciado entre la tabla y el contenido superior */
h1 {
    margin-bottom: 20px;
}
    </style>
