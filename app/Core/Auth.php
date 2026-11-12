<?php
declare(strict_types=1);

namespace App\Core;

final class Auth
{
    private const TIMEOUT = 900; // 15 minutos

    public static function checkInactivity(): void
    {
        if (isset($_SESSION['user'])) {
            if (time() - (int)($_SESSION['last_activity'] ?? time()) > self::TIMEOUT) {
                self::logout();
                flash('error', 'Sua sessão expirou por inatividade. Faça login novamente.');
                return;
            }
            $_SESSION['last_activity'] = time();
        }
    }

    public static function user(): ?array { return $_SESSION['user'] ?? null; }
    public static function check(): bool { return self::user() !== null; }

    public static function attempt(string $email, string $password): bool
    {
        $stmt = Database::connection()->prepare('SELECT id, nome, email, senha_hash, perfil FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => mb_strtolower(trim($email))]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['senha_hash'])) return false;

        session_regenerate_id(true);
        unset($user['senha_hash']);
        $_SESSION['user'] = $user;
        $_SESSION['last_activity'] = time();
        return true;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            flash('error', 'Entre com seu e-mail e senha para continuar.');
            redirect('login');
        }
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
