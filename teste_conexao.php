<?php

require_once "config/Database.php";

$database = new Database();
$conn = $database->getConnection();

if($conn){
    echo "Conexão realizada com sucesso!";
}else{
    echo "Falha na conexão.";
}