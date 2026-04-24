<?php
session_start();
include("Config/Config.php");

$id = $_POST['id'];
$usuario_id = $_SESSION['id'];

// apaga páginas primeiro
$sql = "DELETE FROM paginas WHERE capitulo_id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();

// apaga capítulo
$sql = "DELETE FROM capitulos WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);

echo $stmt->execute() ? "ok" : "erro";