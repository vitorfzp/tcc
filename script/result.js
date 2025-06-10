async function loadFeedbacksFromServer() {
    try {
        const response = await fetch('php/get_feedbacks.php');
        if (!response.ok) {
            throw new Error('Falha ao buscar os dados.');
        }
        const feedbacks = await response.json();
        const feedbackResultsList = document.getElementById('feedbackResults');
        feedbackResultsList.innerHTML = '';

        if (feedbacks.length === 0) {
            feedbackResultsList.innerHTML = '<li>Nenhum feedback encontrado.</li>';
            return;
        }

        feedbacks.forEach(feedback => {
            const li = document.createElement('li');
            const feedbackHTML = `
                <strong>${feedback.nome_usuario}</strong> avaliou <strong>${feedback.nome_prestador}</strong> (${feedback.profissao})
                <br>
                <span class="nota">${"⭐".repeat(parseInt(feedback.nota))}</span>
                <p>${feedback.comentario}</p>
                <small>📅 ${feedback.data_formatada}</small>
            `;
            li.innerHTML = feedbackHTML;
            feedbackResultsList.appendChild(li);
        });
    } catch (error) {
        console.error('Erro:', error);
        document.getElementById('feedbackResults').innerHTML = '<li>Ocorreu um erro ao carregar os feedbacks.</li>';
    }
}

window.addEventListener('DOMContentLoaded', loadFeedbacksFromServer);