<?php
session_start();
include("Config/Config.php");

$id = $_POST['id'];
$usuario_id = $_SESSION['id'];

$sql = "UPDATE paginas SET is_lixeira = 0 WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);

echo $stmt->execute() ? "ok" : "erro";