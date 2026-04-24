<?php
session_start();
include("Config/Config.php");

if (!isset($_SESSION['id'])) {
    echo "nao_autorizado";
    exit;
}

$titulo = $_POST['titulo'] ?? null;
$capitulo_id = $_POST['capitulo_id'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$titulo || !$capitulo_id) {
    echo "dados_invalidos";
    exit;
}

$sql = "SELECT id FROM capitulos WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $capitulo_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "capitulo_invalido";
    exit;
}

// inserir página
$sql = "INSERT INTO paginas (capitulo_id, usuario_id, titulo) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $capitulo_id, $usuario_id, $titulo);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "erro";
}