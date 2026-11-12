<?php
declare(strict_types=1);

function envValue(string $key, mixed $default = null): mixed
{
    static $env = null;
    if ($env === null) {
        $env = [];
        $path = dirname(__DIR__) . '/.env';
        if (is_file($path)) {
            foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
                [$name, $value] = explode('=', $line, 2);
                $env[trim($name)] = trim(trim($value), "\"'");
            }
        }
    }
    return $env[$key] ?? getenv($key) ?: $default;
}

return [
    'name' => envValue('APP_NAME', 'Ortega Clinic'),
    'timezone' => 'America/Sao_Paulo',
    'db' => [
        'host' => envValue('DB_HOST', '127.0.0.1'),
        'port' => envValue('DB_PORT', '5432'),
        'name' => envValue('DB_NAME', 'ortega_clinic'),
        'user' => envValue('DB_USER', 'postgres'),
        'pass' => envValue('DB_PASS', 'postgres'),
    ],
    'app_key' => envValue('APP_KEY', ''),
];
