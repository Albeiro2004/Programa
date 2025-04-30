<?php
include 'database1.php';

$vista = $_GET['vista'] ?? 'deficientes';

$query_deficientes = "SELECT p.idProducto, p.nombre, i.cantidadDisponible
                      FROM inventario i
                      JOIN producto p ON i.idProducto = p.idProducto
                      WHERE i.cantidadDisponible <= 5
                      ORDER BY i.cantidadDisponible ASC";
$result_deficientes = $conexion->query($query_deficientes);

$query_mas_disponibles = "SELECT p.idProducto, p.nombre, i.cantidadDisponible
                          FROM inventario i
                          JOIN producto p ON i.idProducto = p.idProducto
                          ORDER BY i.cantidadDisponible DESC
                          LIMIT 10";
$result_mas_disponibles = $conexion->query($query_mas_disponibles);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario</title>
</head>
<body>

<div class="centrado">
    <?php if ($vista == 'deficientes'): ?>
        <div class="encabezado">
        <h2 class="rojo">Productos Deficientes</h2>
        </div>
        
        <div class="tabla-contenedor">
        <table id="tabla">
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre del Producto</th>
                        <th>Cantidad Disponible</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result_deficientes->fetch_assoc()): ?>
                        <tr id="celdaRoja">
                            <td><?= $row['idProducto'] ?></td>
                            <td><?= $row['nombre'] ?></td>
                            <td><?= $row['cantidadDisponible'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <a href="?vista=mas_disponibles">
            <button class="boton-cambio boton-verde">Ver Más Disponibles</button>
        </a>
        <br>
    <?php else: ?>
        <div class="encabezado">
        <h2 class="verde">Productos con Más Disponibilidad</h2>
        </div>
        
        <div class="tabla-contenedor">
            <table id="tabla">
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre del Producto</th>
                        <th>Cantidad Disponible</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result_mas_disponibles->fetch_assoc()): ?>
                        <tr id="celdaVerde">
                            <td><?= $row['idProducto'] ?></td>
                            <td><?= $row['nombre'] ?></td>
                            <td><?= $row['cantidadDisponible'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <a href="?vista=deficientes">
            <button class="boton-cambio boton-rojo">Ver Deficientes</button>
        </a>
        <br>
        
    <?php endif; ?>

    <?php $conexion->close(); ?>
    <button onclick="exportarTabla('tabla')" class="boton-exportar"> 
    <img src="images/microsoft_office_excel_logo_icon_145720.png" alt="Icon"></button>
</div>

<script>
function exportarTabla(idTabla) {
    let tabla = document.getElementById(idTabla);
    let html = tabla.outerHTML;
    let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    let link = document.createElement('a');
    link.href = url;
    link.download = 'inventario.xls';
    link.click();
}
</script>


</body>
</html>

<style>
        body {
            font-family: Arial, sans-serif;
            background: lightgrey;
            padding: 20px;
        }

        .encabezado {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap; /* por si el espacio es muy reducido */
        }

        h2 {
            margin-top: 0;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 15px;
        }

        .rojo {
            color: #333;
            max-width: 50%;
            margin: auto;
        }

        .verde {
            color: #333;
            max-width: 50%;
            margin: auto;
        }

        .tabla-contenedor {
            max-height: 395px;
            overflow-y: auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background-color: #343a40;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
            padding: 12px;
            text-align: center;
        }

        td, th {
            padding: 12px;
            border: 2px solid #333;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .boton-cambio {
            background-color: #28a745;
            margin-top: 15px;
            padding: 10px 20px;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .boton-rojo {
            background-color: #dc3545;
        }

        .boton-verde {
            background-color: #28a745;
        }

        .centrado {
            text-align: center;
        }

        .boton-exportar {
            background-color:rgba(25, 207, 67, 0.8);
            margin-top: 15px;
            padding: 10px;
            border: none;
            color: black;
            border-radius: 6px;
            cursor: pointer;
        }

        #celdaRoja{
            background-color:rgb(238, 172, 178);
        }

        #celdaVerde{
            background-color:rgb(193, 255, 193);
        }

        img{
            width: 20px;
            height: auto;
        }
    </style>