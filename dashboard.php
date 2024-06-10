<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=dashboard_db', 'root', '');
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $widget_type = $_POST['widget_type'];
    $widget_data = $_POST['widget_data'];
    $position = $_POST['position'];

    $stmt = $pdo->prepare("INSERT INTO dashboard_widgets (user_id, widget_type, widget_data, position) VALUES (:user_id, :widget_type, :widget_data, :position)");
    $stmt->execute(['user_id' => $user_id, 'widget_type' => $widget_type, 'widget_data' => $widget_data, 'position' => $position]);
}

$widgets = $pdo->prepare("SELECT * FROM dashboard_widgets WHERE user_id = :user_id ORDER BY position");
$widgets->execute(['user_id' => $user_id]);
$widgets = $widgets->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customizable Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Dashboard</h1>
    <form method="post">
        Widget Type:
        <select name="widget_type">
            <option value="chart">Chart</option>
            <option value="table">Table</option>
            <option value="text">Text</option>
        </select><br>
        Widget Data: <textarea name="widget_data" required></textarea><br>
        Position: <input type="number" name="position" required><br>
        <input type="submit" value="Add Widget">
    </form>

    <div id="dashboard">
        <?php foreach ($widgets as $widget): ?>
            <div class="widget">
                <h2><?php echo htmlspecialchars($widget['widget_type']); ?></h2>
                <div><?php echo htmlspecialchars($widget['widget_data']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
