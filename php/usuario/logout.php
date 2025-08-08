<?php
// Inicia a sessão para poder manipulá-la
session_start();

// Remove todas as variáveis da sessão
session_unset();

// Destrói a sessão
session_destroy();

// Redireciona para a tela de entrada com uma mensagem de sucesso
header("Location: ../../login.html?success=Logout realizado com sucesso!");
exit;
?>