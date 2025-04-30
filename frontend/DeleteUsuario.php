<?php
include 'database1.php'; // Conexión a la base de datos

if (isset($_POST['id'])) {
    $codProveedor = $_POST['id'];

    // Eliminar de la base de datos
    $queryDelete = "DELETE FROM usuarios WHERE id = ?";
    $stmtDelete = $conexion->prepare($queryDelete);
    $stmtDelete->bind_param("i", $codProveedor);
    $stmtDelete->execute();

    // Mensaje de éxito
    if ($stmtDelete->affected_rows > 0) {
        $mensaje = "Usuario Eliminado.";
    } else {
        $mensaje = "";
    }
    $stmtDelete->close();
}

$query = "SELECT * FROM usuarios";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
   
</head>
<body>
    <h1>Usuarios</h1>

    <!-- Mostrar mensaje de éxito o error -->
    <?php if (isset($mensaje)): ?>
        <p class="mensaje"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <!-- Tabla de proveedores -->
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Usuario</th>
                <th>Clave</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['usuario']) ?></td>
                <td class="oculto"><?= str_repeat('*', strlen($row['clave'])) ?></td>
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                        <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este Usuario?');">Eliminar</button>
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