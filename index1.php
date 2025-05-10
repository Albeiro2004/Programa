<?php
include 'database1.php'; // Conexión a la base de datos

// Obtener el valor del formulario de búsqueda
$buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';

// Consulta SQL que filtra los productos por idProducto, nombre, marca o proveedor
$query = "SELECT producto.idProducto, producto.nombre, producto.precio, producto.marca, proveedor.nombre AS proveedor,
          inventario.cantidadDisponible AS Disponible, inventario.ubicacion AS Ubicacion
          FROM producto
          JOIN inventario ON producto.idProducto = inventario.idProducto
          JOIN proveedor ON producto.idProveedor = proveedor.codProveedor
          WHERE producto.idProducto LIKE '%$buscar%' OR producto.nombre LIKE '%$buscar%' OR producto.marca LIKE '%$buscar%' OR proveedor.nombre LIKE '%$buscar%'
          order by producto.idProducto asc";

$result = $conexion->query($query);
?>
<script>
    (function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1); // Evita volver atrás
        };
    })();
</script>



<!DOCTYPE html>
<html lang="es">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taller - Almacen</title>
    <link rel="icon" type="image/png" href="images/cerrarSesion.png">
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="todo">

<header class="header"> 
        <div class="menu container">
        <a href="#" class="logo" onclick="window.location.href='index1.php';">Sobre Ruedas</a>

        <nav class="navbar">
            <ul>
                        
               <li><button class="menu-btn" onclick="toggleMenu(event, 'menu1')"
               ><img src="images/registro.png">Registrar</button>
               <div class="menu1" id="menu1">
               <a href="registrarProducto.php" target="_blank">Registrar Producto</a>
               <a href="registrarProveedor.php" target="_blank">Registrar Proveedor</a>
               <a href="registrarTrabajador.php" target="_blank">Registrar Trabajador</a>
               <a href="#" onclick="abrirEnOtraPestana('Egresos/egreso.php')">Reg. Egreso</a>
               <a href="registrarUsuario.php" target="_blank">Registrar Usuario</a>
               </div></li>
               
               <li><button class="menu-btn" onclick="toggleMenu(event, 'menu2')">
               <img src="images/actualizar.png">Actualizar</button>
               <div class="menu2" id="menu2">
               <a href="UpdateInventario.php" target="_blank">Inventario</a>
               <a href="actualizarProducto.php" target="_blank">Actualizar Producto</a>
               <a href="Proveedores.php" target="_blank">Actualizar Proveedor</a>
               <a href="Trabajadores.php" target="_blank">Actualizar Trabajador</a>
               <a href="Acceso.php" target="_blank">Actualizar Usuario</a>
               </div></li> 
               
               <li><button class="menu-btn" onclick="toggleMenu(event, 'menu3')">
               <img src="images/eliminar.png">Eliminar</button>
               <div class="menu3" id="menu3">
               <a href="DeleteProducto.php" target="_blank">Eliminar Producto</a>
               <a href="DeleteProveedor.php" target="_blank">Eliminar Proveedor</a>
               <a href="DeleteTrabajador.php" target="_blank">Eliminar Trabajador</a>
               <a href="Acceso1.php" target="_blank">Eliminar Usuario</a>
               </div></li> 

               <li><button class="menu-btn" onclick="toggleMenu(event, 'menu4')">
               <img src="images/consultar.png">Consultas</button>
               <div class="menu4" id="menu4">
               <a href="#" onclick="abrirEnOtraPestana('Ingresos/ingresos.php')">Información de Ventas</a>
               <a href="topClientes.php" target="_blank">Mejores Clientes</a>
               <a href="masVendido.php" target="_blank">Más Vendido</a>
               <a href="facturasFecha.php" target="_blank">Facturas</a>
               <a href="deficientes.php" target="_blank">Productos Disponibles</a>
               <a href="ingresoProducto.php" target="_blank">Ingreso por Producto</a>
               <a href="#" onclick="abrirEnOtraPestana('infoFacturas.php')">Info Facturas</a>
               <a href="#" onclick="abrirEnOtraPestana('Egresos/reportesEgresos.php')">Reporte Egresos</a>
               </div></li> 
               
               <li ><button class="menu-btn" onclick="toggleMenu(event, 'menu6')">
                <img src="images/servicio.png">Servicios</button>
                <div class="menu5" id="menu6">
                <a href="servicio.php" target="_blank">Servicio del día</a>
                <a href="servicioConsulta.php" target="_blank">Datos de Servicios</a>
                </div></li>
                
                <script>
                function abrirEnOtraPestana(url) {
                        window.open(url, '_blank'); // Abre en una nueva pestaña
                }
                </script>

               <li ><button class="menu-btn" onclick="toggleMenu(event, 'menu5')">
                <img src="images/facturar.png">Vender</button>
                <div class="menu5" id="menu5">
                <a href="#" onclick="abrirEnOtraPestana('Facturar1.php')">Factura Rápida</a>    
                <a href="#" onclick="abrirOPriorizarPagina('guardarCliente.php')">Factura - Clientes</a>
                </div></li>

                <li>
                <button class="logout-button" onclick="ventanaSesion()"><i class="fas fa-sign-out-alt"></i>
                Cerrar</button>
                </li>
        
            </ul> 
        </nav>
           
           <!-- abrir sesión -->
           <script>
        function ventanaSesion() {
        // Redirigir a la nueva URL
        window.location.href = 'sesion.php';

        // Reemplazar la entrada actual en el historial para evitar volver atrás
        window.history.replaceState(null, '', 'sesion.php');
        }
        </script>
        

        </div>
    </header>
    
        <h1>Lista de Productos</h1>

    <main>
    
    <!-- Formulario de búsqueda -->
    <div class="search-container">
    <input 
        type="text" 
        id="searchInput" 
        placeholder="Buscar por ID, Nombre, Marca o Proveedor" 
        oninput="filterTable()">
    </div>
    
    <div class="custom-scroll" style="height: auto;">

    <div class="tabla">
        <table id="tabla-productos">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Marca</th>
                <th>Proveedor</th>
                <th>Disponible</th>
                <th>Ubicación</th>

            </tr>
        </thead>
        
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= str_pad(htmlspecialchars($row['idProducto']), 4, "0", STR_PAD_LEFT) ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td>$ <?= number_format(htmlspecialchars($row['precio']), 0, '.', '.') ?></td>
            <td><?= htmlspecialchars($row['marca']) ?></td>
            <td><?= htmlspecialchars($row['proveedor']) ?></td>
            <td><?= htmlspecialchars($row['Disponible']) ?></td>
            <td><?= htmlspecialchars($row['Ubicacion']) ?></td>
        </tr>
        <?php endwhile; ?>
    

    </table>
    </div>
    </div>

        <br>
    </main>

    <h3>Almacen Sobre Ruedas</h3>

    </div>

        <script>
    const LOCK_KEY = "page_opened1";
    const INSTANCE_ID = Date.now().toString(); // Identifica de manera única esta pestaña

    const setLock = () => localStorage.setItem(LOCK_KEY, INSTANCE_ID);
    const clearLock = () => {
        if (localStorage.getItem(LOCK_KEY) === INSTANCE_ID) {
            localStorage.removeItem(LOCK_KEY);
        }
    };

    window.onload = () => {
        // Escucha cambios en el LOCK_KEY
        window.addEventListener("storage", (event) => {
            if (event.key === LOCK_KEY) {
                if (event.newValue !== INSTANCE_ID) {
                    // Si el valor del LOCK_KEY cambia y no coincide con esta pestaña, cierra esta
                    alert("Cerrando esta pestaña porque otra la reemplazó.");
                    clearLock();
                    window.location.href = "about:blank";
                    window.close();  
                } 
            }
        });

        // Verifica si otra pestaña está activa
        const currentLock = localStorage.getItem(LOCK_KEY);
        if (currentLock && currentLock !== INSTANCE_ID) {
            // Notifica a la pestaña anterior para que se cierre
            localStorage.setItem(LOCK_KEY, INSTANCE_ID);
            setTimeout(() => {
                alert("Tenías una página de ésta abierta, por seguridad cerramos la anterior y mantenemos la actual.");
            }, 500);
        } else {
            // Si no hay bloqueo, establece uno nuevo
            setLock();
        }

        // Libera el bloqueo cuando se cierra la pestaña
        window.addEventListener("beforeunload", clearLock);
    };

    let ventanaAbierta = null;

        function abrirOPriorizarPagina(url) {
            if (ventanaAbierta && !ventanaAbierta.closed) {
                ventanaAbierta.focus(); // Enfoca la ventana si ya está abierta
            } else {
                ventanaAbierta = window.open(url, '_blank'); // Abre una nueva ventana si no está abierta
            }
        }

</script>
<script>
    (function () {
    let timeoutId;
    const logoutDelay = 8 * 60 * 60 * 1000; // 8 horas

    // Función para redirigir al cerrar sesión
    function logout() {
        window.location.href = "sesion.php";
    }

    // Función para reiniciar el temporizador
    function resetTimer() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(logout, logoutDelay);
    }

    // Eventos que se consideran como actividad del usuario
    const activityEvents = ["mousemove", "keydown", "scroll", "click", "touchstart"];

    // Agregar listeners para cada evento
    activityEvents.forEach(event => {
        window.addEventListener(event, resetTimer, true);
    });

    // Iniciar el temporizador al cargar la página
    resetTimer();
})();

</script>

        <script src="script1.js"></script>
        
        <script>
        function verificarActualizacion() {
        // Revisar si ha cambiado la clave 'inventarioActualizado'
        window.addEventListener('storage', function(event) {
            if (event.key === 'clave') {
                location.reload(); // Recargar la página automáticamente
            }
        });
    }

    // Llamar a la función al cargar la página
    verificarActualizacion();
    </script>


</body>
</html>
