document.addEventListener('DOMContentLoaded', () => {
    const loginMenuItem = document.querySelector('a[href="login.html"]');

    if (loginMenuItem) {
        fetch('php/usuario/check_session.php')
            .then(response => response.json())
            .then(data => {
                if (data.loggedIn) {
                    // Quando o usuário logado clicar, chamamos a função que cria o nosso pop-up
                    loginMenuItem.addEventListener('click', function (event) {
                        event.preventDefault();
                        showCustomLogoutModal(data.userName);
                    });
                }
            })
            .catch(error => {
                console.error('Erro ao verificar a sessão:', error);
            });
    }
});

/**
 * Cria e exibe um pop-up (modal) customizado para confirmar o logout.
 * @param {string} userName - O nome do usuário logado.
 */
function showCustomLogoutModal(userName) {
    // Evita criar múltiplos modais se o usuário clicar várias vezes
    if (document.querySelector('.logout-modal-overlay')) {
        return;
    }

    // Cria o fundo escurecido (overlay)
    const overlay = document.createElement('div');
    overlay.className = 'logout-modal-overlay';

    // Cria a caixa do modal
    const modal = document.createElement('div');
    modal.className = 'logout-modal';

    // Cria o conteúdo do modal
    modal.innerHTML = `
        <div class="logout-modal-content">
            <h3>Sessão Ativa</h3>
            <p>Você já está logado como <strong>${userName}</strong>.</p>
            <p>Deseja encerrar a sessão?</p>
        </div>
        <div class="logout-modal-buttons">
            <button id="logout-confirm-btn" class="modal-btn-confirm">Sim, Sair</button>
            <button id="logout-cancel-btn" class="modal-btn-cancel">Cancelar</button>
        </div>
    `;

    // Adiciona o overlay e o modal à página
    document.body.appendChild(overlay);
    document.body.appendChild(modal);
    
    // Força o navegador a aplicar os estilos antes de adicionar a classe 'show' para a animação funcionar
    setTimeout(() => {
        overlay.classList.add('show');
        modal.classList.add('show');
    }, 10);

    // Função para fechar o modal
    const closeModal = () => {
        overlay.classList.remove('show');
        modal.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(overlay);
            document.body.removeChild(modal);
        }, 300); // Espera a transição de fade-out terminar
    };

    // Adiciona os eventos aos botões
    document.getElementById('logout-confirm-btn').addEventListener('click', () => {
        window.location.href = 'php/usuario/logout.php';
    });
    document.getElementById('logout-cancel-btn').addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal); // Permite fechar clicando fora do modal
}