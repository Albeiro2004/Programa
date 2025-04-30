<?php
include 'database1.php';

$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;

// Consulta con cálculo de ganancia
$query_ingresos = "SELECT p.idProducto, p.nombre, p.marca, 
                          SUM(fd.cantidad * (p.precio - p.precioCompra)) AS ganancia
                   FROM detalle_factura fd
                   JOIN producto p ON fd.idProducto = p.idProducto
                   JOIN factura f ON fd.idFactura = f.idFactura";

if ($fecha_inicio) {
    $query_ingresos .= " WHERE f.fecha >= '$fecha_inicio'";
}

$query_ingresos .= " GROUP BY p.idProducto, p.nombre, p.marca
                     ORDER BY ganancia DESC";

$result_ingresos = $conexion->query($query_ingresos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganancias por Producto</title>
</head>
<body>

<h1>Ganancias Generadas por Producto</h1>

<form method="POST" action="">
    <label for="fecha_inicio">Seleccionar desde:</label>
    <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= $fecha_inicio ?? '' ?>">
    <button type="submit">Consultar</button>
</form>

<div class="tabla-contenedor">
<?php
if ($result_ingresos->num_rows > 0) {
    echo "<table>
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Ganancia Total</th>
                </tr>
            </thead>
            <tbody>";

    while($row = $result_ingresos->fetch_assoc()) {
        echo "<tr>
                <td>{$row['idProducto']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['marca']}</td>
                <td>$" . number_format($row['ganancia'], 0) . "</td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p style='text-align:center;'>No se encontraron registros de ganancias.</p>";
}

$conexion->close();
?>
</div>

</body>
</html>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f7f9fc;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="date"], button {
            padding: 8px 14px;
            margin: 0 5px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .tabla-contenedor {
            max-height: 460px;
            overflow-y: auto;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        thead {
            position: sticky;
            top: 0;
            background-color: #2c3e50;
            color: white;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        tr:hover {
            background-color: #f0f0f0;
        }
    </style>