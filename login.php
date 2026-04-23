<?php
session_start();
include("Config/Config.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($senha, $user['senha'])) {
        $_SESSION['id'] = $user['id'];
        echo "ok";
    } else {
        echo "senha_incorreta";
    }
} else {
    echo "usuario_nao_encontrado";
}