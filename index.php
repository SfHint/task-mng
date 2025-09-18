<?php
require_once 'config.php';
$mysqli = db_connect();

// подтягивание заданий
$stmt = $mysqli->prepare("SELECT id, title, description, status, created_at FROM tasks ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!doctype html>
<html lang="ru"> 
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Task Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Task Manager</h1>
    <a href="add.php" class="btn btn-primary">Добавить задачу</a>
  </div>

  <div class="card">
    <div class="card-body">
      <?php if (count($tasks) === 0): ?>
        <p class="text-muted">Задач пока нет. Добавьте первую задачу.</p>
      <?php else: ?>
        <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>Заголовок</th>
              <th>Описание</th>
              <th>Статус</th>
              <th>Создано</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($tasks as $task): ?>
              <tr>
                <td><?= htmlspecialchars($task['title']) ?></td>
                <td><?= nl2br(htmlspecialchars($task['description'])) ?></td>
                <td>
                  <?php if ($task['status']): ?>
                    <span class="badge bg-success">Выполнена</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Не выполнена</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($task['created_at']) ?></td>
                <td>
                  <a href="edit.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-primary">Редактировать</a>
                  <a href="delete.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить задачу?');">Удалить</a>
                  <?php if (!$task['status']): ?>
                    <a href="update_status.php?id=<?= $task['id'] ?>&action=done" class="btn btn-sm btn-success">Отметить выполненной</a>
                  <?php else: ?>
                    <a href="update_status.php?id=<?= $task['id'] ?>&action=undone" class="btn btn-sm btn-warning">Отметить невыполненной</a>
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
