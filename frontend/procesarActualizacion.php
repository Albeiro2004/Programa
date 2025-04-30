<?php
include 'database1.php';

if (isset($_GET['idProducto'])) {
    $codProducto = $_GET['idProducto'];

    // Consulta con JOIN para obtener datos del producto e inventario
    $query = "SELECT p.idProducto, p.nombre, p.precio, p.precioCompra,p.marca, 
                     i.cantidadDisponible, i.ubicacion 
              FROM producto p
              JOIN inventario i ON p.idProducto = i.idProducto
              WHERE p.idProducto = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $codProducto);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codProducto = $_POST['idProducto'];
    $nombre = $_POST['nombre'];
    $precioCompra = $_POST['precioCompra'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $ubicacion = $_POST['ubicacion'];

    // Actualizar datos en la tabla producto
    $updateProducto = "UPDATE producto SET nombre = ?, precio = ?, precioCompra = ? 
                       WHERE idProducto = ?";
    $stmt1 = $conexion->prepare($updateProducto);
    $stmt1->bind_param("sdds", $nombre, $precio, $precioCompra, $codProducto);
    $stmt1->execute();

    // Actualizar datos en la tabla inventario
    $updateInventario = "UPDATE inventario SET cantidadDisponible = ?, ubicacion = ? WHERE idProducto = ?";
    $stmt2 = $conexion->prepare($updateInventario);
    $stmt2->bind_param("iss", $cantidad, $ubicacion, $codProducto);
    $stmt2->execute();

    // Redirigir al listado de productos después de la actualización
    header("Location: actualizarProducto.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>
    <form method="POST">
        <input type="hidden" name="idProducto" value="<?= $producto['idProducto'] ?>">
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?= $producto['nombre'] ?>" required><br><br>

        <label for="precioCompra">Precio Compra:</label>
        <input type="number" id="precioCompra" name="precioCompra" value="<?= $producto['precioCompra'] ?>" step="0.01" required><br><br>

        <label for="precio">Precio Venta:</label>
        <input type="number" id="precio" name="precio" value="<?= $producto['precio'] ?>" step="0.01" required><br><br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" value="<?= $producto['cantidadDisponible'] ?>" required><br><br>

        <label for="ubicacion">Ubicación:</label>
        <input type="text" id="ubicacion" name="ubicacion" value="<?= $producto['ubicacion'] ?>" required><br><br>

        <button type="submit">Actualizar</button>
    </form>
</body>
</html>



<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
    color: #333;
}

h1 {
    text-align: center;
    color: #444;
    margin-top: 20px;
    margin-bottom: 20px;
}

/* Form styles */
form {
    background-color: #fff;
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
}

input[type="text"],
input[type="number"] {
    width: calc(100% - 20px);
    padding: 8px;
    margin-bottom: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

button[type="submit"] {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    text-transform: uppercase;
    font-weight: bold;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

/* Responsive Design */
@media (max-width: 600px) {
    form {
        width: 90%;
    }

    input[type="text"],
    input[type="number"] {
        width: calc(100% - 10px);
    }
}

    </style>

