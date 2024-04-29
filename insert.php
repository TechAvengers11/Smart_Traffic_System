<?php

require('con1.php');

if(isset($_POST['msg'])){
    $query = 'INSERT INTO popup_message (message) VALUES (:msg)'; 

    $stm = $pdo->prepare($query);

    $stm->bindValue(':msg', $_POST['msg']);

    if($stm->execute()){
        echo json_encode('added');
    }
}
?>