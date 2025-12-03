<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php'; // Conexão com DB e configs gerais

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Configurações do RabbitMQ
$host = 'rabbitmq';
$port = 5672;
$user = 'guest';
$pass = 'guest';
$queue = 'relatorios';

echo "Consumer iniciado, aguardando mensagens...\n";

// Conexão com o RabbitMQ
$connection = new AMQPStreamConnection($host, $port, $user, $pass);
$channel = $connection->channel();

// Declara a fila (cria se não existir)
$channel->queue_declare($queue, false, true, false, false);

// Callback que processa cada mensagem
$callback = function (AMQPMessage $msg) {
    echo "Mensagem recebida: ", $msg->body, "\n";

    $data = json_decode($msg->body, true);

    if (!$data) {
        echo "Erro: mensagem inválida\n";
        return;
    }

    $unidade = $data['unidade'] ?? 'Salvador';
    $ano = $data['ano'] ?? date('Y');
    $email = $data['email'] ?? null;

    if (!$email) {
        echo "Erro: email não informado\n";
        return;
    }

    // Aqui você pode gerar o relatório XLS como no producer
    $arquivo = __DIR__ . "/relatorio_{$unidade}_{$ano}.xlsx";

    // Exemplo simples de geração de XLS com PhpSpreadsheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Vendedor');
    $sheet->setCellValue('B1', 'Cliente');
    $sheet->setCellValue('C1', 'Unidade');
    $sheet->setCellValue('D1', 'Valor');
    $sheet->setCellValue('E1', 'Data');

    // Conecta no banco para buscar as vendas
    global $pdo;
    $sql = "SELECT vendedor, cliente, unidade, valor_venda, DATE_FORMAT(data_venda, '%d/%m/%Y') as data_venda
            FROM vendas 
            WHERE unidade = :unidade 
              AND YEAR(data_venda) = :ano
            ORDER BY data_venda DESC
            LIMIT 100"; // limitar 100 registros
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['unidade' => $unidade, 'ano' => $ano]);
    $vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $row = 2;
    foreach ($vendas as $venda) {
        $sheet->setCellValue("A$row", $venda['vendedor']);
        $sheet->setCellValue("B$row", $venda['cliente']);
        $sheet->setCellValue("C$row", $venda['unidade']);
        $sheet->setCellValue("D$row", $venda['valor_venda']);
        $sheet->setCellValue("E$row", $venda['data_venda']);
        $row++;
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($arquivo);

    echo "Relatório gerado: $arquivo\n";

    // Aqui você pode enviar por email usando PHPMailer, ou apenas simular
    echo "Simulando envio para $email...\n";

    // Confirma que a mensagem foi processada
    $msg->ack();
};

// Escuta a fila
$channel->basic_qos(null, 1, null); // uma mensagem por vez
$channel->basic_consume($queue, '', false, false, false, false, $callback);

// Loop para manter o consumer rodando
while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
