<?php require_once 'php/auth_guard.php'; ?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Serviços - Autonowe</title>
    <link rel="icon" type="image/png" href="img/logoc.png">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/custom.css">
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
            <a href="login.php" class="menu-item" title="Login"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v-2h8v2zm0-4h-8v-2h8v2zm0-4h-8V9h8v2z"/></svg><span>Login / Cadastro</span></a>
        </nav>
    </aside>

    <main class="main-content">
        <section class="main-section">
            <h2>Nossos Serviços</h2>
            <p>Conheça as categorias de serviços disponíveis na nossa plataforma.</p>
            <div class="service-grid">
                <div class="service-card">
                    <img src="https://content.paodeacucar.com/wp-content/uploads/2019/06/produtos-de-limpeza2.jpg" alt="Limpeza">
                    <div class="card-content">
                        <h3>Limpeza Geral</h3>
                        <p>Varrer, esfregar, aspirar, lavar e polir superfícies.</p>
                        <span class="card-badge">Autonowe Valida</span>
                    </div>
                </div>
                 <div class="service-card">
                    <img src="https://emplaco.com.br/wp-content/uploads/2021/12/Pedreiro-em-Nova-Lima-1024x769.jpg" alt="Pedreiro">
                    <div class="card-content">
                        <h3>Pedreiro</h3>
                        <p>Construção de estruturas de concreto armado e Casas, além de reformar.</p>
                        <span class="card-badge">Autonowe Valida</span>
                    </div>
                </div>
                 <div class="service-card">
                    <img src="https://conecta.fg.com.br/wp-content/uploads/2019/11/jardineiro.png" alt="Jardineiro">
                    <div class="card-content">
                        <h3>Jardineiro</h3>
                        <p>Instalar e manter jardim com o uso de ferramentas.</p>
                        <span class="card-badge">Autonowe Valida</span>
                    </div>
                </div>
                 <div class="service-card">
                    <img src="https://www.verzani.com.br/wp-content/uploads/2022/05/post_thumbnail-4448d68024acf904ff00c094aa5e0d5a.jpeg" alt="Segurança">
                    <div class="card-content">
                        <h3>Segurança</h3>
                        <p>Manter segurança no estabelecimento.</p>
                        <span class="card-badge">Autonowe Valida</span>
                    </div>
                </div>
                <div class="service-card">
                    <img src="https://cdn.fixando.com/u_pt/h/495_teaser.jpg?x=932edd486881bd31df27873c4c0a3659" alt="Animador">
                    <div class="card-content">
                        <h3>Animador de Festa</h3>
                        <p>Manter a festa animada e divertida.</p>
                        <span class="card-badge">Autonowe Valida</span>
                    </div>
                </div>
                <div class="service-card">
                    <img src="https://www.drinkpedia.net.br/wp-content/uploads/2023/11/barman-ou-bartender-03-edited.png" alt="Barman">
                    <div class="card-content">
                        <h3>Barman</h3>
                        <p>Criar uma atmosfera diferente em um drink especial e sofisticado.</p>
                        <span class="card-badge">Autonowe Valida</span>
                    </div>
                </div>
                 <div class="service-card">
                    <img src="https://assets.institutoembelleze.com/images/site-v04/pt-br/cursos/barbeiro-profissional-pleno-estilista-de-cabelo/carousel/01-mobile.jpg" alt="Cabeleireiro">
                    <div class="card-content">
                        <h3>Cabeleireiro</h3>
                        <p>Manter o cabelo com um estilo único e diferente.</p>
                        <span class="card-badge">Autonowe Valida</span>
                    </div>
                </div>
                <div class="service-card">
                    <img src="https://img.odcdn.com.br/wp-content/uploads/2021/04/aplicativo-de-transporte.jpg" alt="Transporte">
                    <div class="card-content">
                        <h3>Transporte de aplicativo</h3>
                        <p>Transportar o cliente para o local seguro com a melhor qualidade.</p>
                        <span class="card-badge">Autonowe Valida</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="main-section">
            <h2>Nossa Localização</h2>
            <p>Estamos localizados no coração de Mogi Mirim, prontos para atender você.</p>
            <div id="map" class="map-container"></div>
        </section>
    </main>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="script/map.js" defer></script>
    <script src="script/session_handler.js" defer></script>
</body>
</html>