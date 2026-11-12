<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class User
{
    public static function createIfMissing(): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email = ?');
        $stmt->execute(['admin@ortegaclinic.com']);
        if ((int)$stmt->fetchColumn() === 0) {
            $insert = $pdo->prepare('INSERT INTO usuarios (nome,email,senha_hash,perfil) VALUES (?,?,?,?)');
            $insert->execute(['Administrador', 'admin@ortegaclinic.com', password_hash('Admin123!', PASSWORD_DEFAULT), 'admin']);
        }
    }
}
