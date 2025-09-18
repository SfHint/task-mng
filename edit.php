<?php
require_once 'config.php';
$mysqli = db_connect();
$errors = [];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('Неверный ID задачи.');
}
// подгрузка
$stmt = $mysqli->prepare('SELECT id, title, description, status FROM tasks WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$task = $res->fetch_assoc(); 
$stmt->close();
if (!$task) {
    die('Задача не найдена.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = isset($_POST['status']) && $_POST['status'] === '1' ? 1 : 0;
    if ($title === '') {
        $errors[] = 'Заголовок обязателен.';
    } elseif (mb_strlen($title) > 255) {
        $errors[] = 'Заголовок не должен превышать 255 символов.';
    }
    if (empty($errors)) {
        $stmt = $mysqli->prepare('UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?');
        $stmt->bind_param('siii', $title, $description, $status, $id);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php');
        exit;
    }
} else {
    // POST form
    $_POST['title'] = $task['title'];
    $_POST['description'] = $task['description'];
    $_POST['status'] = $task['status'];
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Редактировать задачу</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h1>Редактировать задачу</h1>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <form method="post" novalidate>
    <div class="mb-3">
      <label class="form-label">Заголовок</label>
      <input name="title" class="form-control" required maxlength="255" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Описание</label>
      <textarea name="description" class="form-control" rows="6"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    </div>
    <div class="mb-3 form-check">
      <input type="checkbox" name="status" value="1" class="form-check-input" id="statusCheck" <?= (!empty($_POST['status']) && $_POST['status']==1) ? 'checked' : '' ?>>
      <label class="form-check-label" for="statusCheck">Выполнена</label>
    </div>
    <button class="btn btn-primary">Сохранить</button>
    <a href="index.php" class="btn btn-secondary">Отмена</a>
  </form>
</div>
</body>
</html>
