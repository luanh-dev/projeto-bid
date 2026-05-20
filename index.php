<?php

require 'conecta.php';
require 'header.php';

if (!empty($_GET['msg'])) {
    $msgs = [
        'inserido'  => ['type' => 'success', 'text' => 'Jogador cadastrado com sucesso!'],
        'alterado'  => ['type' => 'success', 'text' => 'Jogador atualizado com sucesso!'],
        'excluido'  => ['type' => 'success', 'text' => 'Jogador excluído com sucesso!'],
        'erro'      => ['type' => 'danger',  'text' => 'Ocorreu um erro na operação.'],
    'permissao_negada' => ['type' => 'danger', 'text' => 'Acesso negado: apenas administradores podem realizar esta ação.'],
    ];
    $m = $msgs[$_GET['msg']] ?? null;
    if ($m) {
        echo "<div class='alert alert-{$m['type']}'>{$m['text']}</div>";
    }
}

$busca = trim($_GET['busca'] ?? '');
$where = '';
$params = [];

if ($busca !== '') {
    $where = "WHERE j.nome LIKE :busca OR j.clube LIKE :busca2 OR j.posicao LIKE :busca3";
    $params = [':busca' => "%$busca%", ':busca2' => "%$busca%", ':busca3' => "%$busca%"];
}

$stmt = $pdo->prepare("
    SELECT j.*, u.nome AS cadastrado_por_nome
    FROM jogadores j
    LEFT JOIN USUARIOS u ON j.cadastrado_por = u.id_usuarios
    $where
    ORDER BY j.nome ASC
");
$stmt->execute($params);
$jogadores = $stmt->fetchAll();
?>

<h1>Jogadores Cadastrados</h1>

<div style="display:flex; gap:10px; margin-bottom:18px; align-items:center; flex-wrap:wrap;">
    <form method="GET" style="display:flex; gap:8px; flex:1; min-width:240px;">
        <input type="text" name="busca" placeholder="Buscar por nome, clube ou posição..."
               value="<?= htmlspecialchars($busca) ?>"
               style="flex:1; padding:8px 12px; border:1px solid #ccc; border-radius:5px; font-size:.92rem;">
        <button type="submit" class="btn btn-primary">🔍 Buscar</button>
        <?php if ($busca): ?>
            <a href="index.php" class="btn btn-info">✖ Limpar</a>
        <?php endif; ?>
    </form>
    <?php if (isAdmin()): ?>
        <a href="insere.php" class="btn btn-success">➕ Novo Jogador</a>
    <?php endif; ?>
</div>

<?php if (count($jogadores) === 0): ?>
    <div class="alert alert-info">Nenhum jogador encontrado.</div>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Posição</th>
            <th>Categoria</th>
            <th>Clube</th>
            <th>Idade</th>
            <th>Pé</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($jogadores as $j): ?>
        <?php
            $badgeClass = match($j['status']) {
                'Ativo'     => 'badge-ativo',
                'Suspenso'  => 'badge-suspenso',
                'Lesionado' => 'badge-lesionado',
                default     => 'badge-inativo',
            };
        ?>
        <tr>
            <td><?= $j['id_jogadores'] ?></td>
            <td><strong><?= htmlspecialchars($j['nome']) ?></strong></td>
            <td><?= htmlspecialchars($j['posicao']) ?></td>
            <td><?= htmlspecialchars($j['categoria']) ?></td>
            <td><?= htmlspecialchars($j['clube']) ?></td>
            <td><?= $j['idade'] ?> anos</td>
            <td><?= htmlspecialchars($j['pe_dominante']) ?></td>
            <td><span class="badge <?= $badgeClass ?>"><?= $j['status'] ?></span></td>
            <?php if (isAdmin()): ?>
            <td style="white-space:nowrap;">
                <a href="altera.php?id=<?= $j['id_jogadores'] ?>" class="btn btn-warning btn-sm">✏ Editar</a>
                <a href="exclui.php?id=<?= $j['id_jogadores'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Confirma a exclusão de <?= htmlspecialchars($j['nome']) ?>?')">🗑 Excluir</a>
            </td>
            <?php else: ?>
            <td>—</td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<p style="margin-top:10px; font-size:.85rem; color:#666;">
    Total: <strong><?= count($jogadores) ?></strong> jogador(es) encontrado(s).
</p>
<?php endif; ?>

<?php require 'footer.php'; ?>
