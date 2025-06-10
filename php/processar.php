<?php
require_once 'config.php';
session_start();


$nome     = $_POST['nome'] ?? '';
$cpf      = $_POST['cpf'] ?? ''; 
$email    = $_POST['email'] ?? '';
$mensagem = $_POST['mensagem'] ?? '';
$arquivo_nome = null;

if (empty($nome) || empty($cpf) || empty($email)) {
     die("Erro: Nome, CPF e Email são obrigatórios.");
}

if (!empty($_FILES['arquivo']['name']) && $_FILES['arquivo']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/'; 
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
             error_log("Falha ao criar diretório de uploads: " . $uploadDir);
            die("Erro: Não foi possível preparar o local para upload.");
        }
    }

    $original_filename = basename($_FILES['arquivo']['name']);
  
    $safe_filename = preg_replace("/[^a-zA-Z0-9._-]/", "_", $original_filename);
    $ext = pathinfo($safe_filename, PATHINFO_EXTENSION);

  
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']; 
    if (empty($ext) || !in_array(strtolower($ext), $allowed_extensions)) {
         die("Erro: Tipo de arquivo inválido ou sem extensão. Permitidos: " . implode(', ', $allowed_extensions));
    }

    $arquivo_nome = 'file_' . uniqid() . '.' . strtolower($ext);

    // Mover o arquivo
    if (!move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadDir . $arquivo_nome)) {
         error_log("Erro ao mover o arquivo enviado: " . $_FILES['arquivo']['error'] . " para " . $uploadDir . $arquivo_nome);
         die("Erro ao processar o upload do arquivo. Verifique as permissões da pasta uploads.");
         $arquivo_nome = null;
    }
} elseif ($_FILES['arquivo']['error'] != UPLOAD_ERR_NO_FILE) {
    
     error_log("Erro no upload do arquivo: Código " . $_FILES['arquivo']['error']);
     die("Ocorreu um erro durante o upload do arquivo.");
}



try {

    $stmt = $pdo->prepare("INSERT INTO prestadores (cpf, nome, email, arquivo, mensagem) VALUES (?, ?, ?, ?, ?)");

    // Execução incluindo a variável $cpf
    $stmt->execute([$cpf, $nome, $email, $arquivo_nome, $mensagem]);

} catch (PDOException $e) {
     error_log("Erro ao inserir no banco de dados: " . $e->getMessage() . " - CPF: " . $cpf);
  
     if ($e->getCode() == 23000) { 
          die("Erro: Este CPF já está cadastrado.");
     } else {
          die("Ocorreu um erro ao processar seu cadastro. Tente novamente mais tarde ou contate o suporte.");
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