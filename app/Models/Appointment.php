<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Appointment
{
    public static function all(): array
    {
        $sql = 'SELECT a.*,t.nome AS tutor_nome,p.nome AS pet_nome,u.nome AS usuario_nome
                FROM agendamentos a JOIN tutores t ON t.id=a.tutor_id
                JOIN pets p ON p.id=a.pet_id JOIN usuarios u ON u.id=a.usuario_id
                ORDER BY a.data_agendamento,a.hora_agendamento';
        return Database::connection()->query($sql)->fetchAll();
    }

    public static function save(array $data, int $userId): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO agendamentos (tutor_id,pet_id,usuario_id,data_agendamento,hora_agendamento,servico,status,observacao) VALUES (:tutor_id,:pet_id,:usuario_id,:data,:hora,:servico,:status,:observacao)');
        $stmt->execute([
            'tutor_id'=>(int)$data['tutor_id'],'pet_id'=>(int)$data['pet_id'],'usuario_id'=>$userId,
            'data'=>$data['data_agendamento'],'hora'=>$data['hora_agendamento'],'servico'=>$data['servico'],
            'status'=>'Agendado','observacao'=>$data['observacao'] ?: null
        ]);
    }

    public static function updateDate(int $id, string $date): void
    {
        $stmt = Database::connection()->prepare('UPDATE agendamentos SET data_agendamento=:data WHERE id=:id');
        $stmt->execute(['data'=>$date,'id'=>$id]);
    }
}
