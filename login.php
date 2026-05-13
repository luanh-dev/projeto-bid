<?php
session_start();
require 'conecta.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';
$sucesso = $_GET['msg'] ?? '';
if ($sucesso === 'cadastrado') {
    $sucesso = '✅ Conta criada com sucesso! Faça seu login.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email && $senha) {
        $stmt = $pdo->prepare("SELECT * FROM USUARIOS WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuarios'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_nivel'] = $usuario['nivel'];
            header('Location: index.php');
            exit;
        } else {
            // Verificação temporária para senhas não hasheadas (compatibilidade com o SQL original)
            if ($usuario && $senha === $usuario['senha']) {
                $_SESSION['usuario_id'] = $usuario['id_usuarios'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_nivel'] = $usuario['nivel'];
                header('Location: index.php');
                exit;
            }
            $erro = 'E-mail ou senha inválidos.';
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
    <title>Login - BID</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { color: #1a3a5c; text-align: center; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        input { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 0.75rem; background: #1a3a5c; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 1rem; }
        button:hover { background: #245a8a; }
        .error { color: #c0392b; background: #fadbd8; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; text-align: center; }
        .success { color: #1d6a3a; background: #d4efdf; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; text-align: center; }
        .footer-links { text-align: center; margin-top: 1rem; font-size: 0.9rem; }
        .footer-links a { color: #1a3a5c; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Acesso ao BID</h2>
        <?php if ($erro): ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="success"><?= $sucesso ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" required autofocus>
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
        <div class="footer-links">
            Não tem uma conta? <a href="cadastro.php">Cadastre-se</a>
        </div>
    </div>
</body>
</html>
