<?php
require_once("../../config/database.php");
require_once("../../middlewares/auth.php");

header("Content-Type: application/json");

$capitulo_id = $_GET['capitulo_id'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$capitulo_id) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT * FROM paginas 
        WHERE capitulo_id = ? 
        AND usuario_id = ? 
        AND is_lixeira = 0";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $capitulo_id, $usuario_id);
$stmt->execute();

$result = $stmt->get_result();

$paginas = [];

while ($row = $result->fetch_assoc()) {
    $paginas[] = $row;
}

echo json_encode($paginas);