<?php
require_once 'config.php';
$mysqli = db_connect();
$errors = [];
$id = intval($_GET['id'] ?? 0);
$stmt = $mysqli->prepare('SELECT * FROM finances WHERE id=?');
$stmt->bind_param('i',$id);
$stmt->execute();
$res=$stmt->get_result();
$rec=$res->fetch_assoc();
$stmt->close();
if(!$rec) die('Запись не найдена.');

if($_SERVER['REQUEST_METHOD']==='POST'){
  $title=trim($_POST['title']??'');
  $amount=trim($_POST['amount']??'');
  $type=$_POST['type']??'';
  $status=isset($_POST['status'])?1:0;
  if($title==='') $errors[]='Название обязательно.';
  if($amount===''||!is_numeric($amount)) $errors[]='Введите корректную сумму.';
  if($type!=='доход'&&$type!=='расход') $errors[]='Укажите тип.';
  if(empty($errors)){
    $stmt=$mysqli->prepare('UPDATE finances SET title=?, amount=?, type=?, status=? WHERE id=?');
    $stmt->bind_param('sdsii',$title,$amount,$type,$status,$id);
    $stmt->execute(); $stmt->close();
    header('Location: index.php'); exit;
  }
}else{$_POST=$rec;}
?>
<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Редактировать запись</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><div class="container py-4">
<h1>Редактировать запись</h1>
<?php if(!empty($errors)):?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>';?></ul></div><?php endif;?>
<form method="post">
<div class="mb-3"><label class="form-label">Название</label><input name="title" class="form-control" value="<?=htmlspecialchars($_POST['title']??'')?>"></div>
<div class="mb-3"><label class="form-label">Сумма</label><input name="amount" type="number" step="0.01" class="form-control" value="<?=htmlspecialchars($_POST['amount']??'')?>"></div>
<div class="mb-3"><label class="form-label">Тип</label><select name="type" class="form-select">
<option value="доход" <?= (($_POST['type']??'')==='доход')?'selected':'' ?>>Доход</option>
<option value="расход" <?= (($_POST['type']??'')==='расход')?'selected':'' ?>>Расход</option>
</select></div>
<div class="mb-3 form-check"><input type="checkbox" name="status" value="1" class="form-check-input" id="statusCheck" <?=(!empty($_POST['status'])&&$_POST['status']==1)?'checked':''?>>
<label for="statusCheck" class="form-check-label">Закрыта</label></div>
<button class="btn btn-primary">Сохранить</button> <a href="index.php" class="btn btn-secondary">Отмена</a>
</form></div></body></html>
