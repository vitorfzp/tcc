<?php
// Inicia a sessão para verificar as informações de login
session_start();

// Verifica se a variável de sessão 'user_id' NÃO existe.
// Se não existir, significa que o usuário não está logado.
if (!isset($_SESSION['user_id'])) {
    // Redireciona o usuário para a página de formulário de login com uma mensagem de erro.
    header('Location: login_form.html?error=Você precisa estar logado para acessar esta página.');
    // Garante que o restante do script da página não seja executado.
    exit;
}
?>