<?php
class Auth {
    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function user() {
        return $_SESSION['user'] ?? null;
    }

    public static function login($user) {
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
    }

    public static function logout() {
        unset($_SESSION['user']);
    }

    public static function isRole($role) {
        return self::check() && self::user()['role'] === $role;
    }
}
