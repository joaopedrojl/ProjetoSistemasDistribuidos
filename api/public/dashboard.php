<?php
require __DIR__ . '/../config.php';


// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // redireciona para a página de login
    exit;
}

// Valores para filtro de unidade
$unidades = ['Salvador', 'Feira de Santana', 'Lauro de Freitas'];

// Recebe filtros via GET
$unidade = $_GET['unidade'] ?? 'Salvador';
$ano = $_GET['ano'] ?? date('Y');

// Consulta as vendas com filtro, limit 8
$sql = "SELECT vendedor, cliente, unidade, valor_venda, 
               DATE_FORMAT(data_venda, '%d/%m/%Y') AS data_venda
        FROM vendas
        WHERE unidade = :unidade
          AND YEAR(data_venda) = :ano
        ORDER BY data_venda DESC
        LIMIT 8";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'unidade' => $unidade,
    'ano' => $ano
]);

$vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedidos Online - Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f8f8f8; text-align: left; }
        select, button { padding: 6px; margin-right: 10px; }
        .info { background: #d9edf7; color: #31708f; padding: 10px; margin: 10px 0; border-radius: 4px; }
        .btn-green { background: #28a745; color: white; border: none; padding: 10px 15px; cursor: pointer; }
        .btn-green:hover { background: #218838; }
    </style>
</head>
<body>

<header>
    <div>
        <strong>Pedidos OnLine</strong>
    </div>
    <div>
        <?= htmlspecialchars($_SESSION['user_nome']) ?> |
        <a href="logout.php">Logout</a>
    </div>
</header>

<h3>Filtros</h3>
<form method="GET" action="dashboard.php">
    Unidade:
    <select name="unidade">
        <?php foreach ($unidades as $u): ?>
            <option value="<?= $u ?>" <?= ($u === $unidade) ? 'selected' : '' ?>><?= $u ?></option>
        <?php endforeach; ?>
    </select>
    Ano:
    <select name="ano">
        <?php 
        $anoAtual = (int)date('Y');
        for ($i = $anoAtual - 1; $i <= $anoAtual + 1; $i++): ?>
            <option value="<?= $i ?>" <?= ($i == $ano) ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
    <button type="submit">Buscar</button>
</form>

<div class="info">
    Esta listagem mostra apenas uma quantidade parcial dos dados disponíveis.
</div>

<table>
    <thead>
        <tr>
            <th>Vendedor</th>
            <th>Cliente</th>
            <th>Unidade</th>
            <th>Valor da Venda</th>
            <th>Data de Venda</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!$vendas): ?>
            <tr><td colspan="5">Nenhum registro encontrado.</td></tr>
        <?php else: ?>
            <?php foreach ($vendas as $v): ?>
                <tr>
                    <td><?= htmlspecialchars($v['vendedor']) ?></td>
                    <td><?= htmlspecialchars($v['cliente']) ?></td>
                    <td><?= htmlspecialchars($v['unidade']) ?></td>
                    <td>R$ <?= number_format($v['valor_venda'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($v['data_venda']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<form method="POST" action="producer.php" style="margin-top: 20px;">
    <input type="hidden" name="unidade" value="<?= htmlspecialchars($unidade) ?>" />
    <input type="hidden" name="ano" value="<?= htmlspecialchars($ano) ?>" />
    <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['user_email']) ?>" />
    <button type="submit" class="btn-green">Gerar XLS & Enviar por e-mail</button>
</form>

</body>
</html>
