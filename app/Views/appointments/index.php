<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<div class="page-heading"><div><p class="eyebrow">AGENDA</p><h1>Agendamentos</h1><p class="muted">Consultas e serviços organizados por data.</p></div><a class="btn btn-primary" href="/agendamentos/novo">+ Novo agendamento</a></div>
<section class="panel"><div class="table-wrap"><table><thead><tr><th>Data</th><th>Horário</th><th>Pet</th><th>Tutor</th><th>Serviço</th><th>Status</th><th>Alterar data</th></tr></thead><tbody>
<?php foreach ($appointments as $a): ?><tr><td><?= date('d/m/Y',strtotime($a['data_agendamento'])) ?></td><td><?= substr($a['hora_agendamento'],0,5) ?></td><td><?= e($a['pet_nome']) ?></td><td><?= e($a['tutor_nome']) ?></td><td><?= e($a['servico']) ?></td><td><span class="status"><?= e($a['status']) ?></span></td><td><form method="post" action="/agendamentos/atualizar-data" class="inline-form"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$a['id'] ?>"><input type="date" name="data_agendamento" value="<?= e($a['data_agendamento']) ?>" required><button class="btn btn-small btn-dark">Salvar</button></form></td></tr><?php endforeach; ?>
<?php if (!$appointments): ?><tr><td colspan="7" class="empty">Nenhum agendamento cadastrado.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
