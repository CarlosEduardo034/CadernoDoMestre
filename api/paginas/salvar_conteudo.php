<?php
require_once("../../config/database.php");
require_once("../../middlewares/auth.php");

$id = $_POST['id'] ?? null;
$conteudo = $_POST['conteudo'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$id) {
    echo "dados_invalidos";
    exit;
}

// valida dono
$sql = "SELECT id FROM paginas WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();

if ($stmt->get_result()->num_rows == 0) {
    echo "nao_permitido";
    exit;
}

// update
$sql = "UPDATE paginas SET conteudo = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $conteudo, $id);

echo $stmt->execute() ? "ok" : "erro";