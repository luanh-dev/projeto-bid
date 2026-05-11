<?php

require 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$acao          = $_POST['acao']          ?? '';
$nome          = trim($_POST['nome']          ?? '');
$posicao       = trim($_POST['posicao']       ?? '');
$categoria     = trim($_POST['categoria']     ?? '');
$idade         = (int)($_POST['idade']        ?? 0);
$clube         = trim($_POST['clube']         ?? '');
$nacionalidade = trim($_POST['nacionalidade'] ?? '');
$pe_dominante  = trim($_POST['pe_dominante']  ?? '');
$altura_cm     = (int)($_POST['altura_cm']    ?? 0);
$peso_kg       = (int)($_POST['peso_kg']      ?? 0);
$status        = trim($_POST['status']        ?? 'Ativo');
$obs           = trim($_POST['obs']           ?? '') ?: null;
$cadastrado_por = !empty($_POST['cadastrado_por']) ? (int)$_POST['cadastrado_por'] : null;

if (!$nome || !$posicao || !$categoria || !$clube || !$nacionalidade || !$pe_dominante) {
    header('Location: index.php?msg=erro');
    exit;
}

try {
    if ($acao === 'inserir') {
        
        $stmt = $pdo->prepare("
            INSERT INTO jogadores
                (nome, posicao, categoria, idade, clube, nacionalidade,
                 pe_dominante, altura_cm, peso_kg, status, obs, cadastrado_por)
            VALUES
                (:nome, :posicao, :categoria, :idade, :clube, :nacionalidade,
                 :pe_dominante, :altura_cm, :peso_kg, :status, :obs, :cadastrado_por)
        ");
        $stmt->execute([
            ':nome'          => $nome,
            ':posicao'       => $posicao,
            ':categoria'     => $categoria,
            ':idade'         => $idade,
            ':clube'         => $clube,
            ':nacionalidade' => $nacionalidade,
            ':pe_dominante'  => $pe_dominante,
            ':altura_cm'     => $altura_cm,
            ':peso_kg'       => $peso_kg,
            ':status'        => $status,
            ':obs'           => $obs,
            ':cadastrado_por'=> $cadastrado_por,
        ]);
        header('Location: index.php?msg=inserido');

    } elseif ($acao === 'alterar') {
        
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            header('Location: index.php?msg=erro');
            exit;
        }
        $stmt = $pdo->prepare("
            UPDATE jogadores SET
                nome           = :nome,
                posicao        = :posicao,
                categoria      = :categoria,
                idade          = :idade,
                clube          = :clube,
                nacionalidade  = :nacionalidade,
                pe_dominante   = :pe_dominante,
                altura_cm      = :altura_cm,
                peso_kg        = :peso_kg,
                status         = :status,
                obs            = :obs,
                cadastrado_por = :cadastrado_por
            WHERE id_jogadores = :id
        ");
        $stmt->execute([
            ':nome'          => $nome,
            ':posicao'       => $posicao,
            ':categoria'     => $categoria,
            ':idade'         => $idade,
            ':clube'         => $clube,
            ':nacionalidade' => $nacionalidade,
            ':pe_dominante'  => $pe_dominante,
            ':altura_cm'     => $altura_cm,
            ':peso_kg'       => $peso_kg,
            ':status'        => $status,
            ':obs'           => $obs,
            ':cadastrado_por'=> $cadastrado_por,
            ':id'            => $id,
        ]);
        header('Location: index.php?msg=alterado');

    } else {
        header('Location: index.php?msg=erro');
    }

} catch (PDOException $e) {
    
    error_log($e->getMessage());
    header('Location: index.php?msg=erro');
}

exit;