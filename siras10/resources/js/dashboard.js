document.addEventListener('DOMContentLoaded', function () {
    const chartElement = document.getElementById('cuposPorCarreraChart');
    if (!chartElement) return; // Solo ejecutar si el canvas existe
    
    const ctx = chartElement.getContext('2d');
    const mainColor = window.dashboardMainColor || '#0369a1';
    const data = {
        labels: window.cuposPorCarreraLabels || [],
        datasets: [{
            label: 'Cupos ofertados',
            data: window.cuposPorCarreraData || [],
            backgroundColor: mainColor + '40', // 25% opacity
            borderColor: '#0369a1', // Color de borde fijo
            borderWidth: 2,
            borderRadius: 8,
            maxBarThickness: 40
        }]
    };
    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: mainColor, font: { weight: 'bold' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#e0e7ff' },
                    ticks: { color: mainColor, font: { weight: 'bold' } }
                }
            }
        }
    });
});
