<?php
include 'database1.php'; // Conexión a la base de datos

// Si se ha enviado un id de proveedor, eliminarlo
if (isset($_POST['codProveedor'])) {
    $codProveedor = $_POST['codProveedor'];

    $nuevoProveedor = 10; // Cambia esto al id del proveedor con el que quieres asociar los productos

    // Actualizar los productos con el nuevo proveedor
    $queryUpdate = "UPDATE producto SET idProveedor = ? WHERE idProveedor = ?";
    $stmtUpdate = $conexion->prepare($queryUpdate);
    $stmtUpdate->bind_param("ii", $nuevoProveedor, $codProveedor);
    $stmtUpdate->execute();

    // Eliminar el proveedor de la base de datos
    $queryDelete = "DELETE FROM proveedor WHERE codProveedor = ?";
    $stmtDelete = $conexion->prepare($queryDelete);
    $stmtDelete->bind_param("i", $codProveedor);
    $stmtDelete->execute();

    // Mensaje de éxito
    if ($stmtDelete->affected_rows > 0) {
        $mensaje = "Proveedor eliminado correctamente.";
    } else {
        $mensaje = "";
    }

    $stmtUpdate->close();
    $stmtDelete->close();
}

// Consulta para obtener todos los proveedores
$query = "SELECT * FROM proveedor where codProveedor >=1";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Proveedor</title>
   
</head>
<body>
    <h1>Eliminar Proveedor</h1>

    <!-- Mostrar mensaje de éxito o error -->
    <?php if (isset($mensaje)): ?>
        <p class="mensaje"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <!-- Tabla de proveedores -->
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Contacto</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['codProveedor']) ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['direccion']) ?></td>
                <td><?= htmlspecialchars($row['contacto']) ?></td>
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="codProveedor" value="<?= htmlspecialchars($row['codProveedor']) ?>">
                        <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este proveedor?');">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
<style>
        body {
            align-items: center;
            text-align: center;
            font-family: Arial, sans-serif;
            padding: 20px;
            background-image: url('images/fondoUser.jpg'); 
            background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
            background-position: center;  /* Centra la imagen */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
        }

        h1 {
            text-align: center;
        }

        table {
            margin: 0 auto; /* Esto centra la tabla en el contenedor */
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            
        }
        /* Línea intercalada para filas pares */
        tr:nth-child(even) {
        background-color: #f2f2f2;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: silver;
        }

        .mensaje {
            color: green;
            text-align: center;
            font-weight: bold;
            border-radius: 10px;
        }

        button {
            padding: 6px 12px;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 10px;
        }

        button:hover {
            background-color: #d32f2f;
        }
    </style>