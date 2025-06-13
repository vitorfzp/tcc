// script/estatistica.js

// Variáveis globais para armazenar os dados e o gráfico
let allFeedbacks = [];
let feedbackChart = null;

// Função principal para buscar dados e inicializar a página
async function initializeStatistics() {
    try {
        const response = await fetch('php/get_feedback.php');
        if (!response.ok) {
            throw new Error('Falha ao buscar os dados do servidor.');
        }
        allFeedbacks = await response.json();

        populateFilters();
        setupEventListeners();
        renderChart();

    } catch (error) {
        console.error('Erro ao inicializar estatísticas:', error);
        const chartContainer = document.getElementById('feedbackChart').getContext('2d');
        chartContainer.canvas.parentElement.innerHTML = '<p style="color: red;">Não foi possível carregar os dados para o gráfico.</p>';
    }
}

// Popula os filtros com dados únicos do servidor
function populateFilters() {
    const prestadorFilter = document.getElementById('prestadorFilter');
    const profissaoFilter = document.getElementById('profissaoFilter');

    // Extrai nomes e profissões únicos
    const uniquePrestadores = [...new Set(allFeedbacks.map(fb => fb.nome_prestador))];
    const uniqueProfissoes = [...new Set(allFeedbacks.map(fb => fb.profissao))];

    // Popula filtro de prestadores
    uniquePrestadores.forEach(prestador => {
        const option = document.createElement('option');
        option.value = prestador;
        option.textContent = prestador;
        prestadorFilter.appendChild(option);
    });

    // Popula filtro de profissões
    uniqueProfissoes.forEach(profissao => {
        const option = document.createElement('option');
        option.value = profissao;
        option.textContent = profissao;
        profissaoFilter.appendChild(option);
    });
}

// Configura os listeners para os filtros
function setupEventListeners() {
    document.getElementById('prestadorFilter').addEventListener('change', renderChart);
    document.getElementById('profissaoFilter').addEventListener('change', renderChart);
}

// Agrega os dados com base nos filtros selecionados
function getAggregatedData() {
    const selectedPrestador = document.getElementById('prestadorFilter').value;
    const selectedProfissao = document.getElementById('profissaoFilter').value;

    const filteredFeedbacks = allFeedbacks.filter(fb => {
        const prestadorMatch = (selectedPrestador === 'all' || fb.nome_prestador === selectedPrestador);
        const profissaoMatch = (selectedProfissao === 'all' || fb.profissao === selectedProfissao);
        return prestadorMatch && profissaoMatch;
    });

    const counts = [0, 0, 0, 0, 0]; // Índices 0 a 4 para notas 1 a 5
    filteredFeedbacks.forEach(fb => {
        const nota = parseInt(fb.nota);
        if (nota >= 1 && nota <= 5) {
            counts[nota - 1]++;
        }
    });
    return counts;
}

// Renderiza ou atualiza o gráfico
function renderChart() {
    const ctx = document.getElementById('feedbackChart').getContext('2d');
    const aggregatedData = getAggregatedData();

    const chartData = {
        labels: ['1 ⭐', '2 ⭐⭐', '3 ⭐⭐⭐', '4 ⭐⭐⭐⭐', '5 ⭐⭐⭐⭐⭐'],
        datasets: [{
            label: 'Quantidade de Feedbacks',
            data: aggregatedData,
            backgroundColor: [
                'rgba(239, 68, 68, 0.6)',
                'rgba(249, 115, 22, 0.6)',
                'rgba(234, 179, 8, 0.6)',
                'rgba(59, 130, 246, 0.6)',
                'rgba(34, 197, 94, 0.6)'
            ],
            borderColor: [
                '#b91c1c',
                '#d97706',
                '#ca8a04',
                '#2563eb',
                '#15803d'
            ],
            borderWidth: 1.5,
            borderRadius: 5
        }]
    };

    const config = {
        type: 'bar',
        data: chartData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Quantidade de Avaliações'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    };

    if (feedbackChart) {
        feedbackChart.destroy();
    }
    feedbackChart = new Chart(ctx, config);
}

// Inicia o processo quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', initializeStatistics);