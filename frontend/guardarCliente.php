<?php

include 'database1.php';

$idCliente = '';
$cliente = null;

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCliente'])) {
    $idCliente = $_POST['idCliente'];

    // Buscar cliente por idCliente
    $sql = "SELECT * FROM cliente WHERE idCliente = '$idCliente'";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        // Si el cliente existe
        $cliente = $result->fetch_assoc();
    } else {
        // Si no existe, registrar el cliente
        $nombre = $_POST['nombre'] ?? '';
        $contacto = $_POST['contacto'] ?? '';
        if ($nombre && $contacto) {
            $sql = "INSERT INTO cliente (idCliente, nombre, contacto) VALUES ('$idCliente', '$nombre', '$contacto')";
            if ($conexion->query($sql) === TRUE) {
                echo "Nuevo cliente registrado con éxito.";
                $cliente = ['idCliente' => $idCliente, 'nombre' => $nombre, 'contacto' => $contacto];
            } else {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Cliente</title>
    <link rel="icon" type="image/png" href="images/consultar.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<!-- Formulario de búsqueda -->
<form method="POST">
    <label for="idCliente">ID Cliente:</label>
    <input type="text" name="idCliente" id="idCliente" value="<?php echo isset($_POST['idCliente']) ? $_POST['idCliente'] : ''; ?>" required>
    <button type="submit">Buscar</button>
    <br>
    <button type="button" class="reset-btn" onclick="resetPage()">
        <i class="fas fa-sync-alt"></i></button>
</form>

<?php if ($cliente): ?>
    <!-- Mostrar los resultados -->
    <h3>Cliente Encontrado</h3>
    <p>ID Cliente: <input type="text" value="<?php echo $cliente['idCliente']; ?>" disabled></p>
    <p>Nombre: <input type="text" value="<?php echo $cliente['nombre']; ?>" disabled></p>
    <p>Contacto: <input type="text" value="<?php echo $cliente['contacto']; ?>" disabled></p>

    <!-- Botón para abrir Facturación -->
    <button id="facturarBtn">Ir a Facturación</button>

    <script>
        document.getElementById('facturarBtn').addEventListener('click', function() {
            // Borra el valor del campo idCliente
            document.getElementById('idCliente').value = '';

            // Abre la página de Facturación en una nueva pestaña
            window.open('Facturar.php?idCliente=<?php echo $cliente['idCliente']; ?>');
        });
    </script>
<?php else: ?>
    <!-- Mostrar formulario para registrar un nuevo cliente si no existe -->
    <h3>Cliente No Encontrado. Registrar Cliente:</h3>
    <form method="POST">
        <input type="hidden" name="idCliente" value="<?php echo $idCliente; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <label for="contacto">Contacto:</label>
        <input type="text" name="contacto" id="contacto" required>
        <button type="submit">Registrar Cliente</button>
    </form>
<?php endif; ?>

</body>
</html>

<?php
$conexion->close();
?>

    <script>
    function resetPage() {
        // Recargar la página para reiniciar todo
        window.location.href = window.location.href.split('?')[0];
    }
    history.pushState(null, "", location.href);
    window.onpopstate = function () {
        history.pushState(null, "", location.href);
    };


    </script>

<style>
    /* General */
body {
    font-family: 'Arial', sans-serif;
    background-color:rgb(214, 214, 214);
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    flex-direction: column;
    overflow: hidden;
    background-image: url('images/fondoProducto.jpg'); 
    background-size: cover;       /* Ajusta la imagen para cubrir toda la pantalla */
    background-position: center;  /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
}

h3 {
    font-size: 24px;
    color: #333;
    margin-bottom: 15px;
    text-align: center;
}

/* Formulario */
form {
    background-color: rgba(241, 241, 241, 0.62);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
    margin: 20px;
    transition: all 0.3s ease;
    opacity: 1;
}

form:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

/* Títulos */
h3 {
    font-size: 14px;
    color:rgba(2, 2, 2, 0.6);
}

/* Campos de entrada */
label {
    font-size: 16px;
    margin-bottom: 8px;
    display: block;
    color: #555;
    text-align: center;
}
input[type="text"] {
    width: 60%; /* Ancho del 80% respecto al contenedor */
    padding: 5px; /* Aumento el padding para hacerlo más cómodo */
    margin: 0 auto 20px auto; /* Centrado horizontal y espacio inferior */
    display: block; /* Asegura que el input sea un bloque y pueda centrarse */
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    text-align: center; /* Centrado del texto dentro del input */
}

input[type="text"]:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.4);
}

/* Botón */
button {
    display: block;
    margin: 0 auto; /* Esto lo centra horizontalmente */
    padding: 7px 15px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0056b3;
    transform: translateY(-3px);
}

button:active {
    background-color: #004085;
    transform: translateY(0);
}

/* Deshabilitar campos */
input[disabled] {
    background-color: #e9ecef;
    color: #6c757d;
    text-align: center;

}

/* Resultados de búsqueda */
p {
    font-size: 12px;
    color: #333;
    text-align: center;
}

input[disabled] {
    background-color: #f1f1f1;
    cursor: not-allowed;
}

/* Estilos de error */
h3.error {
    color: #dc3545;
}

h3.success {
    color: #28a745;
}

/* Responsive Design */
@media (max-width: 768px) {
    form {
        width: 90%;
        padding: 20px;
    }

    h3 {
        font-size: 20px;
    }

    input[type="text"], button {
        font-size: 14px;
        padding: 10px;
    }
}
.reset-btn {
        background-color:rgb(236, 153, 147); /* Rojo */
        color: white;
        border: none;
        border-radius: 5px;
        padding: 5px 5px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center; /* Centrar ícono y texto verticalmente */
        gap: 8px; /* Espacio entre ícono y texto */
        transition: background-color 0.3s;
    }

    /* Ícono del botón */
    .reset-btn i {
        font-size: 16px;
    }

    /* Hover del botón */
    .reset-btn:hover {
        background-color: #d32f2f; /* Rojo oscuro */
    }

    /* Activo */
    .reset-btn:active {
        background-color: #b71c1c; /* Rojo más oscuro */
    }

</style>