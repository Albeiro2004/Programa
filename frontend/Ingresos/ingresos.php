<?php include("../database1.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresos</title>
    <link rel="icon" type="image/png" href="../images/consultar.png">
    <link rel="stylesheet" href="@import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Special+Gothic+Condensed+One&family=Special+Gothic+Expanded+One&display=swap');">
</head>
<body>
    
<h2>Consulta de ingresos por facturas</h2>

<?php
    $consulta = $conexion->query("SELECT CURDATE() AS fecha_actual");
    $fecha = $consulta->fetch_assoc()['fecha_actual'];
    $hoy = date('Y-m-d', strtotime($fecha));
?>
<form method="GET" id="formulario">
    Desde: <input type="date" name="desde" value="<?php echo isset($_GET['desde']) ? $_GET['desde'] : $hoy; ?>" required>
    Hasta: <input type="date" name="hasta" value="<?php echo isset($_GET['hasta']) ? $_GET['hasta'] : $hoy; ?>" required>
    <button type="submit">Consultar</button>
</form> 

<?php
if (isset($_GET['desde']) && isset($_GET['hasta'])) {
    $desde = $_GET['desde'];
    $hasta = $_GET['hasta'];

    $query = "
    SELECT 
        f.idFactura,
        f.fecha,
        c.nombre,
        SUM((p.precio - p.precioCompra) * df.cantidad) AS ingreso
    FROM factura f
    JOIN cliente c ON f.cliente = c.idCliente
    JOIN detalle_factura df ON f.idFactura = df.idFactura
    JOIN producto p ON df.idProducto = p.idProducto
    WHERE DATE(f.fecha) BETWEEN '$desde' AND '$hasta'
    GROUP BY f.idFactura, f.fecha, c.nombre
    ORDER BY f.fecha;
";




    $resultado = $conexion->query($query);
    $ingreso_total = 0;

    if ($resultado->num_rows > 0) {
        echo '<div class="table-container">';
        
        echo "<table border='1' id='tabla'>
            <thead>
            <tr>
                <th>Cliente</th>
                <th>ID Factura</th>
                <th>Fecha</th>
                <th>Ingreso</th>
                <th>PDF</th>
            </tr></thead>";
            while ($row = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo $row['idFactura']; ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td>$ <?php echo number_format($row['ingreso'], 0, ',', '.'); ?></td>
                    <td>
                        <button class="bott" onclick="window.open('../factura.php?facturaId=<?php echo $row['idFactura']; ?>', '_blank')">
                            <img src="../images/pdf.png" alt="PDF">
                        </button>
                    </td>
                </tr>
                <?php $ingreso_total += $row['ingreso']; ?>
            <?php endwhile;
        echo "</table></div>";
        echo "<h3>Ingreso total en el rango: $ " . number_format($ingreso_total, 0, ',', '.') . "</h3>";
    } else {
        echo "No se encontraron facturas en ese rango.";
    }
}
?>


<script>
function verificarActualizacion() {
// Revisar si ha cambiado la clave 'inventarioActualizado'
window.addEventListener('storage', function(event) {
    if (event.key === 'clave') {
        location.reload(); // Recargar la página automáticamente
    }
});
}
// Llamar a la función al cargar la página
verificarActualizacion();
</script>

<script>
    // Solo enviar el formulario si no se ha enviado aún (para evitar bucles)
    window.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (!urlParams.has('desde') || !urlParams.has('hasta')) {
            document.getElementById('formulario').submit();
        }
    });
</script>

</body>
</html>

<style>
    body {
        font-family: 'Roboto Condensed', sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 20px;
        background-color: lightgrey;
        color: #333;
    }
    
    h2 {
        color: #2c3e50;
        text-align: center;
        margin-top: -5px;
        margin-bottom: 5px;
    }
    
    h3 {
        color: rgb(21, 143, 49);
        text-align: center;
    }
    
    form {
        background-color: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        max-width: 500px;
        margin: 0 auto 20px;
    }
    
    input[type="date"] {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 10px;
    }
    
    button[type="submit"] {
        background-color:rgb(21, 143, 49);
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    button[type="submit"]:hover {
        background-color:rgb(139, 240, 169);
    }
    
    .table-container {
        max-height: 400px; /* Altura máxima del área visible de la tabla */
        overflow-y: auto;  /* Scroll vertical si excede la altura */
        margin-top: 20px;
        border: 1px solid #ccc;
        border-radius: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        
    }

    thead th {
        position: sticky;
        top: 0;
        z-index: 1;
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    
    th {
        background-color:rgb(21, 143, 49);
        color: white;
        padding: 10px;
        text-align: center;
    }
    
    td {
        padding: 5px;
        background-color: white;
        text-align: center;
    }
    
    tr:nth-child(even) td {
        background-color: #f2f2f2;
    }
    
    .bott {
        background: lightgrey;
        color: white;
        border: none;
        padding: 5px;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.3s ease-in-out;
    }

    .bott:hover {
        background: linear-gradient(45deg, #ff4b2b, #ff416c);
        transform: scale(1.05);
    }

    img {
        width: 20px;
        height: 20px;
    }

    @media (max-width: 600px) {
        form {
            padding: 15px;
        }
        
        input[type="date"] {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        
        button[type="submit"] {
            width: 100%;
        }
    }

    
</style>
