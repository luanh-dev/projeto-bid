<?php

$host   = 'localhost';
$dbname = 'Projeto_bid';       // ← Altere para o nome do seu banco
$user   = 'root';            // ← Altere para seu usuário
$pass   = '';                // ← Altere para sua senha
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('<p style="color:red;font-family:sans-serif;">
        Erro de conexão: ' . $e->getMessage() . '
    </p>');
}
