<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\CpfCrypt;

final class Tutor
{
    public static function all(string $search = ''): array
    {
        $sql = 'SELECT id,nome,cpf_criptografado,telefone,email,criado_em FROM tutores';
        $params = [];
        if ($search !== '') {
            $sql .= ' WHERE nome ILIKE :q OR telefone ILIKE :q OR email ILIKE :q';
            $params['q'] = '%' . $search . '%';
        }
        $sql .= ' ORDER BY nome';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) $row['cpf_mascarado'] = CpfCrypt::mask(CpfCrypt::decrypt($row['cpf_criptografado']));
        return $rows;
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM tutores WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        $row['cpf'] = CpfCrypt::decrypt($row['cpf_criptografado']);
        return $row;
    }

    public static function save(array $data, ?int $id = null): void
    {
        $cpf = preg_replace('/\D/', '', $data['cpf']);
        if (strlen($cpf) !== 11) throw new \InvalidArgumentException('Informe um CPF com 11 números.');
        $encrypted = CpfCrypt::encrypt($cpf);
        $pdo = Database::connection();
        if ($id) {
            $stmt = $pdo->prepare('UPDATE tutores SET nome=:nome,cpf_criptografado=:cpf,telefone=:telefone,email=:email WHERE id=:id');
            $stmt->execute(['nome'=>$data['nome'],'cpf'=>$encrypted,'telefone'=>$data['telefone'],'email'=>$data['email'],'id'=>$id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO tutores (nome,cpf_criptografado,telefone,email) VALUES (:nome,:cpf,:telefone,:email)');
            $stmt->execute(['nome'=>$data['nome'],'cpf'=>$encrypted,'telefone'=>$data['telefone'],'email'=>$data['email']]);
        }
    }
}
