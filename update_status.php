<?php
require_once 'config.php';
$mysqli=db_connect();
$id=intval($_GET['id']??0);
$action=$_GET['action']??'';
if($id>0){
 $status=($action==='close')?1:0;
 $stmt=$mysqli->prepare('UPDATE finances SET status=? WHERE id=?');
 $stmt->bind_param('ii',$status,$id);
 $stmt->execute(); $stmt->close();
}
header('Location: index.php'); exit;
