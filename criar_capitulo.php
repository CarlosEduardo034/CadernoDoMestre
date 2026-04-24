<?php
session_start();
include("Config/Config.php");

if (!isset($_SESSION['id'])) {
    echo "nao_autorizado";
    exit;
}

$nome = $_POST['nome'] ?? null;
$descricao = $_POST['descricao'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$nome) {
    echo "nome_obrigatorio";
    exit;
}

$sql = "INSERT INTO capitulos (usuario_id, nome, descricao) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $usuario_id, $nome, $descricao);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "erro";
}