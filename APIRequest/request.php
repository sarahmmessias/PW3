<?php

    $nome = $_GET["nome"]; /* nome -> query string */
    if ($nome == "sarah") {
        echo "usuario logado";
    } else {
        echo "usuario errado"; 
    }
    echo $nome;
?>