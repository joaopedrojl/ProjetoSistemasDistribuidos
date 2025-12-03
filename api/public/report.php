<?php
require_once __DIR__ . '/../consumer/vendor/autoload.php'; // Aponta para consumer/vendor

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$unidade = $_POST['unidade'] ?? null;
$ano = $_POST['ano'] ?? null;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('relatorios_queue', false, false, false, false);

$data = ['unidade' => $unidade, 'ano' => $ano];
$msg = new AMQPMessage(json_encode($data));
$channel->basic_publish($msg, '', 'relatorios_queue');

echo "Relatório enviado para processamento!";
$channel->close();
$connection->close();
