// Função para carregar os feedbacks armazenados no localStorage
function loadFeedbacks() {
  return JSON.parse(localStorage.getItem('feedbacks')) || [];
}

// Função para obter os prestadores únicos dos feedbacks
function getUniqueEstabelecimentos() {
  const feedbacks = loadFeedbacks();
  const estabelecimentos = feedbacks.map(fb => fb.nomed);
  // Remove duplicatas com Set
  return [...new Set(estabelecimentos)];
}

// Função para obter as profissões únicas dos feedbacks
function getUniqueProfissoes() {
  const feedbacks = loadFeedbacks();
  const profissoes = feedbacks.map(fb => fb.serv);
  return [...new Set(profissoes)];
}

// Popula o dropdown com os prestadores disponíveis
function populateEstabelecimentoFilter() {
  const select = document.getElementById('estabelecimentoFilter');
  const estabelecimentos = getUniqueEstabelecimentos();
  estabelecimentos.forEach(estab => {
    const option = document.createElement('option');
    option.value = estab;
    option.textContent = estab;
    select.appendChild(option);
  });
}

// Popula o dropdown com as profissões disponíveis
function populateProfissaoFilter() {
  const select = document.getElementById('feedbackTypeFilter');
  const profissoes = getUniqueProfissoes();
  profissoes.forEach(prof => {
    const option = document.createElement('option');
    option.value = prof;
    option.textContent = prof;
    select.appendChild(option);
  });
}

// Agrega a quantidade de feedbacks por nota (1 a 5), filtrando por prestador e profissão
function getAggregatedData(filterEstabelecimento = "all", filterProfissao = "all") {
  const feedbacks = loadFeedbacks();
  const counts = [0, 0, 0, 0, 0]; // índices 0 a 4 correspondem às notas 1 a 5
  feedbacks.forEach(fb => {
    if ((filterEstabelecimento === "all" || fb.nomed === filterEstabelecimento) &&
        (filterProfissao === "all" || fb.serv === filterProfissao)) {
      const nota = parseInt(fb.nota);
      if (nota >= 1 && nota <= 5) {
        counts[nota - 1]++;
      }
    }
  });
  return counts;
}

// Dados iniciais para o gráfico (todos os feedbacks)
const initialData = getAggregatedData();
const feedbackData = {
  labels: ['1 ⭐', '2 ⭐⭐', '3 ⭐⭐⭐', '4 ⭐⭐⭐⭐', '5 ⭐⭐⭐⭐⭐'],
  datasets: [{
    label: 'Quantidade de Feedbacks',
    data: initialData,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(255, 206, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(153, 102, 255, 0.2)'
    ],
    borderColor: [
      'rgba(255, 99, 132, 1)',
      'rgba(54, 162, 235, 1)',
      'rgba(255, 206, 86, 1)',
      'rgba(75, 192, 192, 1)',
      'rgba(153, 102, 255, 1)'
    ],
    borderWidth: 1
  }]
};

// Configuração do gráfico de barras
const config = {
  type: 'bar',
  data: feedbackData,
  options: {
    scales: {
      y: {
        beginAtZero: true,
        title: { display: true, text: 'Quantidade de Feedbacks' }
      }
    }
  }
};

// Cria o gráfico
const ctx = document.getElementById('feedbackChart').getContext('2d');
const feedbackChart = new Chart(ctx, config);

// Atualiza o gráfico conforme os filtros selecionados
function updateChart() {
  const selectedEstab = document.getElementById('estabelecimentoFilter').value;
  const selectedProfissao = document.getElementById('feedbackTypeFilter').value;
  const newData = getAggregatedData(selectedEstab, selectedProfissao);
  feedbackChart.data.datasets[0].data = newData;
  feedbackChart.update();
}

// Inicializa os dropdowns e adiciona os listeners para alteração dos filtros
populateEstabelecimentoFilter();
populateProfissaoFilter();
document.getElementById('estabelecimentoFilter').addEventListener('change', updateChart);
document.getElementById('feedbackTypeFilter').addEventListener('change', updateChart);
