function toggleMenu(event, menuId) {
    // Obtener el menú dinámicamente
    var menu = document.getElementById(menuId);
    if (!menu) return; // Salir si el menú no existe

    // Alternar la visibilidad del menú
    var isMenuOpen = menu.style.display === "block";
    menu.style.display = isMenuOpen ? "none" : "block";

    // Evitar que el clic en el botón cierre el menú inmediatamente
    event.stopPropagation();

    // Cerrar el menú cuando el cursor salga del menú
    menu.addEventListener("mouseleave", function () {
        menu.style.display = "none";
    });
}
// Cerrar todos los menús cuando se haga clic fuera de ellos
document.addEventListener("click", function () {
    var menus = document.querySelectorAll(".menu1");
    menus.forEach(function (menu) {
        menu.style.display = "none";
    });
});
document.addEventListener("click", function () {
    var menus = document.querySelectorAll(".menu2");
    menus.forEach(function (menu) {
        menu.style.display = "none";
    });
});
document.addEventListener("click", function () {
    var menus = document.querySelectorAll(".menu3");
    menus.forEach(function (menu) {
        menu.style.display = "none";
    });
});
document.addEventListener("click", function () {
    var menus = document.querySelectorAll(".menu4");
    menus.forEach(function (menu) {
        menu.style.display = "none";
    });
});
document.addEventListener("click", function () {
    var menus = document.querySelectorAll(".menu5");
    menus.forEach(function (menu) {
        menu.style.display = "none";
    });
});
document.addEventListener("click", function () {
    var menus = document.querySelectorAll(".menu6");
    menus.forEach(function (menu) {
        menu.style.display = "none";
    });
});
document.addEventListener('DOMContentLoaded', function() {
    cargarProductos(); // Cargar los productos al inicio
});

function cargarProductos() {
    fetch('obtenerProductos.php') // Solicitar los productos al backend
        .then(response => response.json()) // Procesar la respuesta como JSON
        .then(data => {
            const productosLista = document.getElementById('productosLista'); // Elemento donde se mostrarán los productos
            //productosLista.innerHTML = ''; // Limpiar la lista de productos (por si ya hay algo)

            // Recorrer los productos y agregarlos a la tabla
            data.forEach(producto => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                    
                    <td>${producto.Nombre}</td>
                    <td>${producto.idProducto}</td>
                    <td>${producto.Precio}</td>
                    <td>${producto.Proveedor}</td>
                    <td>${producto.Marca}</td>
                    <td>${producto.Disponible}</td>
                `;//<td><img src="${producto.imagen}" alt="Imagen del producto" width="100" height="100"></td>
                productosLista.appendChild(fila);
            });
        })
        .catch(error => console.error('Error al cargar los productos:', error)); // Manejo de errores
}

    function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const table = document.getElementById("tabla-productos");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Saltar el encabezado
        const cells = rows[i].getElementsByTagName("td");
        let match = false;

        for (let j = 0; j < cells.length; j++) {
            if (cells[j].textContent.toLowerCase().includes(input)) {
                match = true;
                break;
            }
        }

        rows[i].style.display = match ? "" : "none";
    }
    }

    function resetTable() {
    const input = document.getElementById("searchInput");
    const table = document.getElementById("tabla-productos");
    const rows = table.getElementsByTagName("tr");

    input.value = ""; // Limpiar el campo de búsqueda

    for (let i = 1; i < rows.length; i++) { // Mostrar todas las filas
        rows[i].style.display = "";
    }
    }
