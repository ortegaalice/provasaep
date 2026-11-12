<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Models\User;

final class AuthController
{
    public function login(): void
    {
        if (Auth::check()) redirect('dashboard');
        view('auth/login', ['title'=>'Entrar']);
    }

    public function authenticate(): void
    {
        verify_csrf();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (Auth::attempt($email, $password)) redirect('dashboard');
        flash('error', 'E-mail ou senha incorretos.');
        redirect('login');
    }

    public function logout(): void
    {
        verify_csrf();
        Auth::logout();
        redirect('login');
    }
}
