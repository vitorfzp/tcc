<?php
require_once 'php/auth_guard.php';

// --- LÓGICA PHP COMPLETA ---
$servico_selecionado = $_GET['servico'] ?? 'N/A';
$termo_busca = trim($_GET['busca'] ?? '');

$descricoes_servicos = [
    'Limpeza Geral' => 'Profissionais avaliados para limpeza residencial ou comercial. De faxinas pesadas a manutenções diárias, encontre aqui a solução para um ambiente impecável.',
    'Pedreiro' => 'Encontre pedreiros qualificados para construções, reformas e reparos em geral. Qualidade e confiança para a sua obra.',
];
$descricao_atual = $descricoes_servicos[$servico_selecionado] ?? 'Encontre os profissionais mais bem avaliados pela nossa comunidade.';

try {
    // Busca TODOS os prestadores daquela área (para a lista principal)
    $sql_prestadores = "
        SELECT
            p.nome AS nome_prestador, p.profissao, AVG(f.nota) AS media_notas, COUNT(f.id) AS total_avaliacoes
        FROM prestadores p
        LEFT JOIN feedbacks f ON p.nome = f.nome_prestador AND p.profissao = f.profissao
        WHERE p.profissao = :servico";

    if (!empty($termo_busca)) {
        $sql_prestadores .= " AND p.nome LIKE :busca";
    }
    $sql_prestadores .= " GROUP BY p.cpf, p.nome, p.profissao ORDER BY media_notas DESC, p.nome ASC";
    $stmt_prestadores = $pdo->prepare($sql_prestadores);
    $params = [':servico' => $servico_selecionado];
    if (!empty($termo_busca)) {
        $params[':busca'] = '%' . $termo_busca . '%';
    }
    $stmt_prestadores->execute($params);
    $prestadores = $stmt_prestadores->fetchAll();

    // Busca os 3 feedbacks MAIS RECENTES (para a barra lateral)
    $stmt_recentes = $pdo->prepare("
        SELECT f.comentario, f.nota, u.nome as nome_usuario, f.nome_prestador
        FROM feedbacks f JOIN usuario u ON f.usuario_id = u.id
        WHERE f.profissao = ? ORDER BY f.data_feedback DESC LIMIT 3
    ");
    $stmt_recentes->execute([$servico_selecionado]);
    $feedbacks_recentes = $stmt_recentes->fetchAll();

    // Busca TODOS os feedbacks (para o modal)
    $stmt_todos = $pdo->prepare("
        SELECT f.comentario, f.nota, u.nome as nome_usuario, f.nome_prestador, DATE_FORMAT(f.data_feedback, '%d/%m/%Y') as data_formatada
        FROM feedbacks f JOIN usuario u ON f.usuario_id = u.id
        WHERE f.profissao = ? ORDER BY f.data_feedback DESC
    ");
    $stmt_todos->execute([$servico_selecionado]);
    $todos_feedbacks = $stmt_todos->fetchAll();

} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profissionais de <?php echo htmlspecialchars($servico_selecionado); ?> - Autonowe</title>
    <link rel="icon" type="image/png" href="img/LOGO.png">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="img/LOGO.png" alt="Logo Autonowe" class="logo-icon" />
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
        <section class="service-header">
            <h1><?php echo htmlspecialchars($servico_selecionado); ?></h1>
            <p><?php echo htmlspecialchars($descricao_atual); ?></p>
            <div class="service-actions">
                <a href="feedback.php?servico=<?php echo urlencode($servico_selecionado); ?>" class="btn btn-primary">Deixar um Feedback</a>
                <button id="openModalBtn" class="btn btn-secondary">Ver Todos os Feedbacks</button>
            </div>
        </section>

        <section class="search-tool-section">
            <form action="servico_detalhe.php" method="GET">
                <input type="hidden" name="servico" value="<?php echo htmlspecialchars($servico_selecionado); ?>">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="busca" placeholder="Buscar por nome do profissional..." value="<?php echo htmlspecialchars($termo_busca); ?>">
                </div>
                <button type="submit">Buscar</button>
            </form>
        </section>

        <div class="content-layout">
            <div class="prestador-list-main">
                <h2>Profissionais Encontrados</h2>
                <div class="prestador-list">
                    <?php if (count($prestadores) > 0): ?>
                        <?php foreach ($prestadores as $prestador): ?>
                            <div class="prestador-card">
                                <div class="prestador-avatar"><i class="fas fa-user-circle"></i></div>
                                <div class="prestador-info">
                                    <h3><?php echo htmlspecialchars($prestador['nome_prestador']); ?></h3>
                                    <div class="prestador-rating">
                                        <?php if ($prestador['total_avaliacoes'] > 0): ?>
                                            <span class="stars"><?php echo str_repeat('⭐', round($prestador['media_notas'])); ?></span>
                                            <span class="rating-text">
                                                <strong><?php echo number_format($prestador['media_notas'], 1, ','); ?></strong> 
                                                (<?php echo $prestador['total_avaliacoes']; ?> avaliações)
                                            </span>
                                        <?php else: ?>
                                            <span class="rating-text">Nenhuma avaliação ainda</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="prestador-action">
                                    <a href="perfil_prestador.php?nome=<?php echo urlencode($prestador['nome_prestador']); ?>" class="btn-ver-perfil">Ver Perfil</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-results">Nenhum profissional encontrado. Você pode ser o primeiro a se cadastrar nesta área!</p>
                    <?php endif; ?>
                </div>
            </div>

            <aside class="recent-feedbacks-sidebar">
                <h3>Feedbacks Recentes</h3>
                <?php if (count($feedbacks_recentes) > 0): ?>
                    <?php foreach ($feedbacks_recentes as $fb): ?>
                        <div class="feedback-card-small">
                            <p class="comment">"<?php echo htmlspecialchars($fb['comentario']); ?>"</p>
                            <div class="author-info">
                                <span><strong><?php echo htmlspecialchars($fb['nome_usuario']); ?></strong> avaliou <strong><?php echo htmlspecialchars($fb['nome_prestador']); ?></strong></span>
                                <span class="stars-small"><?php echo str_repeat('⭐', $fb['nota']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Ainda não há feedbacks para este serviço.</p>
                <?php endif; ?>
            </aside>
        </div>
    </main>

    <div id="feedbackModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <br>
                <br>
                <h3>Todos os Feedbacks para <?php echo htmlspecialchars($servico_selecionado); ?></h3>
                <br>
                
                <button id="closeModalBtn" class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                        <tr>
                            <th>Usuário</th> <th>Prestador</th> <th>Nota</th> <th>Comentário</th> <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($todos_feedbacks) > 0): ?>
                            <?php foreach ($todos_feedbacks as $fb): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fb['nome_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($fb['nome_prestador']); ?></td>
                                    <td><?php echo str_repeat('⭐', $fb['nota']); ?></td>
                                    <td><?php echo htmlspecialchars($fb['comentario']); ?></td>
                                    <td><?php echo htmlspecialchars($fb['data_formatada']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">Nenhum feedback encontrado para este serviço.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="script/session_handler.js" defer></script>
    <script src="script/modal_handler.js" defer></script>
</body>
</html>