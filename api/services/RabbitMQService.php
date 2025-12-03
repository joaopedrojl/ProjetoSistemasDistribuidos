<?php
namespace App\services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService {

    public static function enviarFila($dados) {
        $conn = new AMQPStreamConnection("rabbitmq", 5672, "guest", "guest");
        $channel = $conn->channel();

        $channel->queue_declare("gerar_xls", false, true, false, false);

        $msg = new AMQPMessage(json_encode($dados));

        $channel->basic_publish($msg, "", "gerar_xls");

        $channel->close();
        $conn->close();
    }
}
