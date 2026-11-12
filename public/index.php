<?php
declare(strict_types=1);

use App\Core\Router;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/app.php';
date_default_timezone_set($config['timezone']);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('ortega_clinic_session');
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
        'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
}

require dirname(__DIR__) . '/app/Core/helpers.php';

\App\Core\Auth::checkInactivity();

$router = new Router();
require dirname(__DIR__) . '/routes/web.php';
$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
