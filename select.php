<?php
require('con1.php');

$query = 'SELECT COUNT(*) popup_message';

$stm = $pdo->prepare($query);

$stm->execute();

if($stm->rowCount()>0){
    $result = $stm->fetch();
    echo json_encode($result[0]);
}
?>