<?php
namespace App\controllers;

use App\services\RabbitMQService;

class RelatorioController {

    public static function gerar() {
        $payload = [
            "unidade" => $_POST["unidade"],
            "ano" => $_POST["ano"],
            "email" => $_POST["email"]
        ];

        RabbitMQService::enviarFila($payload);

        header("Location: relatorio.php?ok=1");
        exit;
    }
}
