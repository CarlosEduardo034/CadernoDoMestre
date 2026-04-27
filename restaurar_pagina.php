<?php
session_start();
include("Config/Config.php");

$id = $_POST['id'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$id) {
    echo "dados_invalidos";
    exit;
}

$sql = "SELECT p.capitulo_id, c.is_lixeira 
        FROM paginas p
        JOIN capitulos c ON p.capitulo_id = c.id
        WHERE p.id = ? AND p.usuario_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "nao_permitido";
    exit;
}

$dado = $result->fetch_assoc();

if ($dado['is_lixeira'] == 1) {
    echo "capitulo_na_lixeira";
    exit;
}

$sql = "UPDATE paginas SET is_lixeira = 0 WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);

echo $stmt->execute() ? "ok" : "erro";