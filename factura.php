<?php
require('fpdf/fpdf.php');
include 'database1.php'; // Conexión a la base de datos

// Obtener el ID de la factura desde la URL
$facturaId = $_GET['facturaId'] ?? null;

if (!$facturaId) {
    die("Error: No se proporcionó un ID de factura.");
}

// Consulta para obtener la factura y el cliente
$queryFactura = "
    SELECT 
        factura.idFactura, 
        factura.fecha, 
        cliente.nombre AS clienteNombre, 
        cliente.idCliente AS clienteIdentidad
    FROM factura
    JOIN cliente ON factura.cliente = cliente.idCliente
    WHERE factura.idFactura = ?
";
$stmt = $conexion->prepare($queryFactura);
$stmt->bind_param("i", $facturaId);
$stmt->execute();
$resultFactura = $stmt->get_result();

if ($resultFactura->num_rows == 0) {
    die("Error: Factura no encontrada.");
}

$factura = $resultFactura->fetch_assoc();

// Consulta para obtener los productos de la factura
$queryProductos = "
    SELECT 
        producto.nombre AS productoNombre, 
        detalle_factura.cantidad, 
        producto.precio 
    FROM detalle_factura
    JOIN producto ON detalle_factura.idProducto = producto.idProducto
    WHERE detalle_factura.idFactura = ?
";
$stmt = $conexion->prepare($queryProductos);
$stmt->bind_param("i", $facturaId);
$stmt->execute();
$resultProductos = $stmt->get_result();

// Crear el PDF con tamaño de página personalizado
$pdf = new FPDF('P', 'mm', [58, 150]); // Tamaño de 58mm x 150mm para impresora térmica
$pdf->AddPage();
$pdf->SetMargins(2, 5, 2); // Márgenes


// Agregar datos del almacén
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(0, 5, '  Sobre Ruedas Motos', 0, 1);  // Nombre del almacén
$pdf->SetFont('Courier', '', 6);
$pdf->Cell(0, 3, 'Carrera 2, Colomboy', 0, 1, 'C');  // Dirección
$pdf->Cell(0, 3, '(123) 456-7890', 0, 1, 'C');
$pdf->Cell(0, 3, 'sobreruedasmotos@gmail.com', 0, 1, 'C');

// Línea separadora
$pdf->Ln(1);
$pdf->SetDrawColor(200, 200, 200);  // Establece un color gris claro (usando RGB)
$pdf->SetLineWidth(0.1);  // Reduce el grosor de la línea
$pdf->Line(5, $pdf->GetY(), 53, $pdf->GetY());
$pdf->Ln(1);
 

// Cambia el parámetro de alineación a 'L' para alinearlo a la izquierda
$pdf->SetFont('Courier', 'B', 6);  // Establece la fuente
$pdf->Cell(0, 5, 'Factura Electronica No: ' . $factura['idFactura'], 0, 1, 'C');

$pdf->SetFont('Courier', '', 7);
$pdf->Cell(0, 5, 'Fecha: ' . $factura['fecha'], 0, 1);

// Datos del cliente
$pdf->SetFont('Courier', '', 7);
$pdf->Cell(0, 4, 'Cliente: ' . $factura['clienteNombre'], 0, 1);
$pdf->Cell(0, 4, 'Identidad: ' . $factura['clienteIdentidad'], 0, 1);
$pdf->Ln(3);

$pdf->SetLineWidth(0.05);  // Grosor de línea de 0.1 mm
$pdf->SetDrawColor(200, 200, 200);  // Color gris claro

$pdf->SetFont('Courier', 'B', 6);
$widths = [25, 6, 10, 13]; // Anchos de las columnas
$headers = ['Producto', 'Qty', 'Precio', 'Subtotal'];

// Calcular el ancho total de la tabla
$totalWidth = array_sum($widths);

// Centrar la tabla en el medio de la página
$startX = (58 - $totalWidth) / 2;  // 58mm es el ancho de la página en mm

// Posicionar el cursor en X para empezar a imprimir la tabla centrada
$pdf->SetX($startX);

// Dibujar los encabezados
foreach ($headers as $key => $header) {
    $pdf->Cell($widths[$key], 5, $header, 1, 0, 'C');
}
$pdf->Ln();

// Dibujar los productos (tabla de contenido)
$pdf->SetFont('Courier', '', 5);
$total = 0;
while ($producto = $resultProductos->fetch_assoc()) {
    $subtotal = $producto['cantidad'] * $producto['precio'];

    // Establecer la posición X antes de imprimir las celdas
    $pdf->SetX($startX);

    // Dibujar las celdas de la fila del producto
    $pdf->Cell($widths[0], 5, $producto['productoNombre'], 1);
    $pdf->Cell($widths[1], 5, $producto['cantidad'], 1, 0, 'C');
    $pdf->Cell($widths[2], 5, '$' . number_format($producto['precio'], 0), 1, 0, 'R');
    $pdf->Cell($widths[3], 5, '$' . number_format($subtotal, 0), 1, 0, 'R');
    $pdf->Ln();

    $total += $subtotal;
}

// Total
$pdf->SetFont('Courier', 'B', 5);
$pdf->SetX($startX); // Centrar la fila del total
$pdf->Cell(array_sum($widths) - $widths[3], 5, 'Total:', 1, 0, 'R');
$pdf->Cell($widths[3], 5, '$' . number_format($total, 0), 1, 0, 'R');

// Agregar observación
$pdf->Ln(7);  // Salto de línea

// Línea separadora
$pdf->Ln(1);
$pdf->SetDrawColor(200, 200, 200);  // Establece un color gris claro (usando RGB)
$pdf->SetLineWidth(0.1);  // Reduce el grosor de la línea
$pdf->Line(2, $pdf->GetY(), 55, $pdf->GetY());
$pdf->Ln(2);

$pdf->SetFont('Courier', 'I', 5);  // Fuente Italica, tamaño 7
$pdf->Cell(0, 2, utf8_decode('Obs: PARA ALGUN CAMBIO DE MERCANCIA DEBE MOSTRAR'), 0, 1, 'L');
$pdf->Cell(0, 2, utf8_decode('LA FACTURA NO MAYOR A UNA SEMANA.'), 0, 1, 'L');

// Línea separadora
$pdf->Ln(2);
$pdf->SetDrawColor(200, 200, 200);  // Establece un color gris claro (usando RGB)
$pdf->SetLineWidth(0.1);  // Reduce el grosor de la línea
$pdf->Line(2, $pdf->GetY(), 55, $pdf->GetY());
$pdf->Ln(1);

// Mostrar el PDF
$pdf->Output("Factura_$facturaId.pdf", 'I');

// Cerrar la conexión
$conexion->close();
?>
