<?php

require 'conecta.php';
require 'header.php';

/* ── Filtros via GET ── */
$filtroStatus    = $_GET['status']    ?? '';
$filtroposicao   = $_GET['posicao']   ?? '';
$filtroCategoria = $_GET['categoria'] ?? '';

$conditions = [];
$params     = [];

if ($filtroStatus) {
    $conditions[] = "j.status = :status";
    $params[':status'] = $filtroStatus;
}
if ($filtroposicao) {
    $conditions[] = "j.posicao = :posicao";
    $params[':posicao'] = $filtroposicao;
}
if ($filtroCategoria) {
    $conditions[] = "j.categoria = :categoria";
    $params[':categoria'] = $filtroCategoria;
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

/* ── Query principal ── */
$stmt = $pdo->prepare("
    SELECT j.*, u.nome AS cadastrado_por_nome
    FROM jogadores j
    LEFT JOIN USUARIOS u ON j.cadastrado_por = u.id_usuarios
    $where
    ORDER BY j.nome ASC
");
$stmt->execute($params);
$jogadores = $stmt->fetchAll();

/* ── Totalizadores por status ── */
$totais = $pdo->query("
    SELECT status, COUNT(*) AS qtd FROM jogadores GROUP BY status
")->fetchAll(PDO::FETCH_KEY_PAIR);

/* ── Média de idade/altura/peso ── */
$medias = $pdo->query("
    SELECT
        ROUND(AVG(idade), 1)     AS media_idade,
        ROUND(AVG(altura_cm), 1) AS media_altura,
        ROUND(AVG(peso_kg), 1)   AS media_peso
    FROM jogadores
")->fetch();
?>

<h1>📊 Relatório de Jogadores</h1>

<!-- Cards de resumo -->
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:12px; margin-bottom:24px;">
    <?php
    $cards = [
        ['label'=>'Total',     'val'=> count($pdo->query("SELECT id_jogadores FROM jogadores")->fetchAll()), 'color'=>'#1a3a5c'],
        ['label'=>'Ativos',    'val'=> $totais['Ativo']     ?? 0, 'color'=>'#1a8a5a'],
        ['label'=>'Lesionados','val'=> $totais['Lesionado'] ?? 0, 'color'=>'#c0392b'],
        ['label'=>'Suspensos', 'val'=> $totais['Suspenso']  ?? 0, 'color'=>'#d4860e'],
        ['label'=>'Inativos',  'val'=> $totais['Inativo']   ?? 0, 'color'=>'#888'],
        ['label'=>'Média Idade','val'=> ($medias['media_idade'] ?? '-') . ' anos', 'color'=>'#2471a3'],
        ['label'=>'Média Alt.', 'val'=> ($medias['media_altura'] ?? '-') . ' cm',  'color'=>'#2471a3'],
        ['label'=>'Média Peso', 'val'=> ($medias['media_peso'] ?? '-') . ' kg',    'color'=>'#2471a3'],
    ];
    foreach ($cards as $card): ?>
    <div style="background:#fff; border-radius:8px; border:1px solid #e0e6ed; padding:14px 16px; text-align:center;">
        <div style="font-size:.78rem; color:#888; font-weight:600; text-transform:uppercase; margin-bottom:6px;">
            <?= $card['label'] ?>
        </div>
        <div style="font-size:1.35rem; font-weight:700; color:<?= $card['color'] ?>;">
            <?= $card['val'] ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filtros -->
<form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px; align-items:flex-end;">
    <div>
        <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Status</label>
        <select name="status" style="padding:7px 10px; border:1px solid #ccc; border-radius:5px; font-size:.9rem;">
            <option value="">Todos</option>
            <?php foreach (['Ativo','Suspenso','Lesionado','Inativo'] as $s): ?>
                <option value="<?= $s ?>" <?= $filtroStatus === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Posição</label>
        <select name="posicao" style="padding:7px 10px; border:1px solid #ccc; border-radius:5px; font-size:.9rem;">
            <option value="">Todas</option>
            <?php foreach (['Goleiro','Lateral Direito','Lateral Esquerdo','Zagueiro',
                             'Volante','Meia','Atacante','Ponta Direita','Ponta Esquerda','Centroavante'] as $p): ?>
                <option value="<?= $p ?>" <?= $filtroposicao === $p ? 'selected' : '' ?>><?= $p ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Categoria</label>
        <select name="categoria" style="padding:7px 10px; border:1px solid #ccc; border-radius:5px; font-size:.9rem;">
            <option value="">Todas</option>
            <?php foreach (['Profissional','Sub-23','Sub-20','Sub-17','Sub-15','Sub-13'] as $c): ?>
                <option value="<?= $c ?>" <?= $filtroCategoria === $c ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">🔍 Filtrar</button>
    <a href="relatorio.php" class="btn btn-info">✖ Limpar</a>
    <button type="button" onclick="window.print()" class="btn btn-success" style="margin-left:auto;">🖨 Imprimir</button>
</form>

<!-- Tabela detalhada -->
<?php if (!$jogadores): ?>
    <div class="alert alert-info">Nenhum resultado para os filtros aplicados.</div>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Posição</th>
            <th>Categoria</th>
            <th>Clube</th>
            <th>Nac.</th>
            <th>Idade</th>
            <th>Alt.</th>
            <th>Peso</th>
            <th>Pé</th>
            <th>Status</th>
            <th>Cadastrado por</th>
            <th>Obs.</th>
            <th>Criado em</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($jogadores as $j):
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
            <td><?= htmlspecialchars($j['nacionalidade']) ?></td>
            <td><?= $j['idade'] ?></td>
            <td><?= $j['altura_cm'] ?> cm</td>
            <td><?= $j['peso_kg'] ?> kg</td>
            <td><?= htmlspecialchars($j['pe_dominante']) ?></td>
            <td><span class="badge <?= $badgeClass ?>"><?= $j['status'] ?></span></td>
            <td><?= htmlspecialchars($j['cadastrado_por_nome'] ?? '—') ?></td>
            <td style="font-size:.82rem; color:#666;"><?= htmlspecialchars($j['obs'] ?? '—') ?></td>
            <td style="font-size:.82rem;"><?= $j['criado_em'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<p style="margin-top:10px; font-size:.85rem; color:#666;">
    Exibindo <strong><?= count($jogadores) ?></strong> registro(s).
</p>
<?php endif; ?>

<style>
@media print {
    nav, form, .btn { display: none !important; }
    body { background: #fff; }
    table { box-shadow: none; }
}
</style>

<?php require 'footer.php'; ?>
