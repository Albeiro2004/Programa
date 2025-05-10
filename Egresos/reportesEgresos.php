<?php
include '../database1.php';

// Rango de fechas si se envió el formulario
$fecha_inicio = $_GET['inicio'] ?? '';
$fecha_fin = $_GET['fin'] ?? '';

$where_fecha = '';
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $where_fecha = "WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

// Consultas
$todos_los_egresos = $conexion->query("SELECT * FROM egresos $where_fecha ORDER BY fecha DESC");
$por_tipo = $conexion->query("SELECT concepto, SUM(monto) AS total FROM egresos $where_fecha GROUP BY concepto ORDER BY total DESC");
$por_mes = $conexion->query("SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, SUM(monto) AS total FROM egresos $where_fecha GROUP BY mes ORDER BY mes DESC");
$condicion = empty($where_fecha) ? "WHERE monto > 500000" : "$where_fecha AND monto > 500000";
$mayores_500 = $conexion->query("SELECT * FROM egresos $condicion ORDER BY monto DESC");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reportes de Egresos</title>

    <script>
        function mostrarReporte(id) {
            document.querySelectorAll('.reporte').forEach(r => r.classList.remove('active'));
            document.getElementById(id).classList.add('active');
        }
    </script>
</head>

<body>

    <h2>📅 Filtrar por rango de fechas</h2>
    <form method="GET">
        <label>Desde: <input type="date" name="inicio" value="<?= $fecha_inicio ?>"></label>
        <label>Hasta: <input type="date" name="fin" value="<?= $fecha_fin ?>"></label>
        <input type="submit" value="Filtrar">
    </form>

    <div class="nav-buttons">
        <button onclick="mostrarReporte('total')">Totales generales</button>
        <button onclick="mostrarReporte('tipo')">Por tipo</button>
        <button onclick="mostrarReporte('mes')">Por mes</button>
        <button onclick="mostrarReporte('mayores')">Mayores a $500.000</button>
    </div>

    <div class="reporte active" id="total">
        <table>
            <tr>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Monto</th>
                <th>Observaciones</th>
            </tr>
            <?php while ($row = $todos_los_egresos->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['fecha'] ?></td>
                    <td><?= htmlspecialchars($row['concepto']) ?></td>
                    <td>$<?= number_format($row['monto'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['observaciones']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

    </div>

    <div class="reporte" id="tipo">
        <h2>📊 Egresos agrupados por tipo</h2>
        <table>
            <tr>
                <th>Tipo</th>
                <th>Total</th>
            </tr>
            <?php while ($row = $por_tipo->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['concepto']) ?></td>
                    <td>$<?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="reporte" id="mes">
        <h2>📆 Total de egresos por mes</h2>
        <table>
            <tr>
                <th>Mes</th>
                <th>Total</th>
            </tr>
            <?php while ($row = $por_mes->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['mes'] ?></td>
                    <td>$<?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="reporte" id="mayores">
        <h2>💰 Egresos mayores a $500.000</h2>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Observaciones</th>
            </tr>
            <?php while ($row = $mayores_500->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['fecha'] ?></td>
                    <td><?= htmlspecialchars($row['concepto']) ?></td>
                    <td>$<?= number_format($row['monto'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['observaciones']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>

</html>

<style>
    :root {
        --primary-color: #d48b02;
        --primary-hover: rgb(210, 219, 86);
        --success-color: #28a745;
        --success-hover: #218838;
        --background-color: #f2f2f2;
        --card-bg: #ffffff;
        --text-color: ;
        --border-color: #ddd;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --border-radius: 8px;
        --transition: all 0.3s ease;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background-color: var(--background-color);
        color: var(--text-color);
        line-height: 1.6;
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
    }

    /* Navigation Buttons */
    .nav-buttons {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 80px;
    }

    .nav-buttons button {
        padding: 10px 22px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .nav-buttons button:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Reports Section */
    .reporte {
        display: none;
        background: var(--card-bg);
        padding: 5px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        transition: var(--transition);
        max-height: 390px; /* Altura máxima deseada */
        overflow-y: auto;  /* Scroll vertical cuando sea necesario */
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .reporte.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    /* Tables */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border-bottom: 1px solid var(--border-color);
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: var(--primary-color);
        color: black;
        position: sticky;
        top: 0;
        font-weight: 600;
    }

    tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    /* Special Values */
    .valor {
        font-size: 1.25rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    /* Forms */
    form {
        background: var(--card-bg);
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        margin: auto;
        margin-top: 24px;
        margin-bottom: 24px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
        width: 50%;
        justify-content: center;
        /* Alineación horizontal */
        align-items: center;
        /* Alineación vertical (opcional) */
        gap: 10px;

    }

    input[type="date"] {
        padding: 5px;
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
        font-family: inherit;
        transition: var(--transition);
    }

    input[type="date"]:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    input[type="submit"] {
        background-color: var(--success-color);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        padding: 8px 10px;
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
    }

    input[type="submit"]:hover {
        background-color: var(--success-hover);
        transform: translateY(-1px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        body {
            padding: 15px;
        }

        .nav-buttons {
            gap: 8px;
        }

        .nav-buttons button {
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        form {
            flex-direction: column;
            align-items: stretch;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>