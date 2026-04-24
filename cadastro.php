<?php
session_start();
include("Config/Config.php");

$email = $_POST['email'] ?? null;
$senha = $_POST['senha'] ?? null;

if (!$email || !$senha) {
    echo "dados_invalidos";
    exit;
}

$email = strtolower(trim($email));

$sql = "SELECT id FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "email_ja_cadastrado";
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (email, senha) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $senhaHash);

if ($stmt->execute()) {
    $userId = $stmt->insert_id;
    $_SESSION['id'] = $userId;

    echo "ok";
} else {
    echo "erro";
}