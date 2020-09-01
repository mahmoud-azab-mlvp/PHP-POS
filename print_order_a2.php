<?php
// call the FPDF library

require('fpdf/fpdf.php');
include_once("includes/connectDb.php");
$id = $_GET["id"];
$query =$pdo->prepare("SELECT * FROM invoices WHERE id = $id");
$query->execute();
$row = $query->fetch(PDO::FETCH_OBJ);
// A4 width : 219 mm
// default margin: 10mm each side
//writeable horizontal: 219-(10*2) = 199mm

//create pdf object 
$pdf = new FPDF ('P', 'mm', array(80,200));

//String orientation (P Or L ) - portrait or landscape
//String unit (pt, mm, and in ) - measure unit
// Mixed format (A3, A4, A5, Letter nad Lagel) - Format or pages
//Add new Page
$pdf -> addPage();
//$pdf->SetFillColor(123, 255, 234);
$pdf->SetFont('Arial', '', 16);
$pdf->Cell(60, 5, 'GeneralSoft', 0, 1, 'C', '', 'http://www.google.com');

$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(60, 5, 'Invoice', 0, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, 'Address: Progress wat , NewYork  - USA', 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 5, 'Document Number:  ' . $row->documentNumber, 0, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, 'Phone Number:  ' . $row->phone , 0, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, 'Date:  ' . $row->date, 0, 1, 'C', );

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, 'E-mail Address: mahmoud@gmail.com', 0, 1, 'C', '', 'http://www.google.com');
$pdf->Cell(60, 5, 'E-mail Address: generalsoft.com@gmail.com', 0, 1, 'C', '', 'http://www.google.com');

$pdf->Line(10, 55, 70, 55);
$pdf->Line(10, 55, 70, 55);
$pdf->Ln(10);
$pdf->setFont('Arial', 'BI', 12);
$pdf->Cell(20, 10, 'Bill to:', 0, 0, '');
$pdf->setFont('Courier', 'BI', 12);
$pdf->Cell(20, 10, 'Fazain', 0, 1);

$pdf->Cell(50, 5, '', 0, 1);

$pdf->setFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 208, 208);
$pdf->Cell(25, 8, 'Product', 1, 0, 'C', true);
$pdf->Cell(10, 8, 'Qty', 1, 0, 'C', true);
$pdf->Cell(10, 8, 'Price', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Total', 1, 1, 'C', true);

$invoiceId = $_GET["id"];
$queryDetails =$pdo->prepare("SELECT * FROM invoice_details WHERE $invoiceId = $invoiceId");
$queryDetails->execute();
$rowDetails = $queryDetails->fetch(PDO::FETCH_OBJ);

$pdf->setFont('Arial', 'B', 12);
while ($rowDetails = $queryDetails->fetch(PDO::FETCH_OBJ)) {
$pdf->Cell(25, 5, $rowDetails->name, 1, 0, 'L');
$pdf->Cell(10, 5, $rowDetails->qty, 1, 0, 'C');
$pdf->Cell(10, 5, $rowDetails->price, 1, 0, 'C');
$pdf->Cell(20, 5, $rowDetails->total, 1, 1, 'C');
}

$pdf->setX(7);
$pdf->setFont('courier', 'B', 8);
$pdf->Cell(20, 5, '', 0, 0, 'L');
$pdf->Cell(25, 5, 'SubTotal:'  , 1, 0, 'C', true);
$pdf->Cell(20, 5, $row->subTotal, 1, 1, 'C');
$pdf->Cell(35, 8, 'Diccount:'  , 1, 0, 'C', true);
$pdf->Cell(30, 8, $row->discount, 1, 1, 'C');
$pdf->Cell(35, 8, 'Tax:', 1, 0, 'C', true);
$pdf->Cell(30, 8, $row->tax, 1, 1, 'C');
$pdf->Cell(35, 8, 'NetTotal:', 1, 0, 'C', true);
$pdf->Cell(30, 8, $row->subTotal, 1, 1, 'C');
$pdf->Cell(35, 8, 'Paid:', 1, 0, 'C', true);
$pdf->Cell(30, 8, $row->paid , 1, 1, 'C');
$pdf->Cell(35, 8, 'Due:'  , 1, 0, 'C', true);
$pdf->Cell(30, 8, $row->due, 1, 1, 'C');
$pdf->Cell(35, 8, 'Payment Method:' , 1, 0, 'C', true);
$pdf->Cell(30, 8,  $row->subTotal, 1, 1, 'C');

$pdf->Cell(20, 5, '', 0, 1, '');
$pdf->setX(7);
$pdf->setFont('Arial', 'B', 8);
$pdf->Cell(25, 5, 'Important  Inovice:', 0, 1, '', true);

$pdf->setX(7);
$pdf->setFont('Arial', '', 5);
$pdf->Cell(75, 5, 'No item will be replaced or refunded if you dont have the invoice with you', 0, 2, '');

$pdf->setX(7);
$pdf->setFont('Arial', '', 5);
$pdf->Cell(75, 5, 'You Can refund with in 2 days of purchase', 0, 1, '');

//output the result

$pdf-> outPut();
?>