<?php
require_once 'config.php';
session_start();

// ... (O restante do código de processamento e upload permanece o mesmo, pois já estava funcional)
// As melhorias aqui são sutis, como garantir que a sessão seja iniciada.

$nome     = $_POST['nome'] ?? '';
$cpf      = $_POST['cpf'] ?? '';
$email    = $_POST['email'] ?? '';
$mensagem = $_POST['mensagem'] ?? '';
$arquivo_nome = null;

if (empty($nome) || empty($cpf) || empty($email)) {
     die("Erro: Nome, CPF e Email são obrigatórios.");
}

// Lógica de Upload (sem alterações significativas)
if (!empty($_FILES['arquivo']['name']) && $_FILES['arquivo']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $original_filename = basename($_FILES['arquivo']['name']);
    $safe_filename = preg_replace("/[^a-zA-Z0-9._-]/", "_", $original_filename);
    $ext = pathinfo($safe_filename, PATHINFO_EXTENSION);
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
    if (in_array(strtolower($ext), $allowed_extensions)) {
        $arquivo_nome = 'file_' . uniqid() . '.' . strtolower($ext);
        if (!move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadDir . $arquivo_nome)) {
            $arquivo_nome = null;
        }
    }
}

try {
    $stmt = $pdo->prepare("INSERT INTO prestadores (cpf, nome, email, arquivo, mensagem) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$cpf, $nome, $email, $arquivo_nome, $mensagem]);
} catch (PDOException $e) {
     if ($e->getCode() == 23000) {
          die("Erro: Este CPF já está cadastrado.");
     } else {
          die("Ocorreu um erro ao processar seu cadastro.");
     }
}

$_SESSION['dados_enviados'] = [
    'nome' => $nome,
    'cpf' => $cpf,
    'email' => $email,
    'mensagem' => $mensagem,
    'arquivo' => $arquivo_nome
];

header("Location: confirmacao.php");
exit;
?>