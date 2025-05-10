<?php
include 'database1.php'; // Conexión a la base de datos

// Procesar la actualización del inventario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idProducto'], $_POST['cantidadAgregar'])) {
    $idProducto = $_POST['idProducto'];
    $cantidadAgregar = intval($_POST['cantidadAgregar']);

    // Actualizar la cantidad en inventario
    $updateQuery = "UPDATE inventario SET cantidadDisponible = cantidadDisponible + ? WHERE idProducto = ?";
    $stmt = $conexion->prepare($updateQuery);
    $stmt->bind_param("is", $cantidadAgregar, $idProducto);
    $stmt->execute();
    $stmt->close();

    echo "<script>
    localStorage.setItem('clave', Date.now());
    window.location.href = 'UpdateInventario.php?actualizado=1';
    </script>";
    exit(); 
    /*echo "<script>localStorage.setItem('inventarioActualizado', 'true');</script>";
    // Redirigir con mensaje de confirmación
    header("Location: UpdateInventario.php?actualizado=1");
    exit();*/
}

// Obtener todos los productos con su cantidad disponible
$query = "SELECT producto.idProducto, producto.nombre, producto.marca, inventario.cantidadDisponible FROM producto 
          JOIN inventario ON producto.idProducto = inventario.idProducto ORDER BY producto.idProducto ASC";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/png" href="images/actualizar.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
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

    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has("actualizado")) {
            let mensaje = document.getElementById("mensaje");
            if (mensaje) {
                mensaje.style.display = "block"; // Mostrar mensaje
                
                setTimeout(() => {
                    mensaje.style.display = "none"; // Ocultarlo después de 2s
                    window.history.replaceState({}, document.title, window.location.pathname);
                }, 2000);
            }
        }
    });
    </script>
</head>
<body>
    <h1>Inventario</h1> 

    <div id="mensaje">Cantidad actualizada correctamente</div>

    <input type="text" id="search" onkeyup="buscarProducto()" placeholder="Buscar por código o nombre">
    
    <div class="table-container">
    <table id="tablaProductos">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Cantidad Disponible</th>
                <th>Agregar</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= str_pad(htmlspecialchars($row['idProducto']), 4, "0", STR_PAD_LEFT) ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['marca']; ?></td>
                    <td><?php echo $row['cantidadDisponible']; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="idProducto" value="<?php echo $row['idProducto']; ?>">
                            <input type="number" name="cantidadAgregar" min="1" required>
                            <button type="submit"><img src="images/subir.png" alt="Actualizar" ></button>

                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</body>
</html>

<?php
$conexion->close();
?>


<STyle>
    body {
    font-family: Arial, sans-serif;
    margin: 20px;
    padding: 0;
    background-color: #f8f9fa;
    background-image: url('images/bag2.jpg'); 
    background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
    background-position: center;  /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
}

h1 {
    text-align: center;
    font-size: 28px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    
    /* Degradado en el texto */
    background: linear-gradient(to right, #555, #333);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;

    /* Sombra sutil */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);

    /* Animación de entrada */
    opacity: 0;
    transform: translateY(-20px);
    animation: fadeIn 1s ease-in-out forwards;
}

/* Definir animación de entrada */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

input[type="text"] {
    width: 100%;
    max-width: 300px;
    padding: 8px;
    margin: 10px auto;
    display: block;
    border: 1px solid #ccc;
    border-radius: 4px;
    text-align: center;
}

.table-container {
    max-height: 450px; /* Ajusta la altura según necesites */
    overflow-y: auto; /* Habilita el scroll solo en la tabla */
   
     /* Crear efecto de degradado en las orillas */
     mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), black 5%, black 95%, rgba(0, 0, 0, 0.1));
    -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), black 5%, black 95%, rgba(0, 0, 0, 0.1));
}

/* Personalizar el scroll para que sea más dinámico */
.table-container::-webkit-scrollbar {
    width: 8px;
}

.table-container::-webkit-scrollbar-thumb {
    background: rgba(100, 100, 100, 0.5);
    border-radius: 10px;
    transition: background 0.3s;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: rgba(100, 100, 100, 0.8);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: white;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

thead {
    position: sticky;
    top: 0;
    background-color: #eee; /* Fijar color de fondo */
    z-index: 100;
}

th {
    background-color: #ddd; /* Asegurar que el encabezado sea visible */
    font-weight: bold;
    padding: 10px;
    border: 1px solid #bbb;
}

tr:hover {
    background-color: #f1f1f1;
}

input[type="number"] {
    width: 60px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    padding: 5px 10px;
    border: none;
    background-color: #555;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}

button:hover {
    background-color: #333;
}
#mensaje {
    display: none;
    text-align: center;
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    margin: 10px auto; /* Centrar horizontalmente */
    border-radius: 4px;
    border: 1px solid #c3e6cb;
    width: 30%;
}

.show {
    display: block;
}
button img {
    width: 20px;  /* Ajusta el tamaño del icono */
    height: 20px;
    vertical-align: middle; /* Alinear el icono verticalmente */
}


</STyle>
