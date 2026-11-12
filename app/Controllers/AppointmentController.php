<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Models\Appointment;

final class AppointmentController
{
    public function index(): void
    {
        Auth::requireLogin();
        view('appointments/index', ['title'=>'Agendamentos','appointments'=>Appointment::all()]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $pdo = Database::connection();
        view('appointments/form', [
            'title'=>'Novo agendamento',
            'tutors'=>$pdo->query('SELECT id,nome FROM tutores ORDER BY nome')->fetchAll(),
            'pets'=>$pdo->query('SELECT id,nome,tutor_id FROM pets ORDER BY nome')->fetchAll()
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin(); verify_csrf();
        foreach (['tutor_id','pet_id','data_agendamento','hora_agendamento','servico'] as $field) {
            if (trim($_POST[$field] ?? '') === '') { flash('error','Preencha todos os campos obrigatórios.'); redirect('agendamentos/novo'); }
        }
        try {
            Appointment::save($_POST, (int)Auth::user()['id']);
            flash('success','Agendamento criado com sucesso.');
        } catch (\Throwable $e) { flash('error','Não foi possível criar o agendamento. Verifique se o pet pertence ao tutor.'); }
        redirect('agendamentos');
    }

    public function updateDate(): void
    {
        Auth::requireLogin(); verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $date = $_POST['data_agendamento'] ?? '';
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format('Y-m-d') !== $date) { flash('error','Informe uma data válida.'); redirect('agendamentos'); }
        Appointment::updateDate($id, $date);
        flash('success','Data do agendamento atualizada.');
        redirect('agendamentos');
    }
}
