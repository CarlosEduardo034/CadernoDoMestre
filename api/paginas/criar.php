<?php
require_once("../../config/database.php");
require_once("../../middlewares/auth.php");

$titulo = $_POST['titulo'] ?? null;
$capitulo_id = $_POST['capitulo_id'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$titulo || !$capitulo_id) {
    echo "dados_invalidos";
    exit;
}

// valida capítulo do usuário
$sql = "SELECT id FROM capitulos WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $capitulo_id, $usuario_id);
$stmt->execute();

if ($stmt->get_result()->num_rows == 0) {
    echo "capitulo_invalido";
    exit;
}

// inserir
$sql = "INSERT INTO paginas (capitulo_id, usuario_id, titulo) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $capitulo_id, $usuario_id, $titulo);

echo $stmt->execute() ? "ok" : "erro";