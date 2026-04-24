<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "caderno_do_mestre";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

// <?php
// $host = "sql100.infinityfree.com";
// $user = "if0_41745169";
// $pass = "T3L6FlSnwTWhRYH";
// $db = "if0_41745169_caderno_do_mestre02";

// $conn = new mysqli($host, $user, $pass, $db);

// if ($conn->connect_error) {
//     die("Erro: " . $conn->connect_error);
// }