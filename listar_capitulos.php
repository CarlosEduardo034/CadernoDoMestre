<?php
session_start();
include("Config/Config.php");

if (!isset($_SESSION['id'])) {
    echo json_encode([]);
    exit;
}

$usuario_id = $_SESSION['id'];

$sql = "SELECT * FROM capitulos WHERE usuario_id = ? AND is_lixeira = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$result = $stmt->get_result();

$capitulos = [];

while ($row = $result->fetch_assoc()) {
    $capitulos[] = $row;
}

echo json_encode($capitulos);