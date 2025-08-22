<?php
require_once 'php/auth_guard.php';

// Pega o nome do prestador da URL. É crucial para segurança usar urlencode/urldecode.
$nome_prestador = urldecode($_GET['nome'] ?? '');

if (empty($nome_prestador)) {
    die("Nenhum prestador especificado.");
}

try {
    // ADIÇÃO 1: Busca a descrição (mensagem) do profissional na tabela `prestadores`
    $stmt_info = $pdo->prepare("SELECT mensagem FROM prestadores WHERE nome = ?");
    $stmt_info->execute([$nome_prestador]);
    $prestador_info = $stmt_info->fetch();

    // 1. Busca as estatísticas gerais do prestador (média de notas, total de avaliações, profissão)
    $stmt_stats = $pdo->prepare("
        SELECT 
            profissao, 
            AVG(nota) as media_notas, 
            COUNT(id) as total_avaliacoes
        FROM feedbacks 
        WHERE nome_prestador = ?
        GROUP BY profissao
    ");
    $stmt_stats->execute([$nome_prestador]);
    $stats = $stmt_stats->fetch();

    // 2. Busca TODOS os feedbacks individuais para este prestador
    $stmt_feedbacks = $pdo->prepare("
        SELECT 
            f.nota, 
            f.comentario, 
            DATE_FORMAT(f.data_feedback, '%d/%m/%Y') as data_formatada,
            u.nome as nome_usuario
        FROM feedbacks f
        JOIN usuario u ON f.usuario_id = u.id
        WHERE f.nome_prestador = ?
        ORDER BY f.data_feedback DESC
    ");
    $stmt_feedbacks->execute([$nome_prestador]);
    $feedbacks = $stmt_feedbacks->fetchAll();

} catch (PDOException $e) {
    die("Erro ao buscar perfil do prestador: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($nome_prestador); ?> - Autonowe</title>
    <link rel="icon" type="image/png" href="img/logoc.png">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="img/logoc.png" alt="Logo Autonowe" class="logo-icon" />
            <h2 class="brand-title">AUTONOWE</h2>
        </div>
        <nav class="sidebar-menu">
            <a href="index.php" class="menu-item" title="Início"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg><span>Início</span></a>
            <a href="local.php" class="menu-item active" title="Serviços"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><span>Serviços</span></a>
            <a href="resultado.php" class="menu-item" title="Resultados"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H4V4h16v12zM6 10h8v2H6zm0-4h12v2H6z"/></svg><span>Resultados</span></a>
            <a href="feedback.php" class="menu-item" title="Feedback"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-4h-2V6h2v4z"/></svg><span>Feedback</span></a>
            <a href="estaticas.php" class="menu-item" title="Estatísticas"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg><span>Estatísticas</span></a>
            <a href="login.html" class="menu-item" title="Login"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v-2h8v2zm0-4h-8v-2h8v2zm0-4h-8V9h8v2z"/></svg><span>Login / Cadastro</span></a>
        </nav>
    </aside>

    <main class="main-content">
        <section class="main-section">
            <div class="profile-header">
                <div class="profile-avatar"><i class="fas fa-user-shield"></i></div>
                <div class="profile-summary">
                    <h1><?php echo htmlspecialchars($nome_prestador); ?></h1>
                    <h2><?php echo htmlspecialchars($stats['profissao'] ?? 'Profissional'); ?></h2>
                    <div class="profile-overall-rating">
                        <span class="stars"><?php echo str_repeat('⭐', round($stats['media_notas'] ?? 0)); ?></span>
                        <strong><?php echo number_format($stats['media_notas'] ?? 0, 1, ','); ?></strong>
                        <span>(baseado em <?php echo $stats['total_avaliacoes'] ?? 0; ?> avaliações)</span>
                    </div>
                </div>
            </div>

            <div class="profile-body">
                
                <?php if ($prestador_info && !empty(trim($prestador_info['mensagem']))): ?>
                    <div class="profile-about-section">
                        <h3>Sobre o Profissional</h3>
                        <p><?php echo nl2br(htmlspecialchars($prestador_info['mensagem'])); ?></p>
                    </div>
                <?php endif; ?>

                <h3>O que os clientes dizem</h3>
                <div class="feedback-list-full">
                    <?php if (count($feedbacks) > 0): ?>
                        <?php foreach ($feedbacks as $fb): ?>
                            <div class="feedback-item">
                                <div class="feedback-item-header">
                                    <div class="feedback-author">
                                        <i class="fas fa-user"></i>
                                        <span><?php echo htmlspecialchars($fb['nome_usuario']); ?></span>
                                    </div>
                                    <div class="feedback-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span><?php echo $fb['data_formatada']; ?></span>
                                    </div>
                                </div>
                                <div class="feedback-item-body">
                                    <span class="stars"><?php echo str_repeat('⭐', $fb['nota']); ?></span>
                                    <p><?php echo htmlspecialchars($fb['comentario']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Este profissional ainda não recebeu avaliações.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <script src="script/session_handler.js" defer></script>
</body>
</html>