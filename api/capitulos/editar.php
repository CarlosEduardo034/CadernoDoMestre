<?php
require_once("../../config/database.php");
require_once("../../middlewares/auth.php");

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? null;
$descricao = $_POST['descricao'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$id || !$nome) {
    echo "dados_invalidos";
    exit;
}

$sql = "SELECT id FROM capitulos WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();

if ($stmt->get_result()->num_rows == 0) {
    echo "nao_permitido";
    exit;
}

$sql = "UPDATE capitulos SET nome = ?, descricao = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $nome, $descricao, $id);

echo $stmt->execute() ? "ok" : "erro";