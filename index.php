<?php
// CONFIGURAÇÃO DO BANCO
$host = "localhost";
$dbname = "crud_atletas";
$user = "root";
$pass = "";

// CONEXÃO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// CRIAR
if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $clube = $_POST['clube'];
    $posicao = $_POST['posicao'];

    $sql = $pdo->prepare("INSERT INTO atletas (nome, clube, posicao) VALUES (?, ?, ?)");
    $sql->execute([$nome, $clube, $posicao]);
    header("Location: index.php");
}

// EXCLUIR
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    $sql = $pdo->prepare("DELETE FROM atletas WHERE id=?");
    $sql->execute([$id]);
    header("Location: header.php");
}

// BUSCAR DADOS PARA EDIÇÃO
$atleta = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $sql = $pdo->prepare("SELECT * FROM atletas WHERE id=?");
    $sql->execute([$id]);
    $atleta = $sql->fetch(PDO::FETCH_ASSOC);
}

// ATUALIZAR
if (isset($_POST['atualizar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $clube = $_POST['clube'];
    $posicao = $_POST['posicao'];

    $sql = $pdo->prepare("UPDATE atletas SET nome=?, clube=?, posicao=? WHERE id=?");
    $sql->execute([$nome, $clube, $posicao, $id]);
    header("Location: header.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Boletim Informativo Diário - BID</title>
    <style>
        body { font-family: Arial; width: 800px; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        input { padding: 8px; margin: 5px; }
        button { padding: 8px 15px; cursor: pointer; }
    </style>
</head>
<body>

<h2>Sistema de Cadastro de Atletas</h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= $atleta['id'] ?? '' ?>">
    
    <input type="text" name="nome" placeholder="Nome do atleta" required value="<?= $atleta['nome'] ?? '' ?>">
    <input type="text" name="clube" placeholder="Clube" required value="<?= $atleta['clube'] ?? '' ?>">
    <input type="text" name="posicao" placeholder="Posição" required value="<?= $atleta['posicao'] ?? '' ?>">

    <?php if ($atleta): ?>
        <button type="submit" name="atualizar">Atualizar</button>
    <?php else: ?>
        <button type="submit" name="cadastrar">Cadastrar</button>
    <?php endif; ?>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Clube</th>
        <th>Posição</th>
        <th>Ações</th>
    </tr>

    <?php
    $sql = $pdo->query("SELECT * FROM atletas");
    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        echo "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['nome']}</td>
            <td>{$row['clube']}</td>
            <td>{$row['posicao']}</td>
            <td>
                <a href='?editar={$row['id']}'>Editar</a> |
                <a href='?deletar={$row['id']}'>Excluir</a>
            </td>
        </tr>";
    }
    ?>
</table>

</body>
</html>