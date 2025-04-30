<?php
include 'database1.php'; // Conexión a la base de datos

$mensaje = "";

// Eliminar producto si se recibe el ID por POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['idProducto'])) {
    $idProducto = $_POST['idProducto'];

    // Verificar si el producto está en una factura
    $queryVerificar = "SELECT COUNT(*) AS total FROM detalle_factura WHERE idProducto = ?";
    $stmtVerificar = $conexion->prepare($queryVerificar);
    $stmtVerificar->bind_param("s", $idProducto);
    $stmtVerificar->execute();
    $resultado = $stmtVerificar->get_result();
    $fila = $resultado->fetch_assoc();

    if ($fila['total'] > 0) {
        // Si el producto está en una factura, no permitir eliminar
        header("Location: ".$_SERVER['PHP_SELF']."?mensaje=No se puede eliminar: el producto está en una factura");
        exit();
    }

    // Si no está en una factura, proceder con la eliminación
    $conexion->begin_transaction();
    try {
        // Eliminar del inventario
        $queryInventario = "DELETE FROM inventario WHERE idProducto = ?";
        $stmtInventario = $conexion->prepare($queryInventario);
        $stmtInventario->bind_param("s", $idProducto);
        $stmtInventario->execute();

        // Eliminar del producto
        $queryProducto = "DELETE FROM producto WHERE idProducto = ?";
        $stmtProducto = $conexion->prepare($queryProducto);
        $stmtProducto->bind_param("s", $idProducto);
        $stmtProducto->execute();

        // Confirmar transacción
        $conexion->commit();
        // Redirigir para evitar reenvío del formulario
        header("Location: ".$_SERVER['PHP_SELF']."?mensaje=Producto eliminado correctamente");
        exit();
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $conexion->rollback();
        header("Location: ".$_SERVER['PHP_SELF']."?mensaje=Error al eliminar el producto");
        exit();
    }
}

// Capturar el mensaje de la URL
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}

// Consultar los productos existentes
$query = "SELECT * FROM producto";
$result = $conexion->query($query);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Producto</title>
</head>
<body>

    <!-- Mostrar mensaje de éxito o error -->
    <?php if (!empty($mensaje)): ?>
    <div class="mensaje" id="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <div class="contenedor-titulo">
    <h1 class="titulo-eliminar">Eliminar Producto</h1>
    </div>

    <div class="search-container">
        <input type="text" id="search" onkeyup="buscarProducto()" placeholder="Buscar por código o nombre">
        <button class="limpiar" onclick="limpiarBusqueda()">✖</button>
    </div>

    <!-- Tabla de productos -->
    <table id="tablaProductos">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Marca</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['idProducto']) ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['precio']) ?></td>
                <td><?= htmlspecialchars($row['marca']) ?></td>
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="idProducto" value="<?= htmlspecialchars($row['idProducto']) ?>">
                        <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                            <img src="images/eliminar.png">
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<script>
    function buscarProducto() {
            let input = document.getElementById("search").value.toLowerCase();
            let filas = document.querySelectorAll("#tablaProductos tbody tr");

            filas.forEach(fila => {
                let codigo = fila.cells[0].innerText.toLowerCase();
                let nombre = fila.cells[1].innerText.toLowerCase();

                if (codigo.includes(input) || nombre.includes(input)) {
                    fila.style.display = "";
                } else {
                    fila.style.display = "none";
                }
            });
        }

    function limpiarBusqueda() {
    document.getElementById("search").value = "";
    buscarProducto(); // Llamar la función para actualizar la búsqueda
    }

    // Espera 2 segundos y luego oculta el mensaje
    setTimeout(function() {
        var mensajeDiv = document.getElementById("mensaje");
        if (mensajeDiv) {
            mensajeDiv.style.display = "none";
            // Opcional: Resetear el contenido del div
            mensajeDiv.innerHTML = "";
        }
    }, 2000);


</script>
  
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 20px;
        color: #333;
        background-image: url('images/fondoProducto.jpg'); 
        background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
        background-position: center;  /* Centra la imagen */
        background-repeat: no-repeat; /* Evita que la imagen se repita */
        align-items: center;
    }
    .mensaje {
    position: fixed;
    top: 10px;  /* Separación desde la parte superior */
    left: 50%;
    transform: translateX(-50%); /* Centrar horizontalmente */
    width: 30%;
    color: black;
    font-weight: bold;
    text-align: center;
    font-size: 14px;
    border-radius: 10px;
    background-color: rgba(0, 255, 0, 0.28);
    padding: 8px;
    z-index: 1000; /* Asegura que esté por encima del contenido */
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2); /* Sombra para resaltar */
}

.contenedor-titulo {
        display: flex;
        justify-content: center; /* Centrar horizontalmente */
        align-items: center;
        margin-top: -15px;
    }

    .titulo-eliminar {
        font-size: 28px;
        font-weight: bold;
        text-align: center;
        color: #D32F2F; /* Rojo elegante */
        text-transform: uppercase;
        letter-spacing: 2px;
        padding: 10px 10px;
        background: linear-gradient(to right, #ff5252, #d32f2f);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        border-bottom: 3px solid #d32f2f;
        display: inline-block;
        margin: 20px 0px;
        font-family: "Poppins", sans-serif;
    }

        h2{
            width: 30%; /* Definir un ancho para el h2 */
            margin: 0 auto; /* Márgenes automáticos a los lados */
            text-align: center; /* Alinear el texto al centro */
        }   
        
        .search-container {
    display: flex;
    align-items: center;  /* Alinea los elementos verticalmente */
    justify-content: center;  /* Centra los elementos horizontalmente */
    gap: 5px;  /* Espacio entre el input y el botón */
    width: 100%;  /* Asegura que ocupe todo el ancho disponible */
}

        input#search {
        padding: 8px;
        width: 200px;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-align: center;
    }

   

    .limpiar {
        background: tomato;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px;
    }

    .limpiar:hover {
        background: darkred;
    }


    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        background-color: rgba(255, 255, 255, 0.5);
       
    }

    th, td {
        border: 2px solid black;
        text-align: center;
    }

    th{
        padding: 10px;
        font-size: 16px;
    }
    td{
        padding: 2px;
        font-size: 14px;
    }
    th {
        background-color: tomato;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color:rgba(231, 72, 72, 0.66);
    }

    button {
        background-color: rgba(255, 255, 255, 0.5);
        color: white;
        border: none;
        padding: 3px 3px;
        border-radius: 4px;
        cursor: pointer;
    }
    button img, button svg {
  width: 16px;
  height: 16px;
}

    button:hover {
        background-color: darkred;
    }

    form {
        margin: 0;
        display: inline;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
    }
</style>
