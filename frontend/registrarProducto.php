<?php
include 'database1.php'; // Conexión a la base de datos

// Obtener los proveedores desde la base de datos
$query = "SELECT codProveedor, nombre FROM proveedor";
$result = $conexion->query($query);

$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : "";

// Si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precioCompra = $_POST['precioCompra'];
    $precio = $_POST['precio'];
    $marca = $_POST['marca'];
    $proveedor = trim($_POST['proveedor']); // El nombre del proveedor
    $cantidad = $_POST['cantidad'];
    $ubicacion = $_POST['zona'];

    // Validar que los campos obligatorios no estén vacíos
    if (empty($codigo) || empty($nombre) || empty($precio) || empty($marca) || empty($proveedor) || empty($cantidad) || empty($ubicacion)) {
        echo "<script>window.location.href='registrarProducto.php?mensaje=Por favor, completa todos los campos.';</script>";
        exit();
    }

    $stmtProveedor = $conexion->prepare("SELECT codProveedor FROM proveedor WHERE nombre = ?");
    $stmtProveedor->bind_param("s", $proveedor);
    $stmtProveedor->execute();
    $stmtProveedor->bind_result($codProveedor);
    $stmtProveedor->fetch();
    $stmtProveedor->close();


   

    // Insertar el producto en la base de datos
    $stmt = $conexion->prepare("INSERT INTO producto (idProducto, nombre, precio, precioCompra,idProveedor, marca) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssddis", $codigo, $nombre, $precio, $precioCompra, $proveedor, $marca);
        $stmtInventario = $conexion->prepare("INSERT INTO inventario (idProducto, cantidadDisponible, ubicacion) VALUES (?, ?, ?)");

        if ($stmtInventario) {
            $stmtInventario->bind_param("sis", $codigo, $cantidad, $ubicacion);

            if ($stmt->execute() && $stmtInventario->execute()) {
                echo "<script>
                localStorage.setItem('inventarioActualizado', Date.now());
                window.location.href='registrarProducto.php?mensaje=Producto Registrado Exitosamente';
                </script>";
                exit();
            } else {
                echo "<script>window.location.href='registrarProducto.php?mensaje=Error al Registrar';</script>";
                exit();
            }

            $stmtInventario->close();
        } else {
            echo "<script>window.location.href='registrarProducto.php?mensaje=Error al preparar la consulta de inventario.';</script>";
            exit();
        }

        $stmt->close();
    } else {
        echo "<script>window.location.href='registrarProducto.php?mensaje=Error al preparar la consulta de producto.';</script>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Producto</title>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
    let mensaje = document.getElementById("mensaje-box");
    if (mensaje) {
        mensaje.style.display = "block"; // Mostrar el mensaje
        setTimeout(() => {
            mensaje.style.opacity = "0"; // Efecto de desvanecimiento
            setTimeout(() => {
                mensaje.style.display = "none"; // Ocultarlo después del desvanecimiento
            }, 500);
        }, 2000);
    }
});

</script>

</head>
<body>
    <!-- Contenedor del mensaje -->
    <?php if (!empty($mensaje)) { ?>
    <div class="mensaje" id="mensaje-box">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php } ?>
    
    <form action="" method="post">
        <h1>Detalles del Nuevo Producto</h1>

        <label for="codigo">Código:</label>
        <input type="text" id="codigo" name="codigo" required>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="precioCompra">Precio Comprado:</label>
        <input type="number" id="precioCompra" name="precioCompra" step="0.01" required>

        <label for="precio">Precio a Vender:</label>
        <input type="number" id="precio" name="precio" step="0.01" required>

        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" required>

        <label for="proveedor">Proveedor:</label>
        <select id="proveedor" name="proveedor" required>
            <option value="">Seleccione un proveedor</option>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['codProveedor'] . "'>" . $row['nombre'] . "</option>";
                }
            } else {
                echo "<option value=''>No hay proveedores disponibles</option>";
            }
            ?>
        </select>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" required>

        <label for="zona">Ubicación:</label>
        <input type="text" id="zona" name="zona" required>

        <button type="submit">Registrar Producto</button>
        <button class="salir" type="button" onclick="window.close();">Salir</button>
    </form>
    <?php $conexion->close(); ?>
</body>
</html>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

/* General */
body {
    font-family: 'Poppins' , sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
    background-image: url('images/fondoProducto.jpg'); 
    background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
    background-position: center;  /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
}

h1 {
    text-align: center;
    color: #4CAF50;
    padding-top: 10px;
    font-size: 14px;
}

/* Formulario */
form {
    
    width: 30%;
    max-height: 630px;
    margin: 10px auto;
    padding: 5px;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: rgba(187, 185, 185, 0.9); 
    display: grid;
    place-items: center;
    margin-bottom: -5px;

}

label {
    display: block;
    font-size: small;
    margin: 10px 0 0px;
    color: #333;
}


input, select, textarea {
    
    width: 70%;
    padding: 3px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: small;
    text-align: center;
}

textarea {
    resize: vertical;
    height: 80px;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    text-align: center;
}

/* Botón de envío */
button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 5px 5px;
    font-size: 12px;
    border-radius: 4px;
    cursor: pointer;
    width: 70%;
    transition: background-color 0.3s ease;
    margin-top: 10px; /* Espacio antes del botón */
}

.salir{
    background-color:rgba(175, 76, 76, 0.79);
    color: white;
    border: none;
    padding: 5px 5px;
    font-size: 12px;
    border-radius: 4px;
    cursor: pointer;
    width: 60%;
    transition: background-color 0.3s ease;
    margin-top: 10px; /* Espacio antes del botón */

}
#bw {
    background-color: #f78c84; /* Color de fondo rojo */
    color: white; /* Texto en blanco */
    border: none; /* Sin borde */
    padding: 5px 5px; /* Espaciado interno */
    text-align: center; /* Alineación de texto */
    text-decoration: none; /* Sin subrayado */
    display: inline-block; /* Alineación en línea */
    font-size: 10px; /* Tamaño de fuente */
    cursor: pointer; /* Puntero al pasar el mouse */
    border-radius: 5px; /* Bordes redondeados */
    transition: background-color 0.3s ease, transform 0.2s; /* Efectos de transición */
    width: 50%;
}
button:hover {
    background-color: #45a049;
}

/* Mensajes */
p.error, p.success {
    text-align: center;
    font-size: 1.1rem;
    padding: 10px;
    margin-top: 10px;
    border-radius: 5px;
}

p.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

p.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.mensaje {
    position: fixed;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 30%;
    background-color: rgba(97, 155, 97, 0.65);
    color: white;
    text-align: center;
    padding: 10px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 15px;
    z-index: 1000;
    opacity: 1;
    transition: opacity 0.5s ease-in-out;
}


    </style>