<?php
include 'database1.php'; // Conexión a la base de datos

// Consulta para obtener los usuarios
$query = "SELECT * FROM usuarios";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .oculto {
            font-family: monospace;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>                
                <th>Contraseña</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['usuario']) ?></td>
                <td class="oculto"><?= str_repeat('•', strlen($row['clave'])) ?></td>
                
                <td>
                    <a href="UpdateUsuario.php?id=<?= $row['id'] ?>">Actualizar Datos</a>
                </td>                               
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<style>
   body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    text-align: center;
    margin: 0;
    padding: 0;
    flex-direction: column;
    align-items: center;
    background-image: url('images/bag7.jpg'); 
    background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
    background-position: center;  /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
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
    border: 1px solid #28a745;
    padding: 15px;
    text-align: center; /* Centrar el contenido */
    font-size: 1rem;
}

th {
    background-color: yellowgreen; 
    color: black; 
}

tr:nth-child(even) {
    background-color: #f2f2f2; /* Fila alterna con fondo gris claro */
}

tr:hover {
    background-color: #e0e0e0; /* Fila resalta en gris cuando el ratón pasa por encima */
}

/* Estilo de los enlaces */
a {
    color: #218838; 
    text-decoration: none; /* Eliminar subrayado */
    font-weight: bold;
    padding: 5px 5px;
    border: 1px solid yellowgreen;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
    font-size: 12px;
}

a:hover {
    background-color: silver; 
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