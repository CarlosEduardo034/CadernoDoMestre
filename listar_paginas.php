<?php
session_start();
include("Config/Config.php");

header("Content-Type: application/json");

if (!isset($_SESSION['id'])) {
    echo json_encode([]);
    exit;
}

$capitulo_id = $_GET['capitulo_id'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$capitulo_id || !is_numeric($capitulo_id)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT * FROM paginas WHERE capitulo_id = ? AND usuario_id = ? AND is_lixeira = 0";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["erro" => $conn->error]);
    exit;
}

$stmt->bind_param("ii", $capitulo_id, $usuario_id);
$stmt->execute();

$result = $stmt->get_result();

$paginas = [];

while ($row = $result->fetch_assoc()) {
    $paginas[] = $row;
}

echo json_encode($paginas);