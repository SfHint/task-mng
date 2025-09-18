<?php
require_once 'config.php'; 
$mysqli = db_connect();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if ($title === '') {
        $errors[] = 'Заголовок обязателен.';
    } elseif (mb_strlen($title) > 255) {
        $errors[] = 'Заголовок не должен превышать 255 символов.';
    }
    if (empty($errors)) {
        $stmt = $mysqli->prepare('INSERT INTO tasks (title, description) VALUES (?, ?)');
        $stmt->bind_param('ss', $title, $description);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Добавить задачу</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h1>Добавить задачу</h1>
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
    <button class="btn btn-primary">Добавить</button>
    <a href="index.php" class="btn btn-secondary">Отмена</a>
  </form>
</div>
</body>
</html>
