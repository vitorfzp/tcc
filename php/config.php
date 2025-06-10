<?php

$host = 'localhost';
$db   = 'cadastro_site'; 
$user = 'root';
$pass = 'aluno123'; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options); 
} catch (\PDOException $e) {
     
     die("Erro ao conectar ao banco de dados.");
}
?>