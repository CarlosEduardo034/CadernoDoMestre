<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit;
}
?>

<h1>Bem-vindo ao seu caderno 📒</h1>