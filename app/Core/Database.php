<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $db = $config['db'];
            $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $db['host'], $db['port'], $db['name']);
            try {
                self::$connection = new PDO($dsn, $db['user'], $db['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                exit('Não foi possível conectar ao banco de dados. Confira as configurações do arquivo .env.');
            }
        }
        return self::$connection;
    }
}
