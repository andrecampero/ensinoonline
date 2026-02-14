<template>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-4">Top Cursos (Inscrições)</h5>
                <div style="height: 20em;">
                    <canvas ref="topCursosChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-4">Top Alunos (Matrículas)</h5>
                <div style="height: 20em;">
                    <canvas ref="topAlunosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';

export default defineComponent({
    name: 'DashboardCharts',
    props: {
        topCursos: {
            type: Array,
            required: true
        },
        topAlunos: {
            type: Array,
            required: true
        }
    },
    setup(props) {
        const topCursosChart = ref(null);
        const topAlunosChart = ref(null);

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        };

        onMounted(() => {
            // Render Top Cursos Chart
            new Chart(topCursosChart.value, {
                type: 'bar',
                data: {
                    labels: props.topCursos.map(c => c.titulo),
                    datasets: [{
                        label: 'Nº de Matrículas',
                        data: props.topCursos.map(c => c.matriculas_count),
                        backgroundColor: '#5a5a5a',
                        borderRadius: 6,
                        barThickness: 30
                    }]
                },
                options: commonOptions
            });

            // Render Top Alunos Chart
            new Chart(topAlunosChart.value, {
                type: 'bar',
                data: {
                    labels: props.topAlunos.map(a => a.name),
                    datasets: [{
                        label: 'Nº de Cursos',
                        data: props.topAlunos.map(a => a.matriculas_count),
                        backgroundColor: 'rgba(90, 90, 90, 0.5)',
                        borderColor: '#5a5a5a',
                        borderWidth: 1,
                        borderRadius: 6,
                        barThickness: 30
                    }]
                },
                options: commonOptions
            });
        });

        return {
            topCursosChart,
            topAlunosChart
        };
    }
});
</script>
