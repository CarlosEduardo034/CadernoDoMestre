<?php
require_once("../../config/database.php");
require_once("../../middlewares/auth.php");

header("Content-Type: application/json");

$usuario_id = $_SESSION['id'];

$sql = "SELECT * FROM capitulos 
        WHERE usuario_id = ? 
        AND is_lixeira = 0";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$result = $stmt->get_result();

$dados = [];

while ($row = $result->fetch_assoc()) {
    $dados[] = $row;
}

echo json_encode($dados);