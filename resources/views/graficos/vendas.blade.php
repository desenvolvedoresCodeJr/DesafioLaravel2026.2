<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-4 mb-6">
                <h1 class="text-3xl font-bold">Gráfico - Vendas Realizadas</h1>
                <a href="{{ route('vendas.index') }}" class="ml-auto bg-[#111fb4] text-white text-lg font-semibold rounded-lg px-8 py-3">Histórico</a>
            </div>

            <p class="text-center text-lg mb-4">Vendas Realizadas por Mês</p>
            <div class="border border-[#111fb4] rounded-2xl p-6">
                <canvas id="grafico" height="110"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('grafico'), {
            type: 'line',
            data: {
                labels: @js($labels),
                datasets: [{
                    label: 'Vendas',
                    data: @js($valores),
                    borderColor: '#111fb4',
                    backgroundColor: '#111fb4',
                    pointRadius: 5,
                    tension: 0
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    </script>
</x-app-layout>