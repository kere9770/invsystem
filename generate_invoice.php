<?php
// Include the TCPDF library
require_once('tcpdf.php');

// Get form data
$invoiceDate = $_POST['invoiceDate'];
$customerName = $_POST['customerName'];
$studentNames = $_POST['studentNames'];
$subjects = $_POST['subjects'];
$dates = $_POST['dates']; // Added date field
$startTimes = $_POST['startTimes'];
$endTimes = $_POST['endTimes'];
$hours = $_POST['hours'];
$priceRates = $_POST['priceRates'];
$amounts = $_POST['amounts'];
$totalAmount = $_POST['totalAmount'];

// Create a new TCPDF object
$pdf = new TCPDF();

// Set custom margins to remove grid lines at the top
$pdf->SetMargins(10, 10, 10); // Left, Top, Right margins
$pdf->SetHeaderMargin(0);     // No header margin
$pdf->SetFooterMargin(10);    // Footer margin
$pdf->SetAutoPageBreak(TRUE, 10); // Auto page break with bottom margin

$pdf->AddPage(); // Add a page to the PDF document

// Add logo image (path, position, size)
$logo = 'logo.jpg'; // Specify the path to your logo file

// Get the width of the page to calculate the position for the top-right corner
$pageWidth = $pdf->getPageWidth();

// Set the desired logo size (2cm height, 4cm width)
$logoWidth = 60; // 4cm = 40mm
$logoHeight = 30; // 2cm = 20mm

// Calculate the position to place the logo at the top-right corner
$rightX = $pageWidth - $logoWidth - 5; // Adjust padding from the right edge
$topY = 2; // 5mm padding from the top edge

// Position the logo at the top-right corner
$pdf->Image($logo, $rightX, $topY, $logoWidth, $logoHeight);

// Set the font for the document
$pdf->SetFont('helvetica', '', 12);

// Add title and customer information
$html = "
<h1>Invoice</h1>
<p><strong>Invoice Date:</strong> $invoiceDate</p>
<p><strong>Customer Name:</strong> $customerName</p>
<table style=\"border: 1px solid black; border-collapse: collapse; width: 100%;\" cellpadding=\"5\">
    <thead>
        <tr style=\"border: 1px solid black;\">
            <th style=\"border: 1px solid black;\"><strong>Student</strong></th>
            <th style=\"border: 1px solid black;\"><strong>Subject</strong></th>
            <th style=\"border: 1px solid black;\"><strong>Date</strong></th>
            <th style=\"border: 1px solid black;\"><strong>Start Time</strong></th>
            <th style=\"border: 1px solid black;\"><strong>End Time</strong></th>
            <th style=\"border: 1px solid black;\"><strong>Total Hours</strong></th>
            <th style=\"border: 1px solid black;\"><strong>Price Rate</strong></th>
            <th style=\"border: 1px solid black;\"><strong>Amount</strong></th>
        </tr>
    </thead>
    <tbody>
";

foreach ($studentNames as $key => $studentName) {
    $subject = $subjects[$key];
    $date = $dates[$key]; // Get the date for this row
    $startTime = $startTimes[$key];
    $endTime = $endTimes[$key];
    $hour = $hours[$key];
    $priceRate = number_format($priceRates[$key], 2, '.', ''); // Format price rate
    $amount = number_format($amounts[$key], 2, '.', '');       // Format amount

    // Add a row to the table for each student's data
    $html .= "
    <tr style=\"border: 1px solid black;\">
        <td style=\"border: 1px solid black;\">{$studentName}</td>
        <td style=\"border: 1px solid black;\">{$subject}</td>
        <td style=\"border: 1px solid black;\">{$date}</td>
        <td style=\"border: 1px solid black;\">{$startTime}</td>
        <td style=\"border: 1px solid black;\">{$endTime}</td>
        <td style=\"border: 1px solid black;\">{$hour}</td>
        <td style=\"border: 1px solid black;\">RM {$priceRate}</td>
        <td style=\"border: 1px solid black;\">RM {$amount}</td>
    </tr>
    ";
}

$html .= "
    </tbody>
</table>
<p><strong>Total Amount:</strong> RM $totalAmount</p>
";

// Write the HTML to the PDF document
$pdf->writeHTML($html);

// Add payment information at the bottom of the page
$pdf->Ln(10); // Add some space
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'PAYMENT INFORMATION', 0, 1, 'L'); // Title

$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(0, 10, "Bank No: 205120767646\nBank Name: AFFIN BANK\nRecipient: Nur Addina Sofia", 0, 'L', 0, 1, '', '', true);

// Output the PDF (this will download the PDF)
$pdf->Output('invoice.pdf', 'I'); // 'I' means inline (browser display), 'D' for download
