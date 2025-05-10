<?php
include 'database1.php'; // Conexión a la base de datos

// Obtener filtros de fecha
$tipo_filtro = isset($_GET['tipo_filtro']) ? $_GET['tipo_filtro'] : 'dia';
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('Y-m');
$ano = isset($_GET['ano']) ? $_GET['ano'] : date('Y');
$semana = isset($_GET['semana']) ? $_GET['semana'] : date('Y-\WW');

// Definir la consulta según el filtro seleccionado
if ($tipo_filtro == 'dia') {
    $query = "WHERE DATE(s.fecha) = '$fecha'";
} elseif ($tipo_filtro == 'mes') {
    $query = "WHERE DATE_FORMAT(s.fecha, '%Y-%m') = '$mes'";
} elseif ($tipo_filtro == 'ano') {
    $query = "WHERE YEAR(s.fecha) = '$ano'";
} elseif ($tipo_filtro == 'semana') {
    // Convertir semana a fecha en formato correcto
    $primerDiaSemana = date('Y-m-d', strtotime($semana));
    $query = "WHERE YEARWEEK(s.fecha, 1) = YEARWEEK('$primerDiaSemana', 1)";
} else {
    $query = "";
}

// Consultas
$ingresoTaller = $conexion->query("SELECT SUM(s.costo * 0.3) AS ingreso_taller FROM servicio s $query")->fetch_assoc();
$servicios = $conexion->query("SELECT s.descripcion, s.costo, t.nombre, s.fecha FROM servicio s JOIN trabajador t ON s.idTrabajador = t.identidad $query");
$liquidacion = $conexion->query("SELECT t.nombre, SUM(s.costo * 0.7) AS pago_trabajador FROM servicio s JOIN trabajador t ON s.idTrabajador = t.identidad $query GROUP BY t.identidad");
$serviciosCostosos = $conexion->query("SELECT s.* FROM servicio s $query ORDER BY s.costo DESC LIMIT 5");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Reportes del Taller</title>
</head>
<body>
    
    <form method="GET">
        <label>Filtrar por:</label>
        <select name="tipo_filtro" onchange="this.form.submit()">
            <option value="dia" <?php if ($tipo_filtro == 'dia') echo 'selected'; ?>>Día</option>
            <option value="semana" <?php if ($tipo_filtro == 'semana') echo 'selected'; ?>>Semana</option>
            <option value="mes" <?php if ($tipo_filtro == 'mes') echo 'selected'; ?>>Mes</option>
            <option value="ano" <?php if ($tipo_filtro == 'ano') echo 'selected'; ?>>Año</option>
        </select>
        
        <?php if ($tipo_filtro == 'dia') { ?>
            <input type="date" name="fecha" value="<?php echo $fecha; ?>">
        <?php } elseif ($tipo_filtro == 'mes') { ?>
            <input type="month" name="mes" value="<?php echo $mes; ?>">
        <?php } elseif ($tipo_filtro == 'ano') { ?>
            <input type="number" name="ano" min="2000" max="<?php echo date('Y'); ?>" value="<?php echo $ano; ?>">
        <?php } elseif ($tipo_filtro == 'semana') { ?>
            <input type="week" name="semana" value="<?php echo $semana; ?>">
        <?php } ?>
        <br>
        <button type="submit">Filtrar</button>
    </form>

    <hr>
    <h3>Servicios</h3>
    <table border="1">
        <tr><th>Descripción</th><th>Costo</th><th>Trabajador</th><th>Fecha</th></tr>
        <?php while ($row = $servicios->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['descripcion']; ?></td>
                <td>$ <?php echo number_format($row['costo'], 0, ',', '.'); ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['fecha']; ?></td>
            </tr>
        <?php } ?>
    </table><br>
            <hr>
    <h3>Liquidación de Trabajadores</h3>
    <table border="1">
        <tr><th>Trabajador</th><th>Pago</th></tr>
        <?php while ($row = $liquidacion->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['nombre']; ?></td>
                <td>$ <?php echo number_format($row['pago_trabajador'], 0, ',', '.'); ?></td>
            </tr>
        <?php } ?>
    </table>
    <br>
    <hr>
    <h3>Ingreso del Taller</h3>
    <p>Total: <strong>$<?php echo number_format($ingresoTaller['ingreso_taller'] ?? 0, 0, ',', '.'); ?></strong></p>
    <br>
    <hr>
    <h3>Servicios Más Costosos</h3>
    <table border="1">
        <tr><th>Descripción</th><th>Costo</th></tr>
        <?php while ($row = $serviciosCostosos->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['descripcion']; ?></td>
                <td>$ <?php echo number_format($row['costo'], 0, ',', '.'); ?></td>
            </tr>
        <?php } ?>
    </table><br><br>
</body>
</html>



<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    text-align: center;
    background-image: url('images/fondoUser.jpg'); 
    background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
    background-position: center;  /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
}

h2, h3 {
    color: #333;
    text-transform: uppercase;
    letter-spacing: 2px;
    background: linear-gradient(to right, #007bff, #28a745);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: fadeIn 1s ease-in-out;
    margin-top: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
    display: inline-block;
    font-size: 17px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

form {
    background:rgba(231, 231, 231, 0.72);
    padding: 10px;
    margin: 20px auto;
    width: 80%;
    max-width: 500px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

label {
    display: block;
    margin-top: 2px;
    font-weight: bold;
    font-size: 14px;
}

input {
    width: 80%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
}

button {
    background: #28a745;
    color: white;
    border: none;
    padding: 5px 15px;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    
}

button:hover {
    background: #218838;
}

.table-container {
    margin: 40px auto;
    padding: 20px;
    background: white;
    width: 90%;
    border-radius: 8px;
    box-shadow: 0 0 10px rgb(0, 0, 0);
}

table {
    width: 90%;
    margin: 0 auto;
    border-radius: 10px;

}

table, th, td {
    border: 1px solid rgba(102, 100, 100, 0.75);
}

th, td {
    padding: 5px;
    text-align: center;
    transition: all 0.3s ease-in-out;
    font-size: 14px;
}
th {
    background:rgb(78, 130, 185);
    color: white;
}
tr:nth-child(even) {
    background: #f2f2f2;
}

tr:hover {
    background: rgba(0, 123, 255, 0.2);
}

@keyframes borderGlow {
    from { box-shadow: 0 0 10px rgba(0, 123, 255, 0.5); }
    to { box-shadow: 0 0 20px rgba(0, 123, 255, 0.8); }
}

p {
    font-size: 18px;
    font-weight: bold;
    animation: textFadeIn 1s ease-in-out;
}

@keyframes textFadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
select {
    width: 40%;
    padding: 5px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
}
</style>