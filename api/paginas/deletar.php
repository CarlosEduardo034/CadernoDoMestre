<?php
require_once("../../config/database.php");
require_once("../../middlewares/auth.php");

$id = $_POST['id'] ?? null;
$usuario_id = $_SESSION['id'];

$sql = "DELETE FROM paginas WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);

echo $stmt->execute() ? "ok" : "erro";