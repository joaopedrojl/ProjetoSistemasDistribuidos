<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$unidade = $_POST['unidade'] ?? '';
$ano = $_POST['ano'] ?? date('Y');
$email = $_POST['email'] ?? '';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('relatorios', false, true, false, false);

$data = json_encode([
    'unidade' => $unidade,
    'ano' => $ano,
    'email' => $email
]);

$msg = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

$channel->basic_publish($msg, '', 'relatorios');

$channel->close();
$connection->close();

echo "Mensagem enviada para a fila com sucesso!";
