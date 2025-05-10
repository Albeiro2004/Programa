<?php
include 'database1.php'; // Conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="stylesUser.css">
</head>
<body>
     <h1>Registro de Usuario</h1><br>
    <form action="guardarUsuario.php" method="post">
      
        <label for="usuario">Nombre de Usuario:</label>
        <input type="text" name="usuario" id="usuario" required><br><br>
        
        <label for="clave">Contraseña:</label>
        <input type="password" name="clave" id="clave" required><br><br>
        
        <label for="confirClave">Confirmar Contraseña:</label>
        <input type="password" name="confirClave" id="confirClave" required><br>
        
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

        <input type="submit" value="Registrarse"><br>
    </form>
</body>
</html>
