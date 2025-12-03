<?php
namespace App\controllers;

use App\services\DB;

class DashboardController {
    public static function dados() {
        $pdo = DB::conn();
        $sql = $pdo->query("SELECT COUNT(*) total FROM vendas");
        return $sql->fetch();
    }
}
