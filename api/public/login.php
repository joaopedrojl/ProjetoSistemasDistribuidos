<?php
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($email && $senha) {
        $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = SHA1(:senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'senha' => $senha]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: dashboard.php');
            exit;
        } else {
            $erro = "Email ou senha inválidos.";
        }
    } else {
        $erro = "Preencha email e senha.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login - Pedidos Online</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; }
        .container { max-width: 300px; margin: 100px auto; background: white; padding: 20px; border-radius: 5px; }
        input { width: 100%; padding: 8px; margin: 6px 0; }
        button { width: 100%; padding: 8px; background: #28a745; color: white; border: none; cursor: pointer; }
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if ($erro): ?>
        <p class="error"><?=htmlspecialchars($erro)?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required autofocus />
        <input type="password" name="senha" placeholder="Senha" required />
        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html>

