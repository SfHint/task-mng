<?php
require_once 'config.php';
$mysqli = db_connect();
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = intval($_GET['id']);
if ($id > 0) {
    $stmt = $mysqli->prepare('DELETE FROM tasks WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}
header('Location: index.php');
exit; 
