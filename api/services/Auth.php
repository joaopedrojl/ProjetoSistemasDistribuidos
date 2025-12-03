<?php
namespace App\services;

class Auth {

    public static function check() {
        session_start();
        if (!isset($_SESSION["user"])) {
            header("Location: login.php");
            exit;
        }
    }

    public static function login($user) {
        session_start();
        $_SESSION["user"] = $user;
    }

    public static function logout() {
        session_start();
        session_destroy();
    }
}
