<?php
session_start();
include("Config/Config.php");

if (!isset($_SESSION['id'])) {
    echo "nao_autorizado";
    exit;
}

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? null;
$descricao = $_POST['descricao'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$id || !$nome) {
    echo "dados_invalidos";
    exit;
}

// garante que o capítulo pertence ao usuário
$sql = "SELECT id FROM capitulos WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "nao_permitido";
    exit;
}

// atualiza
$sql = "UPDATE capitulos SET nome = ?, descricao = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $nome, $descricao, $id);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "erro";
}