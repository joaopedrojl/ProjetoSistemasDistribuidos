<?php
namespace App\controllers;

use App\services\Auth;
use App\services\DB;

class AuthController {

    public static function login() {

        $email = $_POST["email"];
        $senha = $_POST["senha"];

        $pdo = DB::conn();
        $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $sql->execute([$email]);

        $user = $sql->fetch();

        if (!$user || $user["senha"] !== $senha) {
            header("Location: login.php?erro=1");
            exit;
        }

        Auth::login($user);
        header("Location: dashboard.php");
    }
}
