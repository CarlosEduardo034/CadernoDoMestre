<?php
require_once("../../config/database.php");
require_once("../../middlewares/auth.php");

header("Content-Type: application/json");

$usuario_id = $_SESSION['id'];
$dados = [];

$sql = "SELECT 
            id, 
            nome AS titulo, 
            'capitulo' AS tipo,
            NULL AS capitulo_nome,
            NULL AS capitulo_na_lixeira
        FROM capitulos 
        WHERE usuario_id = ? AND is_lixeira = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $dados[] = $row;
}

$sql = "SELECT 
            p.id,
            p.titulo,
            'pagina' AS tipo,
            c.nome AS capitulo_nome,
            c.is_lixeira AS capitulo_na_lixeira
        FROM paginas p
        LEFT JOIN capitulos c ON p.capitulo_id = c.id
        WHERE p.usuario_id = ? AND p.is_lixeira = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $dados[] = $row;
}

echo json_encode($dados);