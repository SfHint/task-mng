<?php
require_once 'config.php';
$mysqli=db_connect();
$id=intval($_GET['id']??0);
if($id>0){
 $stmt=$mysqli->prepare('DELETE FROM finances WHERE id=?');
 $stmt->bind_param('i',$id);
 $stmt->execute(); $stmt->close();
}
header('Location: index.php'); exit;
