<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo "nao_autorizado";
    exit;
}