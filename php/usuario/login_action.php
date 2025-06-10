<?php
require_once '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../login.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    header('Location: ../../login.html?error=Email e senha são obrigatórios.');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        header('Location: ../../index.html');
        exit;
    } else {
        header('Location: ../../login.html?error=Email ou senha inválidos.');
        exit;
    }
} catch (PDOException $e) {
    error_log("Erro no login: " . $e->getMessage());
    header('Location: ../../login.html?error=Ocorreu um erro no servidor.');
    exit;
}
?>