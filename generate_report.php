<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=dashboard_db', 'root', '');
$user_id = $_SESSION['user_id'];

$widgets = $pdo->prepare("SELECT * FROM dashboard_widgets WHERE user_id = :user_id ORDER BY position");
$widgets->execute(['user_id' => $user_id]);
$widgets = $widgets->fetchAll();

// Generate report (e.g., PDF or Excel)
require 'vendor/autoload.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$html = '<h1>Dashboard Report</h1>';
foreach ($widgets as $widget) {
    $html .= "<h2>" . htmlspecialchars($widget['widget_type']) . "</h2>";
    $html .= "<div>" . htmlspecialchars($widget['widget_data']) . "</div>";
}

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("dashboard_report.pdf");
?>
