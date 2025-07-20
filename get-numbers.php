<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['order_id'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

$order_id = $_POST['order_id'];

$database = new Database();
$conn = $database->getConnection();

$query = "SELECT number FROM raffle_numbers WHERE order_id = ? AND status = 'paid' ORDER BY number ASC";
$stmt = $conn->prepare($query);
$stmt->execute([$order_id]);
$numbers = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode([
    'status' => 'success',
    'numbers' => $numbers
]);
?>