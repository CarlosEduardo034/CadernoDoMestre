<?php
require_once("../../config/database.php");
require_once("../../middlewares/auth.php");

$id = $_POST['id'] ?? null;
$titulo = $_POST['titulo'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$id || !$titulo) {
    echo "dados_invalidos";
    exit;
}

$sql = "SELECT id FROM paginas WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();

if ($stmt->get_result()->num_rows == 0) {
    echo "nao_permitido";
    exit;
}

$sql = "UPDATE paginas SET titulo = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $titulo, $id);

echo $stmt->execute() ? "ok" : "erro";