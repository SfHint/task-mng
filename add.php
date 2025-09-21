<?php
require_once 'config.php';
$mysqli = db_connect();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $type = $_POST['type'] ?? '';
    if ($title === '') $errors[] = 'Название обязательно.';
    if ($amount === '' || !is_numeric($amount)) $errors[] = 'Введите корректную сумму.';
    if ($type !== 'доход' && $type !== 'расход') $errors[] = 'Укажите тип.';
    if (empty($errors)) {
        $stmt = $mysqli->prepare('INSERT INTO finances (title, amount, type) VALUES (?, ?, ?)');
        $stmt->bind_param('sds', $title, $amount, $type);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="ru" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Добавить запись — Finance Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #0b0b0d; color: #e6edf3; }
    .card.bg-secondary { background-color: #121214 !important; }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Добавить запись</h2>
    <a href="index.php" class="btn btn-outline-light">Назад</a>
  </div>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
  <?php endif; ?>
  <div class="card bg-secondary text-light">
    <div class="card-body">
      <form method="post" novalidate>
        <div class="mb-3">
          <label class="form-label">Название</label>
          <input name="title" class="form-control" maxlength="255" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Сумма</label>
          <input name="amount" type="number" step="0.01" class="form-control" value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Тип</label>
          <select name="type" class="form-select">
            <option value="">Выберите...</option>
            <option value="доход" <?= (($_POST['type'] ?? '') === 'доход') ? 'selected' : '' ?>>Доход</option>
            <option value="расход" <?= (($_POST['type'] ?? '') === 'расход') ? 'selected' : '' ?>>Расход</option>
          </select>
        </div>
        <button class="btn btn-success">Добавить</button>
        <a href="index.php" class="btn btn-outline-light">Отмена</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>
