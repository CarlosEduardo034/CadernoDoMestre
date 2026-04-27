<?php
require_once("../../config/database.php");

$email = $_POST['email'] ?? null;
$senha = $_POST['senha'] ?? null;

if (!$email || !$senha) {
    echo "dados_invalidos";
    exit;
}

$sql = "SELECT id FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo "email_existente";
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (email, senha) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $senha_hash);

echo $stmt->execute() ? "ok" : "erro";