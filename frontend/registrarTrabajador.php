<?php
include 'database1.php'; // Conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Empleado</title>
    <link rel="stylesheet" href="stylesTrabajador.css">
</head>
<body>
    <h1>Registrar Trabajador</h1>
    <form action="guardarTrabajador.php" method="post">
        
        <label for="identidad">Cédula de Ciudadanía:</label>
        <input type="number" id="identidad" name="identidad" required>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="puesto">Puesto:</label>
        <select id="puesto" name="puesto" required>
        <option value="">Seleccione</option>
        <option value="Mecánico">Mecánico</option>
        <option value="Ventas">Ventas</option>
        <option value="Administrador">Administrador</option>
        </select>

        <label for="sueldo">Sueldo:</label>
        <input type="number" id="sueldo" name="sueldo">
        
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