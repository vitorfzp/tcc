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
     error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
     die("Erro ao conectar ao banco de dados. Verifique as configurações ou contate o suporte."); // Mensagem genérica para o usuário
}
?>