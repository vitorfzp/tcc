<?php
require_once 'config_usuario.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../register.html?error=Método inválido');
    exit;
}


$nome = trim($_POST['nome'] ?? '');
$cpf = trim($_POST['cpf'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? ''; 
$confirma_senha = $_POST['confirma_senha'] ?? '';

$errors = [];
if (empty($nome)) {
    $errors[] = "Nome é obrigatório.";
}
if (empty($cpf)) {
    $errors[] = "CPF é obrigatório.";
} elseif (!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $cpf)) {
     $errors[] = "Formato de CPF inválido (use XXX.XXX.XXX-XX).";
}
if (empty($email)) {
    $errors[] = "Email é obrigatório.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Formato de Email inválido.";
}
if (empty($senha)) {
    $errors[] = "Senha é obrigatória.";
} elseif (strlen($senha) < 6) {
    $errors[] = "Senha deve ter no mínimo 6 caracteres.";
}
if ($senha !== $confirma_senha) {
    $errors[] = "As senhas não coincidem.";
}

if (!empty($errors)) {
    $error_string = implode(' ', $errors);
    header('Location: ../../register.html?error=' . urlencode($error_string));
    exit;
}

try {
    $stmt_check = $pdo->prepare("SELECT id FROM usuario WHERE cpf = ? OR email = ?");
    $stmt_check->execute([$cpf, $email]);
    if ($stmt_check->fetch()) {
        header('Location: ../../register.html?error=' . urlencode("CPF ou Email já cadastrado."));
        exit;
    }
} catch (PDOException $e) {
    error_log("Erro ao verificar usuário existente: " . $e->getMessage());
    header('Location: ../../register.html?error=' . urlencode("Erro no banco de dados ao verificar usuário. Tente novamente."));
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
if ($senha_hash === false) {
     error_log("Falha ao gerar hash da senha.");
     header('Location: ../../register.html?error=' . urlencode("Erro crítico ao processar senha. Contate o suporte."));
     exit;
}


try {
    $stmt_insert = $pdo->prepare("INSERT INTO usuario (cpf, nome, email, senha) VALUES (?, ?, ?, ?)");
    $stmt_insert->execute([$cpf, $nome, $email, $senha_hash]);

    header('Location: ../../login.html?success=' . urlencode("Cadastro realizado com sucesso! Faça o login."));
    exit;

} catch (PDOException $e) {
    error_log("Erro ao inserir usuário no banco: " . $e->getMessage());
    if ($e->getCode() == 23000) {
         header('Location: ../../register.html?error=' . urlencode("CPF ou Email já cadastrado (verificação dupla)."));
    } else {
         header('Location: ../../register.html?error=' . urlencode("Erro ao realizar o cadastro. Tente novamente mais tarde."));
    }
    exit;
}
?>