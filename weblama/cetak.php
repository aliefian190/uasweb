<?php
session_start();
require_once 'vendor/autoload.php'; // Ensure this path is correct

use Dompdf\Dompdf;

if (!isset($_SESSION["akun-admin"])) {
    if (isset($_SESSION["akun-user"])) {
        echo "<script>
            alert('Cetak data hanya berlaku untuk admin!');
            location.href = '../index.php';
        </script>";
        exit;
    } else {
        header("Location: ../login.php");
        exit;
    }
}

ob_start();
include "page.php";
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set paper size and orientation if needed
$dompdf->setPaper('A4', 'portrait');

$dompdf->render();
$dompdf->stream('pesan.pdf', array('Attachment' => 0)); // Display PDF in browser
