<?php

    $user = "adminprogweb";
    $pass = "ProgWeb3";
    $db = "progweb3";
    $conn = mysqli_connect($server, $user, $pass, $db);
    
    if ($conn->connect_errno){
        die ("Erro de conexão!" . $conn->connect_error);
    }else{
        return $conn;         
    }

?>
