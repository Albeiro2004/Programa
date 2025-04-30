<?php
include 'database1.php';

// Consulta para obtener los productos más vendidos
$query = "SELECT p.idProducto, p.nombre, SUM(df.cantidad) AS total_vendido
          FROM detalle_factura df
          JOIN producto p ON df.idProducto = p.idProducto
          GROUP BY p.idProducto
          ORDER BY total_vendido DESC
          LIMIT 10";

$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Más Vendidos</title>
</head>
<body>
    <h1>Top --- Productos --- Vendidos</h1>

    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Posición</th>
                    <th>Nombre del Producto</th>
                    <th>Cantidad Vendida</th>
                </tr>";

        $position = 1; // Variable para mostrar la posición del producto
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $position . "</td>
                    <td>" . $row['nombre'] . "</td>
                    <td>" . $row['total_vendido'] . "</td>
                  </tr>";
            $position++; // Aumenta la posición
        }
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }

    $conexion->close();
    ?>
</body>
</html>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background-image: url('images/fondoProducto.jpg'); 
        background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
        background-position: center;  /* Centra la imagen */
        background-repeat: no-repeat; /* Evita que la imagen se repita */
    }

    h1 {
        text-align: center;
        color: #444;
        margin-bottom: 20px;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        margin: 0 auto;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #d1e7dd;
        cursor: default;
    }

    td:first-child {
        font-weight: bold;
        color: #007bff;
    }

    @media (max-width: 600px) {
        table {
            width: 100%;
        }

        th, td {
            font-size: 14px;
        }
    }
</style>
