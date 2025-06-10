<?php
require_once 'config.php'; 

try {
    
    $stmt = $pdo->query("SELECT cpf, nome, email, arquivo, mensagem, DATE_FORMAT(data_cadastro, '%d/%m/%Y %H:%i') as data_formatada FROM prestadores ORDER BY data_cadastro DESC");
    $dados = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao buscar cadastros: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <link  rel="icon" type="image/png" href="img/logoc.png">
  <title>Lista de Cadastros</title>
  <link rel="stylesheet" href="../style/cadastro.css">
  <style>

    
    body { background-color: #f4f7f6; padding-top: 20px; }

    .container { background-color: #ffffff; max-width: 800px; margin: auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }

    h1 { text-align: center; color: #333; margin-bottom: 20px; }

    .back-link { display: block; margin-bottom: 20px; color: #007bff; text-decoration: none; }

    .back-link:hover { text-decoration: underline; }

    ul { list-style: none; padding: 0; }

    li { background-color: #f9f9f9; border:
       1px solid #eee; padding: 15px; margin-bottom: 10px; border-radius: 4px; }

    li strong { color: #0056b3; }

    li em { color: #555; display: block; margin-top: 5px; font-size: 0.95em; }

    li .details { margin-top: 8px; font-size: 0.9em; color: #777; }

    li .details span { margin-right: 15px; }

    li .file-link { color: #28a745; text-decoration: none; font-weight: bold; }

    li .file-link:hover { text-decoration: underline; }

    hr { border: 0; border-top: 1px solid #eee; margin: 1
      5px 0; }

    .no-records { text-align: center; color: #777; margin-top: 30px; }

  </style>
</head>
<body>
  <div class="container">
    <h1>Todos os Cadastros</h1>
    <a href="../cadastro.html" class="back-link">← Novo Cadastro</a>

    <?php if (count($dados) > 0): ?>
        <ul>
          <?php foreach ($dados as $p): ?>
            <li>
              <strong>Nome:</strong> <?= htmlspecialchars($p['nome']) ?> <br>
              <strong>CPF:</strong> <?= htmlspecialchars($p['cpf']) ?> <br> <strong>Email:</strong> <?= htmlspecialchars($p['email']) ?> <br>

              <?php if (!empty($p['mensagem'])): ?>
                <em>Mensagem: <?= nl2br(htmlspecialchars($p['mensagem'])) ?></em>
              <?php endif; ?>

              <div class="details">
                  <span><strong>Data:</strong> <?= $p['data_formatada'] ?></span>
                  <?php if (!empty($p['arquivo'])): ?>
                    <span><strong>Arquivo:</strong> <a href="../uploads/<?= urlencode($p['arquivo']) ?>" target="_blank" class="file-link">Visualizar</a></span>
                  <?php endif; ?>
              </div>

            </li>
          <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="no-records">Nenhum cadastro encontrado.</p>
    <?php endif; ?>

  </div>
</body>
</html>