<?php
require_once 'config.php';
$mysqli = db_connect();
$errors = [];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('Неверный ID записи.');
}
$stmt = $mysqli->prepare('SELECT * FROM finances WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$rec = $res->fetch_assoc();
$stmt->close();
if (!$rec) die('Запись не найдена.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $type = $_POST['type'] ?? '';
    $status = isset($_POST['status']) && $_POST['status'] === '1' ? 1 : 0;
    if ($title === '') $errors[] = 'Название обязательно.';
    if ($amount === '' || !is_numeric($amount)) $errors[] = 'Введите корректную сумму.';
    if ($type !== 'доход' && $type !== 'расход') $errors[] = 'Выберите тип.';
    if (empty($errors)) {
        $stmt = $mysqli->prepare('UPDATE finances SET title = ?, amount = ?, type = ?, status = ? WHERE id = ?');
        $stmt->bind_param('sdsii', $title, $amount, $type, $status, $id);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php');
        exit;
    }
} else {
    $_POST['title'] = $rec['title'];
    $_POST['amount'] = $rec['amount'];
    $_POST['type'] = $rec['type'];
    $_POST['status'] = $rec['status'];
}
?>
<!doctype html>
<html lang="ru" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Редактировать запись — Finance Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #0b0b0d; color: #e6edf3; }
    .card.bg-secondary { background-color: #121214 !important; }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Редактировать запись</h2>
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
            <option value="доход" <?= (($_POST['type'] ?? '') === 'доход') ? 'selected' : '' ?>>Доход</option>
            <option value="расход" <?= (($_POST['type'] ?? '') === 'расход') ? 'selected' : '' ?>>Расход</option>
          </select>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" name="status" value="1" class="form-check-input" id="statusCheck" <?= (!empty($_POST['status']) && $_POST['status']==1) ? 'checked' : '' ?>>
          <label class="form-check-label" for="statusCheck">Закрыта</label>
        </div>
        <button class="btn btn-primary">Сохранить</button>
        <a href="index.php" class="btn btn-outline-light">Отмена</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>
