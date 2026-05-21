<!-- teste -->

<?php
session_start();
require 'conecta.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if ($nome && $email && $senha) {
        if ($senha !== $confirmar_senha) {
            $erro = 'As senhas não coincidem.';
        } else {
            $stmt = $pdo->prepare("SELECT id_usuarios FROM USUARIOS WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $erro = 'Este e-mail já está cadastrado.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO USUARIOS (nome, email, senha, nivel) VALUES (?, ?, ?, 'usuario')");
                $stmt->execute([$nome, $email, $senha_hash]);
                try {
                    $stmt->execute([$nome, $email, $senha_hash]);
                    header("Location: login.php?msg=cadastrado");
                    exit;
                } catch (PDOException $e) {
                    $erro = 'Erro ao cadastrar: ' . $e->getMessage();
                }
            }
        }
    } else {
        $erro = 'Preencha todos os campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - BID</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { color: #1a3a5c; text-align: center; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        input { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 0.75rem; background: #1a8a5a; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 1rem; }
        button:hover { background: #146c43; }
        .error { color: #c0392b; background: #fadbd8; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; text-align: center; }
        .success { color: #1d6a3a; background: #d4efdf; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; text-align: center; }
        .footer-links { text-align: center; margin-top: 1rem; font-size: 0.9rem; }
        .footer-links a { color: #1a3a5c; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Criar Conta BID</h2>
        <?php if ($erro): ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="success"><?= $sucesso ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" name="nome" required autofocus>
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" required>
            </div>
            <div class="form-group">
                <label>Confirmar Senha</label>
                <input type="password" name="confirmar_senha" required>
            </div>
            <button type="submit">Cadastrar</button>
        </form>
        <div class="footer-links">
            Já tem uma conta? <a href="login.php">Entre aqui</a>
        </div>
    </div>
</body>
</html>
