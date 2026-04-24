<?php
session_start();
include("Config/Config.php");

if (!isset($_SESSION['id'])) {
    echo "nao_autorizado";
    exit;
}

$id = $_POST['id'] ?? null;
$titulo = $_POST['titulo'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$id || !$titulo) {
    echo "dados_invalidos";
    exit;
}

// verifica se a página pertence ao usuário
$sql = "SELECT id FROM paginas WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "nao_permitido";
    exit;
}

// atualiza título
$sql = "UPDATE paginas SET titulo = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $titulo, $id);

echo $stmt->execute() ? "ok" : "erro";