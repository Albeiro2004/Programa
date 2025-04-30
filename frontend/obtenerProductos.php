<?php
header('Content-Type: application/json');
$conexion = new mysqli("localhost:3307", "root", "", "almacen");

if ($conexion->connect_error) {
  die(json_encode(["error" => "conexión fallida: ".$conexion->connect_error]));
}

$sql = "SELECT 
    producto.nombre AS Nombre,
    producto.idProducto AS idProducto,
    producto.precio AS Precio,
    proveedor.nombre AS Proveedor,
    producto.marca AS Marca,
    inventario.cantidadDisponible AS Disponible
FROM 
    producto
JOIN 
    inventario ON producto.idProducto = inventario.idProducto
JOIN 
    proveedor ON producto.idProveedor = proveedor.codProveedor

ORDER BY 
    producto.idProducto ASC;";
$resultado = $conexion->query($sql);

if(!$resultado) {
    die(json_encode(["error" => "Error en la consulta: " .$conexion->error]));
}

$productos = [];
if($resultado->num_rows>0){
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}

echo json_encode($productos);
$conexion->close();
?>
