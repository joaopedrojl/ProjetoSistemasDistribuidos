<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';




$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $maxRetries = 10;
$retry = 0;
while ($retry < $maxRetries) {
    try {
        $pdo = new PDO(
            'mysql:host=mysql;dbname=pedidos_online_db;charset=utf8mb4',
            'root',
            'root123',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        break; // conectado com sucesso
    } catch (PDOException $e) {
        $retry++;
        echo "Tentativa $retry falhou. Tentando novamente em 3 segundos...<br>";
        sleep(3);
    }
}
if (!$pdo) {
    die("Não foi possível conectar ao MySQL após $maxRetries tentativas.");
}


    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = SHA1(:senha) LIMIT 1");
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senha);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['usuario'] = $usuario;
        header('Location: dashboard.php');
        exit;
    } else {
        $erro = 'E-mail ou senha inválidos!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
<form method="post">
    <label>E-mail: <input type="email" name="email" required></label><br><br>
    <label>Senha: <input type="password" name="senha" required></label><br><br>
    <button type="submit">Entrar</button>
</form>
<p style="color:red;"><?php echo $erro; ?></p>
</body>
</html>
