<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Models\Pet;
use App\Models\Tutor;

final class PetController
{
    public function index(): void
    {
        Auth::requireLogin();
        view('pets/index', ['title'=>'Pets','pets'=>Pet::all()]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $tutors = Database::connection()->query('SELECT id,nome FROM tutores ORDER BY nome')->fetchAll();
        view('pets/form', ['title'=>'Cadastrar pet','tutors'=>$tutors]);
    }

    public function store(): void
    {
        Auth::requireLogin(); verify_csrf();
        if (trim($_POST['nome'] ?? '') === '' || trim($_POST['especie'] ?? '') === '' || (int)($_POST['tutor_id'] ?? 0) < 1) {
            flash('error','Informe nome, espécie e tutor.'); redirect('pets/novo');
        }
        try { Pet::save($_POST); flash('success','Pet cadastrado com sucesso.'); }
        catch (\Throwable $e) { flash('error','Não foi possível cadastrar o pet. Confira o tutor selecionado.'); }
        redirect('pets');
    }
}
