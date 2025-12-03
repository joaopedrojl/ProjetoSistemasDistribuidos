<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Dados da requisição via sessão para exibir
$unidade = $_SESSION['process_unidade'] ?? '';
$ano = $_SESSION['process_ano'] ?? '';
$email = $_SESSION['process_email'] ?? '';
$solicitado_em = $_SESSION['process_solicitado_em'] ?? '';

if (!$unidade || !$ano || !$email) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Relatório em Processamento</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; max-width: 600px; }
        h1 { color: #333; }
        .box { border: 1px solid #ddd; padding: 20px; border-radius: 6px; background: #f9f9f9; }
        .alert-info { background: #d9edf7; color: #31708f; padding: 10px; margin-top: 15px; border-radius: 4px; }
        button { margin-top: 20px; padding: 10px 15px; background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>

<h1>Relatório em Processamento</h1>
<p>Seu relatório está sendo gerado. Assim que estiver pronto, você receberá o arquivo XLS por e-mail.</p>

<div class="box">
    <h3>Detalhes da Solicitação:</h3>
    <p><strong>Unidade:</strong> <?=htmlspecialchars($unidade)?></p>
    <p><strong>Ano:</strong> <?=htmlspecialchars($ano)?></p>
    <p><strong>Solicitado em:</strong> <?=htmlspecialchars($solicitado_em)?></p>
    <p><strong>Será enviado para:</strong> <?=htmlspecialchars($email)?></p>
</div>

<div class="alert-info">
    Atenção: O processamento pode levar alguns minutos dependendo da quantidade de dados. Você receberá uma notificação por e-mail quando o relatório estiver disponível.
</div>

<form action="dashboard.php" method="GET">
    <button type="submit">Solicitar Novo Relatório</button>
</form>

</body>
</html>
