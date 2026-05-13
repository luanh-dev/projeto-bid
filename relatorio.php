<?php
require 'conecta.php';
require 'header.php';

/* ── Filtros via GET ── */
$filtroNome      = trim($_GET['nome'] ?? '');
$filtroClube     = trim($_GET['clube'] ?? '');
$filtroStatus    = $_GET['status']    ?? '';
$filtroPosicao   = $_GET['posicao']   ?? '';
$filtroCategoria = $_GET['categoria'] ?? '';
$filtroPe        = $_GET['pe']        ?? '';
$filtroIdadeMin  = $_GET['idade_min']  ?? '';
$filtroIdadeMax  = $_GET['idade_max']  ?? '';

$conditions = [];
$params     = [];

if ($filtroNome) {
    $conditions[] = "j.nome LIKE :nome";
    $params[':nome'] = "%$filtroNome%";
}
if ($filtroClube) {
    $conditions[] = "j.clube LIKE :clube";
    $params[':clube'] = "%$filtroClube%";
}
if ($filtroStatus) {
    $conditions[] = "j.status = :status";
    $params[':status'] = $filtroStatus;
}
if ($filtroPosicao) {
    $conditions[] = "j.posicao = :posicao";
    $params[':posicao'] = $filtroPosicao;
}
if ($filtroCategoria) {
    $conditions[] = "j.categoria = :categoria";
    $params[':categoria'] = $filtroCategoria;
}
if ($filtroPe) {
    $conditions[] = "j.pe_dominante = :pe";
    $params[':pe'] = $filtroPe;
}
if ($filtroIdadeMin !== '') {
    $conditions[] = "j.idade >= :idade_min";
    $params[':idade_min'] = (int)$filtroIdadeMin;
}
if ($filtroIdadeMax !== '') {
    $conditions[] = "j.idade <= :idade_max";
    $params[':idade_max'] = (int)$filtroIdadeMax;
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

<h1>📊 Relatório Avançado de Jogadores</h1>

<!-- Cards de resumo -->
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:12px; margin-bottom:24px;">
    <?php
    $totalGeral = $pdo->query("SELECT COUNT(*) FROM jogadores")->fetchColumn();
    $cards = [
        ['label'=>'Total',     'val'=> $totalGeral, 'color'=>'#1a3a5c'],
        ['label'=>'Ativos',    'val'=> $totais['Ativo']     ?? 0, 'color'=>'#1a8a5a'],
        ['label'=>'Lesionados','val'=> $totais['Lesionado'] ?? 0, 'color'=>'#c0392b'],
        ['label'=>'Suspensos', 'val'=> $totais['Suspenso']  ?? 0, 'color'=>'#d4860e'],
        ['label'=>'Média Idade','val'=> ($medias['media_idade'] ?? '-') . ' anos', 'color'=>'#2471a3'],
        ['label'=>'Média Alt.', 'val'=> ($medias['media_altura'] ?? '-') . ' cm',  'color'=>'#2471a3'],
    ];
    foreach ($cards as $card): ?>
    <div style="background:#fff; border-radius:8px; border:1px solid #e0e6ed; padding:14px 16px; text-align:center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="font-size:.78rem; color:#888; font-weight:600; text-transform:uppercase; margin-bottom:6px;">
            <?= $card['label'] ?>
        </div>
        <div style="font-size:1.35rem; font-weight:700; color:<?= $card['color'] ?>;">
            <?= $card['val'] ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filtros Aprimorados -->
<div class="form-card" style="max-width: 100%; margin-bottom: 25px; padding: 20px;">
    <h3 style="margin-bottom: 15px; font-size: 1.1rem; color: #1a3a5c;">🔍 Filtros de Busca</h3>
    <form method="GET" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div>
            <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Nome do Jogador</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($filtroNome) ?>" placeholder="Buscar por nome..." style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
        </div>
        <div>
            <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Clube</label>
            <input type="text" name="clube" value="<?= htmlspecialchars($filtroClube) ?>" placeholder="Buscar por clube..." style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
        </div>
        <div>
            <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Status</label>
            <select name="status" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
                <option value="">Todos os Status</option>
                <?php foreach (['Ativo','Suspenso','Lesionado','Inativo'] as $s): ?>
                    <option value="<?= $s ?>" <?= $filtroStatus === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Posição</label>
            <select name="posicao" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
                <option value="">Todas as Posições</option>
                <?php foreach (['Goleiro','Lateral Direito','Lateral Esquerdo','Zagueiro','Volante','Meia','Atacante','Ponta Direita','Ponta Esquerda','Centroavante'] as $p): ?>
                    <option value="<?= $p ?>" <?= $filtroPosicao === $p ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Categoria</label>
            <select name="categoria" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
                <option value="">Todas as Categorias</option>
                <?php foreach (['Profissional','Sub-23','Sub-20','Sub-17','Sub-15','Sub-13'] as $c): ?>
                    <option value="<?= $c ?>" <?= $filtroCategoria === $c ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Pé Dominante</label>
            <select name="pe" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
                <option value="">Todos</option>
                <?php foreach (['Direito','Esquerdo','Ambidestro'] as $pe): ?>
                    <option value="<?= $pe ?>" <?= $filtroPe === $pe ? 'selected' : '' ?>><?= $pe ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display: flex; gap: 10px;">
            <div style="flex: 1;">
                <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Idade Mín.</label>
                <input type="number" name="idade_min" value="<?= htmlspecialchars($filtroIdadeMin) ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>
            <div style="flex: 1;">
                <label style="display:block; font-size:.82rem; font-weight:600; color:#555; margin-bottom:4px;">Idade Máx.</label>
                <input type="number" name="idade_max" value="<?= htmlspecialchars($filtroIdadeMax) ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>
        </div>
        <div style="display:flex; gap:10px; align-items:flex-end;">
            <button type="submit" class="btn btn-primary" style="flex:1;">🔍 Filtrar</button>
            <a href="relatorio.php" class="btn btn-info" style="flex:1; text-align:center;">✖ Limpar</a>
        </div>
    </form>
    <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px; display: flex; justify-content: flex-end;">
        <button type="button" onclick="window.print()" class="btn btn-success">🖨 Imprimir Relatório</button>
    </div>
</div>

<!-- Tabela detalhada -->
<?php if (!$jogadores): ?>
    <div class="alert alert-info">Nenhum resultado para os filtros aplicados.</div>
<?php else: ?>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Posição</th>
                    <th>Categoria</th>
                    <th>Clube</th>
                    <th>Idade</th>
                    <th>Alt.</th>
                    <th>Peso</th>
                    <th>Pé</th>
                    <th>Status</th>
                    <th>Cadastrado por</th>
                    <th>Data</th>
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
                    <td><?= $j['idade'] ?></td>
                    <td><?= $j['altura_cm'] ?> cm</td>
                    <td><?= $j['peso_kg'] ?> kg</td>
                    <td><?= htmlspecialchars($j['pe_dominante']) ?></td>
                    <td><span class="badge <?= $badgeClass ?>"><?= $j['status'] ?></span></td>
                    <td><?= htmlspecialchars($j['cadastrado_por_nome'] ?? '—') ?></td>
                    <td style="font-size:.82rem;"><?= date('d/m/Y', strtotime($j['criado_em'])) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p style="margin-top:10px; font-size:.85rem; color:#666;">
        Exibindo <strong><?= count($jogadores) ?></strong> registro(s) encontrados.
    </p>
<?php endif; ?>

<style>
@media print {
    nav, .form-card, .btn, .footer-links { display: none !important; }
    body { background: #fff; padding: 0; }
    main { margin: 0; max-width: 100%; }
    table { box-shadow: none; border: 1px solid #ccc; width: 100%; font-size: 0.8rem; }
    th, td { border: 1px solid #eee; padding: 5px; }
    h1 { font-size: 1.2rem; margin-bottom: 10px; }
    .badge { border: 1px solid #ccc; padding: 2px 5px; }
}
</style>

<?php require 'footer.php'; ?>
