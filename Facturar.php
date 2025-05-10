<?php
include 'database1.php';  // Archivo de conexión a la base de datos

// Obtener el ID del cliente desde la URL
$cliente_id = $_GET['idCliente'] ?? null;

// Verificar si el cliente ID está presente
if (!$cliente_id) {
    $cliente_id = 0;
}

// Consultar los productos disponibles
$resultProductos = $conexion->query("SELECT idProducto, nombre, marca, precio, ubicacion, cantidadDisponible FROM producto
                                    NATURAL JOIN inventario WHERE cantidadDisponible > 0;");

// Verificar si se ha enviado el formulario para crear la factura
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar que al menos un producto tenga una cantidad mayor a 0
    $productos = $_POST['productos'] ?? [];
    $cantidadValida = false;

    foreach ($productos as $cantidad) {
        if ($cantidad > 0) {
            $cantidadValida = true;
            break;
        }
    }

    if (!$cantidadValida) {
        echo '
        <div id="mensaje" style="font-size: 12px; width: 30%; padding: 10px; border: 1px solid #ccc; margin: 20px auto; text-align: center; border-radius: 10px; background-color: #f8d7da; color: #721c24;">
            No se puede facturar. Debes seleccionar al menos un producto.
            <script>
            // Ocultar el mensaje después de 3 segundos
            const mensajeDiv = document.getElementById("mensaje");
            setTimeout(function () {
            mensajeDiv.style.display = "none";
            }, 3000); </script>
        </div>';
    } else {
        // Registrar la factura
        
        $insertFactura = "INSERT INTO factura (cliente, fecha) VALUES (?, NOW())";
        $stmt = $conexion->prepare($insertFactura);
        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();
        $factura_id = $stmt->insert_id;  // Obtener el ID de la factura insertada
        $stmt->close();

        // Insertar los productos en detalle_factura
        if (isset($_POST['productos'])) {
            foreach ($_POST['productos'] as $producto_id => $cantidad) {
                if ($cantidad > 0) {
                    // Insertar en detalle_factura
                    $insertDetalle = "INSERT INTO detalle_factura (idFactura, idProducto, cantidad) VALUES (?, ?, ?)";
                    $stmt = $conexion->prepare($insertDetalle);
                    $stmt->bind_param("isi", $factura_id, $producto_id, $cantidad);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        
        echo "<script>

        localStorage.setItem('clave', Date.now());
        // Variables globales para manejar la referencia de la ventana
        let ventanaAbierta = null;

        function abrirOPriorizarPagina(url) {
            if (ventanaAbierta && !ventanaAbierta.closed) {
                ventanaAbierta.focus(); // Enfoca la ventana si ya está abierta
            } else {
                ventanaAbierta = window.open(url, '_blank'); // Abre una nueva ventana si no está abierta
            } 
        }

        // Mostrar mensaje de éxito
        alert('Factura creada con éxito!');
        
        // Verificar si la ventana ya está abierta o no
        abrirOPriorizarPagina('infoFacturas.php');

        // Cerrar la pestaña actual después de redirigir a la otra
        window.close(); 
          </script>"; 
          exit();  // Detener la ejecución del script después de la redirección
    
    }
}

    // Consultar el nombre del cliente
    $sql = "SELECT nombre FROM cliente WHERE idCliente = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $stmt->bind_result($nombre_cliente);
    $stmt->fetch();
    $stmt->close();
    $conexion->close();

        $mensaje = $_GET['mensaje'] ?? ''; // Obtener el mensaje de éxito de la URL (si está presente)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Factura</title>
    <link rel="icon" type="image/png" href="images/facturar.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<!-- Mostrar mensaje de éxito si está presente -->
<?php if ($mensaje): ?>
        <div id="mensaje" style="font-size: 16px; width: 30%; padding: 10px; border: 1px solid #ccc; margin: 20px auto; text-align: center; border-radius: 10px; background-color: #d4edda; color: #155724;">
            <?php echo $mensaje; ?>
            <script>
            // Ocultar el mensaje después de 3 segundos
            const mensajeDiv = document.getElementById("mensaje");
            setTimeout(function () {
            mensajeDiv.style.display = "none";
            }, 3000); </script>
        </div>
    <?php endif; ?>


    <h1>Facturar Productos a: <?php echo $nombre_cliente; ?> </h1> <br>

    <form id="formulario" method="POST">

    <div id="search-container">
    <!-- Buscador de productos -->
    <input type="text" id="search" onkeyup="buscarProducto()" placeholder="Buscar producto...">
    <button type="button" onclick="limpiarBusqueda()"><i class="fas fa-times"></i></button>
    <button id="id" type="button" onclick="mostrarSeleccionados()"> 
    <i class="fas fa-eye"></i> Seleccionados</button>

    </div>
        
    <div id="total-container" style="position: fixed; top: 20px; left: 20px; background-color: #f0f0f0; padding: 10px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <strong>Total a pagar:</strong> $<span id="total">0.00</span>
    </div>


        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Precio</th>
                    <th>Ubicación</th>
                    <th>Disponible</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody id="productos-container">
                <?php
                while ($row = $resultProductos->fetch_assoc()) {
                    echo "<tr class='producto'>
                            <td>" . $row['nombre'] . "</td>
                            <td>" . $row['marca'] . "</td>
                            <td>$" . number_format($row['precio'], 2) . "</td>
                            <td>" . $row['ubicacion'] . "</td>
                            <td>" . $row['cantidadDisponible'] . "</td>
                            <td>
                                <input type='number' name='productos[" . $row['idProducto'] . "]' min='0' value='0'>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <button id="boton-facturar" type="submit"> <i class="fas fa-check-circle"></i>Facturar</button>
        
    </form>

    <script>
         function limpiarBusqueda() {
        // Limpia el valor del input
        document.getElementById('search').value = '';
        // Opcionalmente, puedes reiniciar la búsqueda de productos
        buscarProducto();
    }

    function buscarProducto() {
        var input, filter, container, productos, i, txtValue;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        container = document.getElementById("productos-container");
        productos = container.getElementsByClassName("producto");

        for (i = 0; i < productos.length; i++) {
            txtValue = productos[i].textContent || productos[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                productos[i].style.display = "";
            } else {
                productos[i].style.display = "none";
            }
        }
    }
    function mostrarSeleccionados() {
    var container = document.getElementById("productos-container");
    var productos = container.getElementsByClassName("producto");
    var input;

    for (var i = 0; i < productos.length; i++) {
        input = productos[i].querySelector("input[type='number']");
        if (input && input.value > 0) {
            productos[i].style.display = ""; // Mostrar productos seleccionados
        } else {
            productos[i].style.display = "none"; // Ocultar productos no seleccionados
        }
    }
}
        document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll("input[type='number']");
        const totalSpan = document.getElementById("total");

        // Función para calcular el total
        function calcularTotal() {
        let total = 0;

        inputs.forEach(input => {
            const cantidad = parseFloat(input.value) || 0;
            const precio = parseFloat(input.closest('tr').querySelector('td:nth-child(3)').innerText.replace('$', '').replace(',', '')) || 0;
            total += cantidad * precio;
        });

        // Formatear el total con separadores de miles
        const formattedTotal = new Intl.NumberFormat('es-ES', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(total);

        totalSpan.innerText = formattedTotal;
        }

        // Añadir eventos a cada input
        inputs.forEach(input => {
        input.addEventListener('input', calcularTotal);
            });
        });

    </script>
    
</body>
</html>

<style>
    /* General */
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        padding: 0;
        background-color:rgba(201, 207, 212, 0.8);
        color: #333;
    }

    h1, h3 {
        text-align: center;
        color:rgb(0, 4, 8);
        font-size: 18px;
    }

    form {
        max-width: 100%;
        margin: 0 auto;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #boton-facturar {
    position: fixed;
    top: 20px; /* Ajusta la distancia desde la parte inferior */
    right: 10px;  /* Ajusta la distancia desde la derecha */
    padding: 10px 20px;
    background-color: #4CAF50; /* Color de fondo del botón */
    color: white; /* Color del texto */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra para dar profundidad */
}

#boton-facturar:hover {
    background-color: #45a049; /* Color al pasar el ratón */
}

#boton-facturar:focus {
    outline: none; /* Elimina el borde al hacer clic */
}


    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
        margin:5px 0;
        text-align: left;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color:rgb(75, 74, 74);
        color: white;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e9ecef;
    }

    /* Inputs dentro de la tabla */
    input[type="number"] {
        width: 80px;
        padding: 5px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        text-align: center;
    }
        #boton-facturar i {
            margin-right: 8px;  /* Espacio entre el icono y el texto */
        }

       /* Contenedor para centrar ambos elementos */
#search-container {
    display: flex;
    justify-content: center; /* Centra los elementos horizontalmente */
    align-items: center; /* Centra los elementos verticalmente */
    margin: 20px 0;
    gap: 10px; /* Espaciado entre elementos */
}

/* Estilos para el campo de búsqueda */
input[type="text"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    margin-right: 10px; /* Espacio entre el campo y el botón */
    width: 300px; /* Ancho del campo de búsqueda */
    text-align: center;
}

/* Estilos para el botón de limpiar */
button[type="button"] {
    background-color:rgb(236, 149, 142); /* Color de fondo rojo */
    color: white; /* Color del texto */
    border: none;
    padding: 8px 10px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    transition: background-color 0.3s ease;
    text-align: center;
    
}

#id {
    background-color:rgba(108, 180, 117, 0.85); 
}

#id:hover{
    background-color:rgb(46, 124, 31); 
}

/* Efecto hover para el botón */
button[type="button"]:hover {
    background-color: #d32f2f; /* Color de fondo al pasar el ratón */
}


</style>