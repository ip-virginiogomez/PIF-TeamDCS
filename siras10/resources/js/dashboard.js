document.addEventListener('DOMContentLoaded', function () {
    initCuposChart();
    initInmunizacionChart('inmunizacionChart', window.inmunizacionData, '/alumnos');
    initInmunizacionChart('inmunizacionDocenteChart', window.docenteInmunizacionData, '/docentes');
    initOcupacionChart();
});

function initCuposChart() {
    const chartElement = document.getElementById('cuposPorCarreraChart');
    if (!chartElement) return;

    const ctx = chartElement.getContext('2d');
    const mainColor = window.dashboardMainColor || '#0369a1';
    const data = {
        labels: window.cuposPorCarreraLabels || [],
        datasets: [{
            label: 'Cupos ofertados',
            data: window.cuposPorCarreraData || [],
            backgroundColor: mainColor + '40',
            borderColor: '#0369a1',
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
}

function initInmunizacionChart(canvasId, dataValues, baseUrl) {
    const chartElement = document.getElementById(canvasId);
    if (!chartElement) return;

    const ctx = chartElement.getContext('2d');
    const values = dataValues || { vigentes: 0, vencidas: 0, sin_vacunas: 0 };

    // Datos: [Vigentes, Vencidas, Sin Vacunas]
    const data = {
        labels: ['Vigentes', 'Vencidas', 'Sin Vacunas'],
        datasets: [{
            data: [values.vigentes, values.vencidas, values.sin_vacunas],
            backgroundColor: [
                '#10b981', // Green-500 (Vigentes)
                '#ef4444', // Red-500 (Vencidas)
                '#9ca3af'  // Gray-400 (Sin Vacunas)
            ],
            hoverOffset: 4
        }]
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            let value = context.raw;
                            let total = context.chart._metasets[context.datasetIndex].total;
                            let percentage = Math.round((value / total) * 100) + '%';
                            return label + value + ' (' + percentage + ')';
                        }
                    }
                }
            },
            onClick: (event, elements, chart) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const label = chart.data.labels[index];
                    if (label === 'Vencidas' || label === 'Sin Vacunas') {
                        // Redirigir a la lista filtrada
                        let filterValue = label.toLowerCase().replace(' ', '_');
                        window.location.href = baseUrl + '?estado_vacuna=' + filterValue;
                    }
                }
            }
        }
    });
}

function initOcupacionChart() {
    const chartElement = document.getElementById('ocupacionChart');
    if (!chartElement) return;

    const ctx = chartElement.getContext('2d');
    const labels = window.ocupacionLabels || [];
    const dataTotal = window.ocupacionTotal || [];
    const dataAsignados = window.ocupacionAsignada || [];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Cupos Totales',
                    data: dataTotal,
                    backgroundColor: '#e5e7eb', // Gray-200
                    borderColor: '#9ca3af', // Gray-400
                    borderWidth: 1
                },
                {
                    label: 'Alumnos Asignados',
                    data: dataAsignados,
                    backgroundColor: '#0ea5e9', // Sky-500
                    borderColor: '#0284c7', // Sky-600
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            }
        }
    });
}


