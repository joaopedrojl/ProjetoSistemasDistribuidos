<?php
session_start();

try {
    $pdo = new PDO(
        'mysql:host=mysql;dbname=pedidos_online_db;charset=utf8mb4', // host é o serviço Docker
        'root', // usuário
        'root123', // senha
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('Erro ao conectar ao banco: ' . $e->getMessage());
}

// Facilita o uso dos dados do usuário
if (isset($_SESSION['usuario'])) {
    $_SESSION['user_id'] = $_SESSION['usuario']['id'];
    $_SESSION['user_nome'] = $_SESSION['usuario']['nome'];
    $_SESSION['user_email'] = $_SESSION['usuario']['email'];
}
