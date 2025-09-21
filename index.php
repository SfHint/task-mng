<?php
require_once 'config.php';
$mysqli = db_connect();

// Получаем записи
$stmt = $mysqli->prepare("SELECT id, title, amount, type, status, created_at FROM finances ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$records = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Считаем суммы
$total_income = 0.0;
$total_expense = 0.0;
foreach($records as $rec){
    if($rec['type'] === 'доход'){
        $total_income += (float)$rec['amount'];
    } else {
        $total_expense += (float)$rec['amount'];
    }
}
$balance = $total_income - $total_expense;
?>
<!doctype html>
<html lang="ru" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Finance Manager — Учёт личных финансов</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #0b0b0d; }
    .card.bg-secondary { background-color: #121214 !important; }
    .table-dark th, .table-dark td { border-color: rgba(255,255,255,0.06); }
    .brand { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; letter-spacing: 0.4px; }
  </style>
</head>
<body class="text-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div><h1 class="brand">Personal Finance</h1><div class="text-muted small">Учёт личных финансов</div></div>
    <div>
      <a href="add.php" class="btn btn-success">Добавить запись</a>
    </div>
  </div>

  <div class="card bg-secondary text-light mb-4">
    <div class="card-body d-flex gap-4 flex-wrap">
      <div>
        <div class="text-muted small">Доходы</div>
        <div class="h5 text-success fw-bold"><?= number_format($total_income, 2, '.', ' ') ?></div>
      </div>
      <div>
        <div class="text-muted small">Расходы</div>
        <div class="h5 text-danger fw-bold"><?= number_format($total_expense, 2, '.', ' ') ?></div>
      </div>
      <div>
        <div class="text-muted small">Баланс</div>
        <div class="h5 fw-bold <?= $balance >= 0 ? 'text-success' : 'text-danger' ?>"><?= number_format($balance, 2, '.', ' ') ?></div>
      </div>
      <div class="ms-auto text-muted small align-self-end">Всего записей: <?= count($records) ?></div>
    </div>
  </div>

  <div class="card bg-secondary text-light">
    <div class="card-body">
      <?php if (count($records) === 0): ?>
        <p class="text-muted">Записей пока нет. Добавьте первую запись.</p>
      <?php else: ?>
        <div class="table-responsive">
        <table class="table table-dark table-striped align-middle">
          <thead>
            <tr>
              <th>Название</th>
              <th>Сумма</th>
              <th>Тип</th>
              <th>Статус</th>
              <th>Дата</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($records as $rec): ?>
              <tr>
                <td><?= htmlspecialchars($rec['title']) ?></td>
                <td><?= htmlspecialchars(number_format($rec['amount'],2,'.',' ')) ?></td>
                <td><?= htmlspecialchars($rec['type']) ?></td>
                <td>
                  <?php if ($rec['status']): ?>
                    <span class="badge bg-success">Закрыта</span>
                  <?php else: ?>
                    <span class="badge bg-warning">Актуальная</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($rec['created_at']) ?></td>
                <td>
                  <a href="edit.php?id=<?= $rec['id'] ?>" class="btn btn-sm btn-outline-info">Редактировать</a>
                  <a href="delete.php?id=<?= $rec['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить запись?');">Удалить</a>
                  <?php if (!$rec['status']): ?>
                    <a href="update_status.php?id=<?= $rec['id'] ?>&action=close" class="btn btn-sm btn-success">Закрыть</a>
                  <?php else: ?>
                    <a href="update_status.php?id=<?= $rec['id'] ?>&action=open" class="btn btn-sm btn-warning">Открыть</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
