<?php
require_once 'config.php';
$mysqli = db_connect();

$stmt = $mysqli->prepare("SELECT id, title, amount, type, status, created_at FROM finances ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$records = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Finance Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Учёт личных финансов</h1>
    <a href="add.php" class="btn btn-primary">Добавить запись</a>
  </div>

  <div class="card">
    <div class="card-body">
      <?php if (count($records) === 0): ?>
        <p class="text-muted">Записей пока нет. Добавьте первую запись.</p>
      <?php else: ?>
        <div class="table-responsive">
        <table class="table table-striped align-middle">
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
                <td><?= htmlspecialchars($rec['amount']) ?></td>
                <td><?= htmlspecialchars($rec['type']) ?></td>
                <td>
                  <?php if ($rec['status']): ?>
                    <span class="badge bg-success">Закрыта</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Актуальная</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($rec['created_at']) ?></td>
                <td>
                  <a href="edit.php?id=<?= $rec['id'] ?>" class="btn btn-sm btn-outline-primary">Редактировать</a>
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
