<?php
include 'database1.php';

// Verificar si se enviaron las fechas del formulario
if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Consulta para obtener las facturas entre las fechas seleccionadas
    $query = "SELECT * FROM factura 
              WHERE DATE(fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'
              ORDER BY fecha DESC";

    $result = $conexion->query($query);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Facturas</title>
</head>
<body>
    <h1>Facturas por Fechas</h1>

    <!-- Formulario para seleccionar el rango de fechas -->
    <form method="POST" action="facturasFecha.php">
        <label for="fecha_inicio">Fecha de inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" required>
        <label for="fecha_fin">Fecha de fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" required>
        <button type="submit">Consultar</button>
    </form>

    <?php
    if (isset($result)) {
        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>ID Factura</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                    </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['idFactura'] . "</td>
                        <td>" . $row['cliente'] . "</td>
                        <td>" . $row['fecha'] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron facturas en este rango de fechas.";
        }
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
        background-color: #f5f6f7;
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        min-height: 100vh;
        background-image: url('images/fondoUser.jpg'); 
        background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
        background-position: center;  /* Centra la imagen */
        background-repeat: no-repeat; /* Evita que la imagen se repita */
    }

    h1 {
        color: #444;
        margin-top: 20px;
        margin-bottom: 20px;
        text-align: center;
    }

    form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        width: 300px;
    }

    label {
        font-size: 14px;
        font-weight: bold;
        color: #555;
    }

    input[type="date"] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
    }

    button {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #0056b3;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        margin: 20px 0;
        background-color: #ffffff;
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
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #e8f4fd;
    }

    td:first-child {
        font-weight: bold;
    }

    @media (max-width: 600px) {
        form {
            width: 90%;
        }

        table {
            width: 100%;
        }

        th, td {
            font-size: 14px;
        }
    }
</style>
