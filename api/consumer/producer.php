<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Conexão com o RabbitMQ
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

// Declarar a fila
$channel->queue_declare('test_queue', false, false, false, false);

// Mensagem
$data = "Hello World! " . date('Y-m-d H:i:s');
$msg = new AMQPMessage($data);

// Enviar
$channel->basic_publish($msg, '', 'test_queue');

echo " [x] Sent '$data'\n";

// Fechar conexão
$channel->close();
$connection->close();
