<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<section class="login-card">
    <div class="brand-mark">OC</div>
    <p class="eyebrow">GESTÃO VETERINÁRIA</p>
    <h1>Bem-vindo ao Ortega Clinic</h1>
    <p class="muted">Entre para acessar o painel da clínica.</p>
    <form method="post" action="/login" class="form-stack">
        <?= csrf_field() ?>
        <label>E-mail
            <input type="email" name="email" required autocomplete="username" placeholder="seu@email.com">
        </label>
        <label>Senha
            <input type="password" name="password" required autocomplete="current-password" placeholder="Sua senha">
        </label>
        <button class="btn btn-primary full" type="submit">Entrar no sistema</button>
    </form>
    <p class="login-help">Acesso inicial: <strong>admin@ortegaclinic.com</strong><br>Senha: <strong>Admin123!</strong></p>
</section>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
