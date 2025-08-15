<?php
// 1. Simplesmente inclua o novo auth_guard. Ele faz todo o trabalho!
require_once 'php/auth_guard.php';
?>  

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Prestador - Autonowe</title>
    <link rel="icon" type="image/png" href="img/logoc.png">
    <link rel="stylesheet" href="style/auth_style.css">
</head>
<body>
    <div class="auth-container">
        <div class="logo-section">
            <div class="logo-container">
                <img src="img/logoc.png" alt="AUTONOWE Logo">
                <h1>AUTONOWE</h1>
            </div>
        </div>
        <div class="form-section">
            <h2>Cadastro de Prestador</h2>
            <p style="text-align: center; margin-bottom: 20px;">Preencha para se tornar um prestador em nossa plataforma.</p>
            <form class="auth-form" action="php/processar.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" required placeholder="000.000.000-00">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="arquivo">Anexar currículo (Opcional)</label>
                    <input type="file" id="arquivo" name="arquivo">
                </div>

                <div class="form-group">
                    <label for="mensagem">Mensagem (Opcional)</label>
                    <textarea id="mensagem" name="mensagem" rows="3" style="background-color: #f8fafc; border: 1px solid #dbeafe; border-radius: 12px; padding: 14px 18px; font-family: inherit; font-size: 1rem;"></textarea>
                </div>

                <button type="submit" class="auth-button">Enviar Cadastro</button>
            </form>
             <p class="form-link">
                Já é um prestador? <a href="login.html">Faça Login</a>
             </p>
        </div>
    </div>
</body>
</html>