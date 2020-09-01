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
$pdf = new FPDF ('P', 'mm', 'A4');

//String orientation (P Or L ) - portrait or landscape
//String unit (pt, mm, and in ) - measure unit
// Mixed format (A3, A4, A5, Letter nad Lagel) - Format or pages
//Add new Page
$pdf -> addPage();
//$pdf->SetFillColor(123, 255, 234);
$pdf->SetFont('Arial', '', 16);
$pdf->Cell(80, 10, 'GeneralSoft', 0, 0, 'C', '', 'http://www.google.com');

$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(112, 10, 'Invoice', 0, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(80, 5, 'Address: Progress wat , NewYork  - USA', 0, 0, '');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(112, 5, 'Document Number:  ' . $row->documentNumber, 0, 1, 'C', );

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(80, 5, 'Phone Number:  ' . $row->phone , 0, 0, '', '', 'http://www.google.com');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(112, 5, 'Date:  ' . $row->date, 0, 1, 'C', );

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(80, 5, 'E-mail Address: mahmoud@gmail.com', 0, 1, '', '', 'http://www.google.com');
$pdf->Cell(80, 5, 'E-mail Address: generalsoft.com@gmail.com', 0, 1, '', '', 'http://www.google.com');

$pdf->Line(5, 45, 205, 45);
$pdf->Line(5, 46, 205, 46);
$pdf->Ln(10);
$pdf->setFont('Arial', 'BI', 12);
$pdf->Cell(20, 10, 'Bill to:', 0, 0, '');
$pdf->setFont('Courier', 'BI', 12);
$pdf->Cell(20, 10, 'Fazain', 0, 1);

$pdf->Cell(50, 5, '', 0, 1);

$pdf->setFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 208, 208);
$pdf->Cell(100, 8, 'Product', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Qty', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Price', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Total', 1, 1, 'C', true);

$invoiceId = $_GET["id"];
$queryDetails =$pdo->prepare("SELECT * FROM invoice_details WHERE $invoiceId = $invoiceId");
$queryDetails->execute();
$rowDetails = $queryDetails->fetch(PDO::FETCH_OBJ);

$pdf->setFont('Arial', 'B', 12);
while ($rowDetails = $queryDetails->fetch(PDO::FETCH_OBJ)) {
$pdf->Cell(100, 8, $rowDetails->name, 1, 0, 'L');
$pdf->Cell(20, 8, $rowDetails->qty, 1, 0, 'C');
$pdf->Cell(30, 8, $rowDetails->price, 1, 0, 'C');
$pdf->Cell(40, 8, $rowDetails->total, 1, 1, 'C');
}



$pdf->Cell(120, 8, 'SubTotal:'  , 1, 0, 'C', true);
$pdf->Cell(70, 8, $row->subTotal, 1, 1, 'C');
$pdf->Cell(120, 8, 'Diccount:'  , 1, 0, 'C', true);
$pdf->Cell(70, 8, $row->discount, 1, 1, 'C');
$pdf->Cell(120, 8, 'Tax:', 1, 0, 'C', true);
$pdf->Cell(70, 8, $row->tax, 1, 1, 'C');
$pdf->Cell(120, 8, 'NetTotal:', 1, 0, 'C', true);
$pdf->Cell(70, 8, $row->subTotal, 1, 1, 'C');
$pdf->Cell(120, 8, 'Paid:', 1, 0, 'C', true);
$pdf->Cell(70, 8, $row->paid , 1, 1, 'C');
$pdf->Cell(120, 8, 'Due:'  , 1, 0, 'C', true);
$pdf->Cell(70, 8, $row->due, 1, 1, 'C');
$pdf->Cell(120, 8, 'Payment Method:' , 1, 0, 'C', true);
$pdf->Cell(70, 8,  $row->subTotal, 1, 1, 'C');

$pdf->Cell(50, 5, '', 0, 1, '');

$pdf->setFont('Arial', 'B', 10);
$pdf->Cell(32, 10, 'Important  Inovice:', 0, 0, '', true);
$pdf->setFont('Arial', '', 8);
$pdf->Cell(148, 10, 'No item will be replaced or refunded if you dont have the invoice with you . You Can refund with in 2 days of purchase', 0, 0, '');
//output the result
$pdf-> outPut();
?>