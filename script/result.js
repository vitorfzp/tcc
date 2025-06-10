  // Ao carregar a página, recupera os feedbacks do localStorage e exibe na lista
  window.addEventListener('DOMContentLoaded', function() {
    // Recupera os feedbacks do localStorage
    let feedbacks = JSON.parse(localStorage.getItem('feedbacks')) || [];
    // Recupera a lista onde os feedbacks serão exibidos  
    let feedbackResults = document.getElementById('feedbackResults');

    // Exibe cada feedback na lista
    feedbacks.forEach(function(feedback) {
        // Cria um elemento <li> para cada feedback
      let li = document.createElement('li');
      // Adiciona o texto do feedback ao elemento <li>
      li.innerHTML = feedback.html;
      // Adiciona o elemento <li> a lista
      feedbackResults.appendChild(li);
    });
  });

  // Evento para o botão que limpa todos os comentários
  document.getElementById('btnClear').addEventListener('click', function() {
    // Remove os dados armazenados
    localStorage.removeItem('feedbacks');
    // Limpa a lista exibida
    document.getElementById('feedbackResults').innerHTML = '';
  });