<?php
include 'database1.php';

// Consulta para obtener el Top 10 de clientes con más facturas
$query = "SELECT f.cliente, COUNT(f.idFactura) AS num_facturas
          FROM factura f
          GROUP BY f.cliente
          ORDER BY num_facturas DESC
          LIMIT 10";

$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Clientes</title>
</head>
<body>
    <h1>Top --- Clientes</h1><br>

    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Posición</th>
                    <th>Cliente ID</th>
                    <th>Facturas Realizadas</th>
                </tr>";

        $position = 1; // Variable para mostrar la posición del cliente
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $position . "</td>
                    <td>" . $row['cliente'] . "</td>
                    <td>" . $row['num_facturas'] . "</td>
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
        background-color: #f9f9f9;
        color: #333;
        
        justify-content: center; /* Centra horizontalmente */
        align-items: center; /* Centra verticalmente */
        background-image: url('images/bag8.jpg'); 
        background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
        background-position: center;  /* Centra la imagen */
        background-repeat: no-repeat; /* Evita que la imagen se repita */
    }

    h1 {
        text-align: center;
        color: black;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        background-color: #fff;
    }

    th, td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        background-color: #4CAF50;
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

