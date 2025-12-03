<?php
namespace App\services;

use PDO;

class DB {
    public static function conn() {
        return new PDO("mysql:host=mysql;dbname=sistema;charset=utf8", "root", "root");
    }
}
