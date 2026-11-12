<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Pet
{
    public static function all(): array
    {
        return Database::connection()->query('SELECT p.*,t.nome AS tutor_nome FROM pets p JOIN tutores t ON t.id=p.tutor_id ORDER BY p.nome')->fetchAll();
    }

    public static function save(array $data): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO pets (tutor_id,nome,especie,raca,data_nascimento) VALUES (:tutor_id,:nome,:especie,:raca,:data_nascimento)');
        $stmt->execute([
            'tutor_id'=>(int)$data['tutor_id'], 'nome'=>$data['nome'], 'especie'=>$data['especie'],
            'raca'=>$data['raca'] ?: null, 'data_nascimento'=>$data['data_nascimento'] ?: null
        ]);
    }
}
