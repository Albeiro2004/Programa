<?php
session_start();
include 'database1.php'; // Conexión a la base de datos

$mensajeError = ''; // Variable para almacenar mensajes de error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data); 
        $data = htmlspecialchars($data);
        return $data;
    }

    $usuario = validate($_POST['usuario']);  // Cambié a 'username'
    $clave = validate($_POST['clave']);    // Cambié a 'password'

    if (empty($usuario)) {
        $mensajeError = 'El usuario es requerido.';
    } elseif (empty($clave)) {
        $mensajeError = 'La clave es requerida.';
    } else {
        // Consulta en la base de datos
        $Sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'";
        $result = mysqli_query($conexion, $Sql);

        if (mysqli_num_rows($result)===1) {
          
            $row = mysqli_fetch_assoc($result);

            if ($row['usuario'] === $usuario && $row['clave']=== $clave) {
                // Inicio de sesión exitoso
                $_SESSION['usuario'] = $row['usuario'];
                echo "<script>window.location.href = 'index1.php';</script>";
                exit();
            } else {
                $mensajeError = 'La contraseña es incorrecta.';
            }
        } else {
            $mensajeError = 'Usuario no encontrado.';
        }
        $conexion->close();
    }
}

?>

</script>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/cerrarSesion.png" type="image/x-icon">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="estiloSesion.css">
    <script>
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>

    <script>
        // Función para ocultar el mensaje de error después de 3 segundos
        function ocultarMensaje() {
            const mensaje = document.getElementById('mensaje-error');
            if (mensaje) {
                setTimeout(() => {
                    mensaje.style.display = 'none';
                }, 3000);
            }
        }
        // Llama a la función al cargar la página
        window.onload = ocultarMensaje;
    </script>
</head>
<body>
    <form action="" method="POST">
        <h1>Iniciar Sesión</h1>
        
        <!-- Mostrar mensaje de error -->
        <?php if (!empty($mensajeError)): ?>
            <p id="mensaje-error" style="color: red; font-weight: bold; font-size: 12px;"><?= $mensajeError ?></p>
        <?php endif; ?>
        
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" value="" required>
        <br>
        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" value="" required>
        <br>
        <div class="button-container">
          <br>
          <button type="submit">Ingresar</button>
          <button class="error" type="reset">Borrar</button>
        </div>
    </form>
</body>
</html>
