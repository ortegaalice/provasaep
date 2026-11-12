<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Tutor;

final class TutorController
{
    public function index(): void
    {
        Auth::requireLogin();
        view('tutors/index', ['title'=>'Tutores','tutors'=>Tutor::all(trim($_GET['q'] ?? '')),'query'=>trim($_GET['q'] ?? '')]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        view('tutors/form', ['title'=>'Novo tutor','tutor'=>null]);
    }

    public function store(): void
    {
        Auth::requireLogin(); verify_csrf();
        try {
            $this->validate($_POST);
            Tutor::save($_POST);
            flash('success','Tutor cadastrado com sucesso.');
        } catch (\Throwable $e) {
            flash('error',$e instanceof \InvalidArgumentException ? $e->getMessage() : 'Não foi possível cadastrar o tutor. Verifique os dados.');
            redirect('tutores/novo');
        }
        redirect('tutores');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $tutor = Tutor::find((int)($_GET['id'] ?? 0));
        if (!$tutor) { flash('error','Tutor não encontrado.'); redirect('tutores'); }
        view('tutors/form', ['title'=>'Editar tutor','tutor'=>$tutor]);
    }

    public function update(): void
    {
        Auth::requireLogin(); verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->validate($_POST);
            Tutor::save($_POST, $id);
            flash('success','Dados do tutor atualizados.');
        } catch (\Throwable $e) {
            flash('error',$e instanceof \InvalidArgumentException ? $e->getMessage() : 'Não foi possível atualizar o tutor.');
            redirect('tutores/editar?id='.$id);
        }
        redirect('tutores');
    }

    private function validate(array $data): void
    {
        if (trim($data['nome'] ?? '') === '' || trim($data['telefone'] ?? '') === '') throw new \InvalidArgumentException('Preencha nome e telefone.');
        if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) throw new \InvalidArgumentException('Informe um e-mail válido.');
        if (strlen(preg_replace('/\D/','',$data['cpf'] ?? '')) !== 11) throw new \InvalidArgumentException('Informe um CPF com 11 números.');
    }
}
