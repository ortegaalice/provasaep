<?php
declare(strict_types=1);

use App\Core\Csrf;

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    return rtrim((string) (require dirname(__DIR__, 2) . '/config/app.php')['name'], '/') === ''
        ? '/' . ltrim($path, '/') : '/' . ltrim($path, '/');
}

function view(string $name, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require dirname(__DIR__) . '/Views/' . $name . '.php';
}

function redirect(string $path): never
{
    header('Location: /' . ltrim($path, '/'));
    exit;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(Csrf::token()) . '">';
}

function verify_csrf(): void
{
    if (!Csrf::verify($_POST['_token'] ?? null)) {
        http_response_code(419);
        exit('Solicitação expirada ou inválida. Volte e tente novamente.');
    }
}
