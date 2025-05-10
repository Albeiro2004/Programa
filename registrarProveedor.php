<?php
include 'database1.php'; // Conexión a la base de datos

// Obtener los proveedores desde la base de datos
$query = "SELECT codProveedor, nombre FROM proveedor";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Proveedor</title>
    <link rel="stylesheet" href="stylesProveedor.css">
</head>
<body>
    <h1>Registrar Proveedor</h1>
    <form action="guardarProveedor.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>

        <label for="contacto">Contacto:</label>
        <input type="text" id="contacto" name="contacto" required>
        
        <?php
        if(isset($_GET['mensaje'])){
          ?>
          <p class="mensaje"> 
            <?php
            echo $_GET['mensaje']
            ?>
          </p>
          <?php 
        }
        ?>
       
        <br><button type="submit">Registrar</button><br>
    </form>

    <?php
    // Cerrar la conexión
    $conexion->close();
    ?>
</body>
</html>