<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "salaorose";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn -> connect_error){
   echo "Erro ao conectar ao BD: ". $conn -> connect_error;
}
?>