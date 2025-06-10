<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html?error=Você precisa estar logado para enviar um feedback.');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../feedback.html?error=Método inválido.');
    exit;
}

$usuario_id = $_SESSION['user_id'];
$nome_prestador = trim($_POST['nome_prestador'] ?? '');
$profissao = trim($_POST['profissao'] ?? '');
$nota = filter_input(INPUT_POST, 'nota', FILTER_VALIDATE_INT);
$comentario = trim($_POST['comentario'] ?? '');
$tipo = 'serviços'; // Valor padrão

if (empty($nome_prestador) || empty($profissao) || $nota === false || empty($comentario)) {
    header('Location: ../feedback.html?error=Todos os campos são obrigatórios.');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO feedbacks (usuario_id, nome_prestador, profissao, tipo, nota, comentario) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$usuario_id, $nome_prestador, $profissao, $tipo, $nota, $comentario]);
    header('Location: ../resultado.html?success=Feedback enviado com sucesso!');
    exit;
} catch (PDOException $e) {
    error_log("Erro ao salvar feedback: " . $e->getMessage());
    header('Location: ../feedback.html?error=Ocorreu um erro ao salvar seu feedback.');
    exit;
}
?>