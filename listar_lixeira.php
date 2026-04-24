<?php
session_start();
include("Config/Config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

if (!isset($_SESSION['id'])) {
    echo json_encode([]);
    exit;
}

$usuario_id = $_SESSION['id'];
$dados = [];

// CAPÍTULOS
$sql = "SELECT id, nome AS titulo, 'capitulo' AS tipo, NULL AS capitulo_nome 
        FROM capitulos 
        WHERE usuario_id = ? AND is_lixeira = 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["erro" => $conn->error]);
    exit;
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $dados[] = $row;
}

// PÁGINAS
$sql = "SELECT 
            p.id,
            p.titulo,
            'pagina' AS tipo,
            c.nome AS capitulo_nome
        FROM paginas p
        LEFT JOIN capitulos c ON p.capitulo_id = c.id
        WHERE p.usuario_id = ? AND p.is_lixeira = 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["erro" => $conn->error]);
    exit;
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $dados[] = $row;
}

echo json_encode($dados);