<?php use App\Core\Auth; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Ortega Clinic') ?> | Ortega Clinic</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<?php if (Auth::check()): ?>
<header class="topbar">
    <a class="brand" href="/dashboard">Ortega <span>Clinic</span></a>
    <nav>
        <a href="/dashboard">Início</a>
        <a href="/tutores">Tutores</a>
        <a href="/pets">Pets</a>
        <a href="/agendamentos">Agendamentos</a>
    </nav>
    <div class="user-area">
        <span><?= e(Auth::user()['nome']) ?></span>
        <form method="post" action="/logout"><?= csrf_field() ?><button class="btn btn-outline" type="submit">Sair</button></form>
    </div>
</header>
<?php endif; ?>
<main class="<?= Auth::check() ? 'container' : 'auth-container' ?>">
<?php if ($message = flash('success')): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?>
<?php if ($message = flash('error')): ?><div class="alert error"><?= e($message) ?></div><?php endif; ?>
