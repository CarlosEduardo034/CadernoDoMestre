<?php
require_once("../../config/database.php");
require_once("../../middlewares/auth.php");

$nome = $_POST['nome'] ?? null;
$descricao = $_POST['descricao'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$nome) {
    echo "dados_invalidos";
    exit;
}

$sql = "INSERT INTO capitulos (nome, descricao, usuario_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $nome, $descricao, $usuario_id);

echo $stmt->execute() ? "ok" : "erro";