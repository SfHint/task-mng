<?php
require_once 'config.php';
$mysqli = db_connect();
if (!isset($_GET['id']) || !isset($_GET['action'])) {
    header('Location: index.php');
    exit;
}
$id = intval($_GET['id']);
$action = $_GET['action'];
if ($id > 0) {
    if ($action === 'done') {
        $status = 1;
    } else { 
        $status = 0;
    }
    $stmt = $mysqli->prepare('UPDATE tasks SET status = ? WHERE id = ?');
    $stmt->bind_param('ii', $status, $id);
    $stmt->execute();
    $stmt->close();
}
header('Location: index.php');
exit;
