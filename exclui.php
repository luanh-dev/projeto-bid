<?php

require 'conecta.php';

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header('Location: index.php?msg=erro');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM jogadores WHERE id_jogadores = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        header('Location: index.php?msg=excluido');
    } else {
        /* Nenhuma linha afetada — ID inexistente */
        header('Location: index.php?msg=erro');
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: index.php?msg=erro');
}

exit;
