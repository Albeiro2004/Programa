<?php

include '../database1.php';

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $concepto = $conexion->real_escape_string($_POST['concepto']);
    $monto = floatval($_POST['monto']);
    $fecha = $_POST['fecha'];
    $observaciones = $conexion->real_escape_string($_POST['observaciones']);

    if ($concepto && $monto > 0 && $fecha) {
        $sql = "INSERT INTO egresos (concepto, monto, fecha, observaciones)
              VALUES ('$concepto', $monto, '$fecha', '$observaciones')";
        if ($conexion->query($sql)) {
            header("Location: egreso.php?success=1");
            exit();
        } else {
            $mensaje = "Error al guardar: " . $conexion->error;
        }
    } else {
        $mensaje = "Por favor completa todos los campos requeridos.";
    }
}

// --- Obtener egresos registrados ---
$registros = $conexion->query("SELECT * FROM egresos ORDER BY fecha DESC LIMIT 10;");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Egresos</title>

</head>

<body>

    <h2>Gestión de Egresos</h2>

    <div class="container">
        <!-- Formulario -->
        <div class="form-container">
            <h3>Registrar Egreso</h3>

            <?php if (isset($_GET['success'])): ?>
                <div class="mensaje" id="mensaje-exito">✅ Egreso registrado exitosamente.</div>
            <?php elseif (!empty($mensaje)): ?>
                <div class="mensaje error" id="mensaje-error">⚠️ <?= $mensaje ?></div>
            <?php endif; ?>


            <form method="POST" action="">
                <label for="concepto">Concepto</label>
                <select name="concepto" id="concepto" required>
                    <option value="">Seleccionar</option>
                    <option value="Arriendo">Arriendo</option>
                    <option value="Servicios Públicos">Servicios Públicos</option>
                    <option value="Compra de Herramientas">Compra de Herramientas</option>
                    <option value="Otros">Otros</option>
                </select><br>

                <label for="monto">Monto</label>
                <input type="number" name="monto" id="monto" step="0.01" min="0" required><br>

                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" required><br>

                <label for="observaciones">Observaciones</label>
                <textarea name="observaciones" id="observaciones" rows="3"></textarea>

                <button type="submit">Guardar Egreso</button>
            </form>
        </div>

        <!-- Tabla -->
        <div class="table-container">
            <h3>Últimos 10 Egresos Registrados</h3>
            <?php if ($registros->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $registros->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($fila['fecha']) ?></td>
                                <td><?= htmlspecialchars($fila['concepto']) ?></td>
                                <td>$ <?= number_format($fila['monto'], 2) ?></td>
                                <td><?= htmlspecialchars($fila['observaciones']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-registros">No hay egresos registrados aún.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Ocultar mensaje después de 5 segundos
        setTimeout(() => {
            const exito = document.getElementById("mensaje-exito");
            const error = document.getElementById("mensaje-error");
            if (exito) exito.style.display = "none";
            if (error) error.style.display = "none";
        }, 5000);
    </script>

</body>

</html>

<?php $conexion->close(); ?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-image: url('../images/fondoProducto.jpg'); 
        background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
        background-position: center;  /* Centra la imagen */
        background-repeat: no-repeat; /* Evita que la imagen se repita */
        margin: 0;
        padding: 20px;
    }

    h2 {
        margin-top: 1px;
        margin-bottom: 25px;
        background-color: #d48b02;
        border-radius: 20px;
        text-align: center;
        color: black;
        padding: 5px;
    }

    h3 {
        text-align: center;
        color:rgb(80, 80, 80);
    }

    .container {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .form-container,
    .table-container {
        background: whitesmoke;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-container {
        width: 30%;
        height: 500px;
        
    }

    .table-container {
        width: 60%;
        height: 500px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    input,
    textarea,
    select {
        width: 95%;
        padding: 8px;
        margin-bottom: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 20px;
        margin-top: 10px;

    }

    button {
        background: #d48b02;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        width: 40%;
        display: block;
        margin: 0 auto;
    }

    button:hover {
        background:rgb(252, 190, 77);
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
    }

    th,
    td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    thead th {
        position: sticky;
        top: 0;
        background: #d48b02;
        color: black;
        z-index: 2;

    }

    th {
        background: #d48b02;
        color: white;
        position: sticky;
        top: 0;
    }

    .no-registros {
        text-align: center;
        margin-top: 10px;
        color: #555;
    }

    .mensaje {
        padding: 5px;
        margin-bottom: 15px;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }
</style>