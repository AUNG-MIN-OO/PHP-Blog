<?php 

require "../config/config.php";
$stmt = $pdo->prepare("DELETE FROM posts WHERE id=".$_GET['id']);
$result = $stmt->execute();

if($result){
    echo "<script>alert('Post has been deleted');window.location.href='index.php';</script>";
}