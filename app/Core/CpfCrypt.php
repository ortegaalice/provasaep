<?php
declare(strict_types=1);

namespace App\Core;

final class CpfCrypt
{
    private static function key(): string
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $decoded = base64_decode((string)$config['app_key'], true);
        if ($decoded === false || strlen($decoded) !== 32) {
            throw new \RuntimeException('APP_KEY inválida. Configure uma chave Base64 de 32 bytes no arquivo .env.');
        }
        return $decoded;
    }

    public static function encrypt(string $cpf): string
    {
        $iv = random_bytes(12);
        $tag = '';
        $cipher = openssl_encrypt(preg_replace('/\D/', '', $cpf), 'aes-256-gcm', self::key(), OPENSSL_RAW_DATA, $iv, $tag);
        if ($cipher === false) throw new \RuntimeException('Falha ao criptografar CPF.');
        return base64_encode($iv . $tag . $cipher);
    }

    public static function decrypt(string $payload): string
    {
        $raw = base64_decode($payload, true);
        if ($raw === false || strlen($raw) < 28) return '';
        $plain = openssl_decrypt(substr($raw, 28), 'aes-256-gcm', self::key(), OPENSSL_RAW_DATA, substr($raw, 0, 12), substr($raw, 12, 16));
        return $plain === false ? '' : $plain;
    }

    public static function mask(string $cpf): string
    {
        $digits = preg_replace('/\D/', '', $cpf);
        return strlen($digits) === 11 ? '***.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-**' : 'CPF cadastrado';
    }
}
