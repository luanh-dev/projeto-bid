<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

function isAdmin() {
    return isset($_SESSION['usuario_nivel']) && $_SESSION['usuario_nivel'] === 'admin';
}

function checkAdmin() {
    if (!isAdmin()) {
        header('Location: index.php?msg=permissao_negada');
        exit;
    }
}
