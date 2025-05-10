<?php
include 'database1.php'; // Asegúrate de tener un archivo para la conexión a la BD

$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : "";

// Obtener lista de trabajadores
$trabajadores = $conexion->query("SELECT * FROM trabajador");

// Obtener servicios del día
$servicios = $conexion->query("SELECT s.descripcion, s.costo, t.nombre, s.fecha, s.codServicio AS iden FROM servicio s JOIN trabajador t ON s.idTrabajador = t.identidad WHERE DATE(s.fecha) = CURDATE()");

// Obtener liquidación del día
$liquidacion = $conexion->query("
    SELECT t.identidad AS idTrabajador, t.nombre, 
           SUM(s.costo * 0.7) AS pago_trabajador, 
           SUM(s.costo * 0.3) AS ingreso_taller 
    FROM servicio s 
    JOIN trabajador t ON s.idTrabajador = t.identidad 
    WHERE DATE(s.fecha) = CURDATE() 
    GROUP BY t.identidad
");

// Registrar servicio si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descripcion = $_POST['descripcion'];
    $costo = $_POST['costo'];
    $idTrabajador = $_POST['idTrabajador'];

    if ($idTrabajador == "") {
        echo "<script>window.location.href = 'servicio.php?mensaje=Por favor, debe seleccionar un trabajador.';</script>"; 
    } else {
    $sql = "INSERT INTO servicio (descripcion, costo, idTrabajador, fecha) VALUES (?, ?, ?, NOW())";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sds", $descripcion, $costo, $idTrabajador);
    
    if ($stmt->execute()) {
        echo "<script>window.location.href = 'servicio.php?mensaje=Servicio registrado correctamente.';</script>"; 
        exit(); 
        }
    }
}

if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $idSer = $_GET['eliminar'];

    $sql = "DELETE FROM servicio WHERE codServicio = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idSer);
    
    if ($stmt->execute()) {
        echo "<script>window.location.href = 'servicio.php?mensaje=Servicios eliminados correctamente.';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro de Servicios</title>
</head>
<body>

     <!-- Contenedor del mensaje -->
     <?php if (!empty($mensaje)) { ?>
    <div class="mensaje" id="mensaje-box">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
    <?php } ?>
    
    <form method="POST">
        <label>Descripción:</label>
        <input type="text" name="descripcion" required>
        <label>Costo:</label>
        <input type="number" name="costo" step="0.01" required>
        <label>Trabajador:</label>
        <select name="idTrabajador" required>
        <option value="">Seleccionar</option>
            <?php while ($row = $trabajadores->fetch_assoc()) { ?>
                <option value="<?php echo $row['identidad']; ?>"> <?php echo $row['nombre']; ?> </option>
            <?php } ?>
        </select>
        <br>
        <button type="submit">Registrar</button>
    </form>

    <h2>Liquidación del Día</h2>
    <table border="1">
        <tr><th>Trabajador</th><th>Pago</th><th>Ingreso Taller</th></tr>
        <?php while ($row = $liquidacion->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['nombre']; ?></td>
                <td>$ <?= number_format(htmlspecialchars($row['pago_trabajador']), 0, '.', '.') ?></td>
                <td>$ <?= number_format(htmlspecialchars($row['ingreso_taller']), 0, '.', '.') ?></td>
            </tr>
        <?php } ?>
    </table>

    <h2>Servicios del Día</h2>
    <table border="1">
        <tr><th>Descripción</th><th>Costo</th><th>Trabajador</th><th>Fecha</th><th>Delete</th></tr>
        <?php while ($row = $servicios->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['descripcion']; ?></td>
                <td>$ <?= number_format(htmlspecialchars($row['costo']), 0, '.', '.') ?></td>
                <td><?php echo htmlspecialchars($row['nombre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></td>
                <td><?php echo $row['fecha']; ?></td>
                <td>
                <a href="servicio.php?eliminar=<?php echo $row['iden']; ?>" 
                onclick="return confirm('¿Seguro que deseas eliminar este servicio?');">
                    🗑️</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    
</body>
</html>

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

<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    text-align: center;
    background-image: url('images/fondoVerde.jpg'); 
    background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
    background-position: center;  /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
}

form {
    background:rgba(235, 235, 235, 0.86);
    padding: 20px;
    margin: 20px auto;
    width: 70%;
    max-width: 400px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    color: #333;
    font-size: 20px;
    text-transform: uppercase;
    letter-spacing: 2px;
    background: linear-gradient(to right,rgb(255, 255, 255),rgb(97, 97, 97));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: fadeIn 1s ease-in-out;
    
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}


label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
    font-size: 14px;
}

input, select {
    width: 80%;
    padding: 5px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
}

button {
    background: #28a745;
    color: white;
    border: none;
    padding: 5px 10px;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 12px;
}

button:hover {
    background: #218838;
}

table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
}

th, td {
    padding: 5px;
    border: 1px solid #ddd;
    text-align: center;
    font-size: 14px;
}

th {
    background:rgba(72, 175, 115, 0.82);
    color: white;
}

tr:nth-child(even) {
    background: #f2f2f2;
}

@media (max-width: 600px) {
    table, th, td {
        font-size: 14px;
    }
}

.mensaje {
    position: fixed;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 30%;
    background-color: rgba(63, 199, 0, 0.49);
    color: black;
    text-align: center;
    padding: 8px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 15px;
    z-index: 1000;
    opacity: 1;
    transition: opacity 0.5s ease-in-out;
}

</style>