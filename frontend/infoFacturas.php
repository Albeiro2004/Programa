<?php
include 'database1.php'; // Archivo de conexión a la base de datos

// Verifica si la solicitud es AJAX
if (isset($_GET['load_facturas'])) {
    $limit = 50;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $searchCliente = isset($_GET['searchCliente']) ? $_GET['searchCliente'] : '';
    $searchFecha = isset($_GET['searchFecha']) ? $_GET['searchFecha'] : '';

    $query = "
        SELECT 
            factura.idFactura, 
            factura.fecha, 
            cliente.nombre AS clienteNombre, 
            cliente.idCliente AS clienteIdentidad, 
            COALESCE(SUM(detalle_factura.cantidad), 0) AS totalProductos
        FROM factura
        JOIN cliente ON factura.cliente = cliente.idCliente
        LEFT JOIN detalle_factura ON factura.idFactura = detalle_factura.idFactura
        WHERE ('$searchCliente' = '' OR cliente.idCliente LIKE '%$searchCliente%')
        AND ('$searchFecha' = '' OR factura.fecha LIKE '$searchFecha%')
        GROUP BY factura.idFactura, factura.fecha, cliente.nombre, cliente.idCliente
        ORDER BY factura.idFactura DESC
        LIMIT $limit OFFSET $offset
    ";

    $result = $conexion->query($query);

    while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['idFactura']; ?></td>
            <td><?php echo $row['clienteNombre']; ?></td>
            <td><?php echo $row['clienteIdentidad']; ?></td>
            <td><?php echo $row['fecha']; ?></td>
            <td><?php echo $row['totalProductos']; ?></td>
            <td>
                <button onclick="window.open('factura.php?facturaId=<?php echo $row['idFactura']; ?>', '_blank')">
                    <img src="images/pdf.png">
                </button>
            </td>
        </tr>
    <?php endwhile;

    $conexion->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Facturas</title>
    <script>
        let offset = 0;
        const limit = 50;

        function cargarFacturas(reset = false) {
            if (reset) offset = 0;

            let searchCliente = document.getElementById("searchCliente").value;
            let searchFecha = document.getElementById("searchFecha").value;

            let xhr = new XMLHttpRequest();
            xhr.open("GET", `?load_facturas=1&offset=${offset}&searchCliente=${searchCliente}&searchFecha=${searchFecha}`, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (reset) {
                        document.getElementById("facturas-body").innerHTML = xhr.responseText;
                    } else {
                        document.getElementById("facturas-body").innerHTML += xhr.responseText;
                    }
                }
            };
            xhr.send();
            offset += limit;
        }

        function resetearFiltros() {
            document.getElementById("searchCliente").value = "";
            document.getElementById("searchFecha").value = "";
            cargarFacturas(true);
        }

        function configurarBusquedaDinamica() {
            document.getElementById("searchCliente").addEventListener("input", () => cargarFacturas(true));
            document.getElementById("searchFecha").addEventListener("input", () => cargarFacturas(true));
        }

        window.onload = function () {
            cargarFacturas(true);
            configurarBusquedaDinamica();
        };
        const sessionKey = "pagina_facturas_abierta";
const channel = new BroadcastChannel("facturas_channel");

    // Verifica si la página ya estaba abierta
    if (localStorage.getItem(sessionKey)) {
        // Notifica a la otra pestaña para que se actualice
        channel.postMessage("actualizar");
        window.close(); // Cierra la nueva pestaña
    } else {
        // Marcar la página como abierta
        localStorage.setItem(sessionKey, "true");

        // Escuchar si otra pestaña intenta abrirse
        channel.addEventListener("message", (event) => {
            if (event.data === "actualizar") {
                window.location.reload(); // Recargar la página actual
            }
        });

    // Remover la marca cuando la pestaña se cierre
    window.addEventListener("beforeunload", () => {
        localStorage.removeItem(sessionKey);
    });
}
    </script>
 
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

</head>

<body>
    <h1 style="text-align: center;">Facturas</h1>

    <!-- Formulario de búsqueda -->
    <form style="text-align: center; margin-bottom: 20px;">
        <label for="searchCliente">Buscar por Cédula:</label>
        <input type="text" id="searchCliente">
        <label for="searchFecha">Buscar por Fecha:</label>
        <input type="date" id="searchFecha">
        <button type="button" onclick="resetearFiltros()">Resetear</button>
    </form>

    <!-- Tabla de facturas -->
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Identidad</th>
                <th>Fecha</th>
                <th>Cantidad de Productos</th>
                <th>PDF</th>
            </tr>
        </thead>
        <tbody id="facturas-body">
            <!-- Aquí se cargan las facturas dinámicamente -->
        </tbody>
    </table>

    <!-- Botón "Ver más facturas" -->
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="cargarFacturas()">Ver más facturas</button><br>
    </div>
    <br>
</body>
</html>

<style>
      /* Estilos generales */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

/* Estilo del título elegante */
h1 {
    text-align: center;
    font-size: 2rem;
    font-weight: bold;
    color: #ffffff;
    background: linear-gradient(45deg, #ff416c, #ff4b2b);
    padding: 15px;
    border-radius: 10px;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
    margin: 20px auto;
    width: 80%;
}

/* Estilos para el formulario de búsqueda */
form {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    font-size: 14px;
}

input[type="text"], input[type="date"] {
    padding: 3px;
    font-size: 16px;
    border: 2px solid #ccc;
    border-radius: 5px;
}

button {
    background: linear-gradient(45deg, #ff416c, #ff4b2b);
    color: white;
    border: none;
    padding: 8px 15px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
}

button:hover {
    background: linear-gradient(45deg, #ff4b2b, #ff416c);
    transform: scale(1.05);
}

/* Estilos para la tabla */
table {
    width: 90%;
    margin: auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    border: 1px solid #ddd;
    padding: 5px;
    text-align: center;
}

th {
    background-color: #ff416c;
    color: white;
    font-size: 1.1rem;
}

/* Efecto al pasar el mouse por la fila */
tr:hover {
    background-color: rgba(216, 36, 30, 0.2); /* Cambia el color de fondo */
    transition: background-color 0.3s ease-in-out; /* Transición suave */
}

/* Mejorar los botones de la tabla */
td {
    text-align: center;  /* Centra el contenido en la celda */
    vertical-align: middle; /* Asegura que el botón esté alineado verticalmente */
    font-size: 14px;
}

td button {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    margin: auto;  /* Centra el botón horizontalmente */
}


td button img {
    width: 20px;
    height: 20px;
}

/* Responsivo */
@media (max-width: 768px) {
    h1 {
        font-size: 2rem;
        width: 95%;
    }
    form {
        flex-direction: column;
        gap: 15px;
    }
    table {
        width: 100%;
    }
}


    </style>