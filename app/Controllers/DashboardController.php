<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;

final class DashboardController
{
    public function index(): void
    {
        Auth::requireLogin();
        $pdo = Database::connection();
        $counts = [];
        foreach (['tutores','pets','agendamentos'] as $table) $counts[$table] = (int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
        $upcoming = $pdo->query("SELECT a.data_agendamento,a.hora_agendamento,p.nome AS pet_nome,t.nome AS tutor_nome
            FROM agendamentos a JOIN pets p ON p.id=a.pet_id JOIN tutores t ON t.id=a.tutor_id
            WHERE a.data_agendamento >= CURRENT_DATE ORDER BY a.data_agendamento,a.hora_agendamento LIMIT 5")->fetchAll();
        view('dashboard/index', ['title'=>'Painel principal','counts'=>$counts,'upcoming'=>$upcoming]);
    }
}
