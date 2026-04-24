<?php
session_start();
include("Config/Config.php");

if (!isset($_SESSION['id'])) {
    echo "nao_autorizado";
    exit;
}

$id = $_POST['id'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$id) {
    echo "dados_invalidos";
    exit;
}

// verifica se pertence ao usuário
$sql = "SELECT id FROM capitulos WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "nao_permitido";
    exit;
}

// 🔥 agora só marca como lixeira
$sql = "UPDATE capitulos SET is_lixeira = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "erro";
}