<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<div class="page-heading"><div><p class="eyebrow">AGENDA</p><h1>Novo agendamento</h1><p class="muted">Registre uma consulta ou serviço veterinário.</p></div><a class="btn btn-outline" href="/agendamentos">Voltar</a></div>
<section class="panel form-panel">
<?php if (!$tutors || !$pets): ?><p class="empty">É necessário cadastrar ao menos um tutor e um pet antes de agendar.</p><a class="btn btn-primary" href="/tutores/novo">Cadastrar tutor</a><?php else: ?>
<form method="post" action="/agendamentos/salvar" class="form-grid"><?= csrf_field() ?>
<label>Tutor<select name="tutor_id" id="tutor" required><option value="">Selecione</option><?php foreach ($tutors as $t): ?><option value="<?= (int)$t['id'] ?>"><?= e($t['nome']) ?></option><?php endforeach; ?></select></label>
<label>Pet<select name="pet_id" id="pet" required><option value="">Selecione o tutor primeiro</option><?php foreach ($pets as $p): ?><option value="<?= (int)$p['id'] ?>" data-tutor="<?= (int)$p['tutor_id'] ?>"><?= e($p['nome']) ?></option><?php endforeach; ?></select></label>
<label>Data<input type="date" name="data_agendamento" required min="<?= date('Y-m-d') ?>"></label>
<label>Horário<input type="time" name="hora_agendamento" required></label>
<label class="span-2">Serviço<input name="servico" required maxlength="120" placeholder="Ex.: consulta, vacinação, retorno"></label>
<label class="span-2">Observação<textarea name="observacao" rows="3"></textarea></label>
<div class="form-actions span-2"><button class="btn btn-primary">Salvar agendamento</button><a class="btn btn-outline" href="/agendamentos">Cancelar</a></div>
</form><?php endif; ?></section>
<script>
const tutor = document.getElementById('tutor');
const pet = document.getElementById('pet');
if (tutor && pet) {
  tutor.addEventListener('change', () => {
    [...pet.options].forEach((option, index) => {
      if (index === 0) return;
      option.hidden = option.dataset.tutor !== tutor.value;
    });
    pet.value = '';
  });
}
</script>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
