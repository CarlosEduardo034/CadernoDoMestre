<?php
require_once("../../config/database.php");
require_once("../../middlewares/auth.php");

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
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    echo "nao_permitido";
    exit;
}

$dado = $res->fetch_assoc();

if ($dado['is_lixeira'] == 1) {
    echo "capitulo_na_lixeira";
    exit;
}

$sql = "UPDATE paginas SET is_lixeira = 0 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

echo $stmt->execute() ? "ok" : "erro";