<!-- teste -->

<?php

require 'conecta.php';
require 'header.php';

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    echo '<div class="alert alert-danger">❌ ID inválido.</div>';
    require 'footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM jogadores WHERE id_jogadores = :id");
$stmt->execute([':id' => $id]);
$j = $stmt->fetch();

if (!$j) {
    echo '<div class="alert alert-danger">Atleta não encontrado.</div>';
    require 'footer.php';
    exit;
}

$usuarios = $pdo->query("SELECT id_usuarios, nome FROM USUARIOS ORDER BY nome")->fetchAll();

function sel($val, $current): string {
    return $val === $current ? 'selected' : '';
}
?>

<h1>✏ Editar Jogador — <?= htmlspecialchars($j['nome']) ?></h1>

<div class="form-card">
    <form action="recebe.php" method="POST">
        <input type="hidden" name="acao" value="alterar">
        <input type="hidden" name="id"   value="<?= $j['id_jogadores'] ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Nome completo *</label>
                <input type="text" name="nome" maxlength="120" required
                       value="<?= htmlspecialchars($j['nome']) ?>">
            </div>
            <div class="form-group">
                <label>Clube *</label>
                <input type="text" name="clube" maxlength="120" required
                       value="<?= htmlspecialchars($j['clube']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Posição *</label>
                <select name="posicao" required>
                    <?php foreach (['Goleiro','Lateral Direito','Lateral Esquerdo','Zagueiro',
                                    'Volante','Meia','Atacante','Ponta Direita',
                                    'Ponta Esquerda','Centroavante'] as $p): ?>
                        <option value="<?= $p ?>" <?= sel($p, $j['posicao']) ?>><?= $p ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Categoria *</label>
                <select name="categoria" required>
                    <?php foreach (['Profissional','Sub-23','Sub-20','Sub-17','Sub-15','Sub-13'] as $c): ?>
                        <option value="<?= $c ?>" <?= sel($c, $j['categoria']) ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nacionalidade *</label>
                <input type="text" name="nacionalidade" maxlength="80" required
                       value="<?= htmlspecialchars($j['nacionalidade']) ?>">
            </div>
            <div class="form-group">
                <label>Pé dominante *</label>
                <select name="pe_dominante" required>
                    <?php foreach (['Direito','Esquerdo','Ambidestro'] as $pe): ?>
                        <option value="<?= $pe ?>" <?= sel($pe, $j['pe_dominante']) ?>><?= $pe ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Idade *</label>
                <input type="number" name="idade" min="10" max="60" required value="<?= $j['idade'] ?>">
            </div>
            <div class="form-group">
                <label>Altura (cm) *</label>
                <input type="number" name="altura_cm" min="140" max="230" required value="<?= $j['altura_cm'] ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Peso (kg) *</label>
                <input type="number" name="peso_kg" min="40" max="150" required value="<?= $j['peso_kg'] ?>">
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <?php foreach (['Ativo','Suspenso','Lesionado','Inativo'] as $st): ?>
                        <option value="<?= $st ?>" <?= sel($st, $j['status']) ?>><?= $st ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Cadastrado por</label>
                <select name="cadastrado_por">
                    <option value="">-- Nenhum --</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u['id_usuarios'] ?>"
                            <?= sel((string)$u['id_usuarios'], (string)$j['cadastrado_por']) ?>>
                            <?= htmlspecialchars($u['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Observações</label>
                <input type="text" name="obs" maxlength="100"
                       value="<?= htmlspecialchars($j['obs'] ?? '') ?>">
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:8px;">
            <button type="submit" class="btn btn-warning">💾 Salvar Alterações</button>
            <a href="index.php" class="btn btn-primary">← Cancelar</a>
        </div>
    </form>
</div>

<?php require 'footer.php'; ?>
