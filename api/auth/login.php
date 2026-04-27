<?php
session_start();
require_once("../../config/database.php");

$email = $_POST['email'] ?? null;
$senha = $_POST['senha'] ?? null;

if (!$email || !$senha) {
    echo "dados_invalidos";
    exit;
}

$sql = "SELECT id, senha FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "usuario_nao_encontrado";
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($senha, $user['senha'])) {
    echo "senha_incorreta";
    exit;
}

$_SESSION['id'] = $user['id'];

echo "ok";