<?php
// Não precisa mais do auth_guard.php aqui se o cadastro for público.
// Se quiser que apenas usuários logados cadastrem prestadores, mantenha a linha abaixo.
// require_once 'php/auth_guard.php';
?>  

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Prestador - Autonowe</title>
    <link rel="icon" type="image/png" href="img/logo_.png">
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
            
            <div id="message-area" style="margin-bottom: 15px;"></div>

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
                    <label for="profissao">Área de Atuação</label>
                    <select id="profissao" name="profissao" required style="background-color: #f8fafc; border: 1px solid #dbeafe; border-radius: 12px; padding: 14px 18px; font-family: inherit; font-size: 1rem;">
                        <option value="" disabled selected>Selecione sua profissão</option>
                        <option value="Limpeza Geral">Limpeza Geral</option>
                        <option value="Pedreiro">Pedreiro</option>
                        <option value="Jardineiro">Jardineiro</option>
                        <option value="Segurança">Segurança</option>
                        <option value="Animador de Festa">Animador de Festa</option>
                        <option value="Barman">Barman</option>
                        <option value="Cabeleireiro">Cabeleireiro</option>
                        <option value="Transporte de aplicativo">Transporte de aplicativo</option>
                    </select>
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
             <p class="form-link" style="margin-top: 10px;">
                <a href="index.php">← Voltar para o Início</a>
             </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const messageArea = document.getElementById('message-area');
            const error = params.get('error');
            if (error) {
                // A classe "message error" já existe no seu CSS `auth_style.css`
                messageArea.innerHTML = `<div class="message error">${decodeURIComponent(error)}</div>`;
                // Limpa a URL para que o erro não fique aparecendo se o usuário recarregar a página
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>
</html>