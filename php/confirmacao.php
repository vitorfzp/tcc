<?php
session_start();
$dados = $_SESSION['dados_enviados'] ?? null;

if (!$dados) {
    header("Location: ../cadastro.html");
    exit;
}

unset($_SESSION['dados_enviados']);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="img/logoc.png">
  <title>Confirmação de Cadastro</title>
  <link rel="stylesheet" href="../style/cadastro.css">
   <style>

    body { background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin:0; font-family: Arial, sans-serif; padding: 20px; }

    .container { background-color: #ffffff; padding: 30px 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: left; max-width: 650px; width: 100%; }

    h1 { color: #4CAF50; text-align: center; margin-bottom: 25px; font-size: 1.8em; }

    p { color: #333; line-height: 1.6; margin-bottom: 10px; font-size: 1em; }

    strong { color: #0056b3; font-weight: 600; } 

    .label { display: inline-block; min-width: 80px; } 

    .confirmation-links { margin-top: 30px; text-align: center; }


    .confirmation-links a {
        color: #007bff;
        text-decoration: none;
        padding: 10px 18px;
        margin: 5px 10px; 
        border: 2px solid #007bff;
        border-radius: 6px;
        transition: background-color 0.3s ease, color 0.3s ease;
        font-weight: bold;
        display: inline-block;
    }

    .confirmation-links a:hover {
        background-color: #007bff;
        color: #ffffff;
    }

    hr { border: 0; border-top: 1px solid #eee; margin: 25px 0; }

    .data-item { margin-bottom: 15px; }

    .file-link { color: #28a745; text-decoration: none; font-weight: bold; }

    .file-link:hover { text-decoration: underline; }

    .no-data { color: #777; font-style: italic; } 

   </style>
</head>
<body>
  <div class="container">
    <h1>Cadastro Recebido com Sucesso!</h1>
    <p>Obrigado por se cadastrar, <strong><?= htmlspecialchars($dados['nome']) ?></strong>. Seus dados foram registrados:</p>
    <hr>
    <div class="data-item">
      <p><strong class="label">Nome:</strong> <?= htmlspecialchars($dados['nome']) ?></p>
    </div>
    <div class="data-item">
      <p><strong class="label">CPF:</strong> <?= htmlspecialchars($dados['cpf']) ?></p>
    </div>
    <div class="data-item">
      <p><strong class="label">Email:</strong> <?= htmlspecialchars($dados['email']) ?></p>
    </div>
    <div class="data-item">
      <p><strong class="label">Mensagem:</strong> <?= !empty($dados['mensagem']) ? nl2br(htmlspecialchars($dados['mensagem'])) : '<span class="no-data">Nenhuma mensagem enviada</span>' ?></p>
    </div>
    <div class="data-item">
      <p><strong class="label">Arquivo:</strong>
        <?php if (!empty($dados['arquivo'])): ?>
           <a href="../uploads/<?= urlencode($dados['arquivo']) ?>" target="_blank" class="file-link">Visualizar arquivo enviado</a>
        <?php else: ?>
          <span class="no-data">Nenhum arquivo enviado</span>
        <?php endif; ?>
      </p>
    </div>

    <div class="confirmation-links">
      <a href="../cadastro.html">Novo Cadastro</a>
      <a href="lista.php">Ver Cadastros</a>
    </div>
  </div>
</body>
</html>