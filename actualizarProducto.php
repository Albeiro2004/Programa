<?php
include 'database1.php'; // Archivo con la conexión a la base de datos

// Consulta con JOIN para obtener datos de producto + inventario
$query = "SELECT p.idProducto, p.nombre, p.precio, p.precioCompra, p.marca, 
                 i.cantidadDisponible, i.ubicacion 
          FROM producto p
          JOIN inventario i ON p.idProducto = i.idProducto";

$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toLowerCase();
            let table = document.getElementById("productTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) { // Comienza en 1 para evitar la fila de encabezado
                let td = tr[i].getElementsByTagName("td");
                let match = false;

                for (let j = 0; j < td.length - 1; j++) { // Evita la columna de acción
                    if (td[j]) {
                        let textValue = td[j].textContent || td[j].innerText;
                        if (textValue.toLowerCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = match ? "" : "none";
            }
        }
    </script>
</head>
    
<body>
    <h1>Listado de Productos</h1>

    <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar productos...">

    <table id="productTable">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Cantidad</th>
                <th>Ubicación</th>
                <th>Marca</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['idProducto'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td>$ <?= number_format($row['precioCompra'],0, ".") ?></td>
                <td>$ <?= number_format($row['precio'],0, ".") ?></td>
                <td><?= $row['cantidadDisponible'] ?></td>
                <td><?= $row['ubicacion'] ?></td>
                <td><?= $row['marca'] ?></td>
                <td>
                    <a href="procesarActualizacion.php?idProducto=<?= $row['idProducto'] ?>">Actualizar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br><br>
</body>
</html>

<style>
    /* General */
body {
    font-family: 'Poppins', Arial, sans-serif;
    margin: 0;
    padding: 0;
    color: #333;
    background-image: url('images/bag2.jpg'); 
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

/* Título */
h1 {
    text-align: center;
    color: #fff;
    margin-top: 20px;
    font-size: 28px;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
}

/* Buscador */
#searchInput {
    display: block;
    width: 90%;
    max-width: 400px;
    padding: 10px;
    margin: 20px auto;
    border: 2px solid #28a745;
    border-radius: 25px;
    font-size: 16px;
    text-align: center;
    outline: none;
    transition: 0.3s ease-in-out;
    background-color: #fff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

#searchInput:focus {
    border-color: #218838;
    box-shadow: 0px 0px 10px rgba(40, 167, 69, 0.5);
}

/* Tabla */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    overflow: hidden;
}

/* Encabezados de la tabla */
th {
    background-color:rgba(14, 14, 14, 0.66);
    color: white;
    text-transform: uppercase;
    font-size: 14px;
    padding: 12px;
}

/* Celdas */
th, td {
    border: 2px solid rgba(14, 14, 14, 0.66);
    padding: 8px;
    text-align: center;
    font-size: 16px;
}

/* Filas alternas */
tr:nth-child(even) {
    background-color:rgba(140, 212, 134, 0.69);
}

/* Efecto hover en filas */
tr:hover {
    background-color: #e9ecef;
    transition: 0.3s;
    cursor: pointer;
}

/* Enlaces */
a {
    text-decoration: none;
    color: #007BFF;
    font-weight: bold;
    transition: 0.3s ease-in-out;
}

a:hover {
    text-decoration: underline;
    color: #0056b3;
}

/* Botón fijo */
.fixed-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #28a745;
    border: none;
    color: white;
    padding: 15px;
    border-radius: 50%;
    font-size: 18px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: 0.3s;
}

.fixed-icon:hover {
    background-color: #218838;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
}

/* Diseño Responsive */
@media (max-width: 768px) {
    table {
        font-size: 14px;
    }

    th, td {
        padding: 10px;
    }

    #searchInput {
        max-width: 100%;
    }
}

</style>