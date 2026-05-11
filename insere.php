<?php
/* ============================================================
   📁 ARQUIVO: insere.php
   📌 FUNÇÃO : Exibe o formulário de cadastro de novo jogador
   🔗 DEPENDE : conecta.php | header.php | footer.php
   📤 ENVIA PARA: recebe.php (método POST)
   ============================================================ */

require 'conecta.php';
require 'header.php';

/* ── Busca usuários para o campo "cadastrado_por" ── */
$usuarios = $pdo->query("SELECT id_usuarios, nome FROM USUARIOS ORDER BY nome")->fetchAll();
?>

<h1>➕ Cadastrar Novo Jogador</h1>

<div class="form-card">
    <form action="recebe.php" method="POST">
        <input type="hidden" name="acao" value="inserir">

        <div class="form-row">
            <div class="form-group">
                <label>Nome completo *</label>
                <input type="text" name="nome" maxlength="120" required placeholder="Ex.: Carlos Eduardo Silva">
            </div>
            <div class="form-group">
                <label>Clube *</label>
                <input type="text" name="clube" maxlength="120" required placeholder="Ex.: Esporte Clube Vitória">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Posição *</label>
                <select name="posicao" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach (['Goleiro','Lateral Direito','Lateral Esquerdo','Zagueiro',
                                    'Volante','Meia','Atacante','Ponta Direita',
                                    'Ponta Esquerda','Centroavante'] as $p): ?>
                        <option value="<?= $p ?>"><?= $p ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Categoria *</label>
                <select name="categoria" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach (['Profissional','Sub-23','Sub-20','Sub-17','Sub-15','Sub-13'] as $c): ?>
                        <option value="<?= $c ?>"><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nacionalidade *</label>
                <input type="text" name="nacionalidade" maxlength="80" required placeholder="Ex.: Brasileiro">
            </div>
            <div class="form-group">
                <label>Pé dominante *</label>
                <select name="pe_dominante" required>
                    <option value="">-- Selecione --</option>
                    <option value="Direito">Direito</option>
                    <option value="Esquerdo">Esquerdo</option>
                    <option value="Ambidestro">Ambidestro</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Idade *</label>
                <input type="number" name="idade" min="10" max="60" required placeholder="Ex.: 24">
            </div>
            <div class="form-group">
                <label>Altura (cm) *</label>
                <input type="number" name="altura_cm" min="140" max="230" required placeholder="Ex.: 178">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Peso (kg) *</label>
                <input type="number" name="peso_kg" min="40" max="150" required placeholder="Ex.: 74">
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    <option value="Ativo" selected>Ativo</option>
                    <option value="Suspenso">Suspenso</option>
                    <option value="Lesionado">Lesionado</option>
                    <option value="Inativo">Inativo</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Cadastrado por</label>
                <select name="cadastrado_por">
                    <option value="">-- Nenhum --</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u['id_usuarios'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Observações</label>
                <input type="text" name="obs" maxlength="100" placeholder="Ex.: Jogador veloz com bom drible.">
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:8px;">
            <button type="submit" class="btn btn-success">💾 Cadastrar Jogador</button>
            <a href="index.php" class="btn btn-primary">← Voltar</a>
        </div>
    </form>
</div>

<?php require 'footer.php'; ?>
