@extends("layouts.plantilla")

@section("titulomain")
Dashboard — Análisis de Seguridad
@endsection

@section("contenido")

{{-- Chart.js desde CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
    .dash-wrap { padding: 24px; color: #e2e8f0; font-family: 'Roboto', sans-serif; }

    /* KPIs */
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
    .kpi-card {
        background: linear-gradient(135deg, rgba(11,23,40,0.96), rgba(7,18,35,0.96));
        border: 1px solid rgba(56,114,191,0.22);
        border-radius: 16px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.4); }
    .kpi-card::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; background: var(--kpi-color, #38bdf8); }
    .kpi-label { font-size:.78rem; color:#94a3b8; text-transform:uppercase; letter-spacing:1px; margin-bottom: 8px; font-weight: 600; }
    .kpi-value { font-size: 2.2rem; font-weight: 700; color: var(--kpi-color, #38bdf8); line-height: 1; }
    .kpi-icon { position:absolute; top: 18px; right: 18px; font-size: 1.6rem; opacity: .35; }

    /* Charts grid */
    .charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 28px; }
    .charts-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 28px; }
    .chart-card {
        background: rgba(11,23,40,0.96);
        border: 1px solid rgba(56,114,191,0.22);
        border-radius: 16px;
        padding: 20px;
    }
    .chart-card h3 { color:#e2e8f0; font-size: 1.05rem; font-weight: 600; margin: 0 0 16px; display:flex; align-items:center; gap:8px; }
    .chart-canvas-wrap { position: relative; height: 320px; }

    /* Top comunas table */
    .top-table { width: 100%; border-collapse: collapse; background: rgba(11,23,40,0.96); border-radius: 8px; overflow: hidden; }
    .top-table th { text-align: left; color: #94a3b8; font-size: .78rem; text-transform: uppercase; letter-spacing: 1px; padding: 12px 16px; border-bottom: 1px solid rgba(56,114,191,0.22); background: rgba(7,18,35,0.96); font-weight: 600; }
    .top-table td { padding: 12px 16px; border-bottom: 1px solid rgba(56,114,191,0.1); color: #e2e8f0; }
    .top-table td strong { color: #ef4444; font-size: 1.1em; }
    .top-table tr:last-child td { border-bottom: none; }
    .top-table tr:hover { background: rgba(56,114,191,0.05); }
    .badge-nivel { display:inline-block; padding: 3px 10px; border-radius: 999px; font-size: .72rem; font-weight: 700; color: #07141f; }

    @media (max-width: 1100px) {
        .charts-grid, .charts-grid-3 { grid-template-columns: 1fr; }
    }
</style>

<div class="dash-wrap">

    {{-- ════════════ KPIs ════════════ --}}
    <div class="kpi-grid">

        <div class="kpi-card" style="--kpi-color:#ef4444;">
            <div class="kpi-icon">⚠️</div>
            <div class="kpi-label">Total Delitos</div>
            <div class="kpi-value">{{ number_format($kpis['total_delitos']) }}</div>
        </div>

        <div class="kpi-card" style="--kpi-color:#3b82f6;">
            <div class="kpi-icon">👮</div>
            <div class="kpi-label">Estaciones de Policía</div>
            <div class="kpi-value">{{ $kpis['total_estaciones'] }}</div>
        </div>

        <div class="kpi-card" style="--kpi-color:#a855f7;">
            <div class="kpi-icon">👥</div>
            <div class="kpi-label">Usuarios sistema</div>
            <div class="kpi-value">{{ $kpis['total_usuarios'] }}</div>
        </div>

        <div class="kpi-card" style="--kpi-color:#10b981;">
            <div class="kpi-icon">📅</div>
            <div class="kpi-label">Delitos este mes</div>
            <div class="kpi-value">{{ $kpis['delitos_mes_actual'] }}</div>
        </div>

        <div class="kpi-card" style="--kpi-color:#f59e0b;">
            <div class="kpi-icon">📈</div>
            <div class="kpi-label">Promedio mensual</div>
            <div class="kpi-value">{{ $kpis['promedio_delitos_mes'] }}</div>
        </div>

    </div>

    {{-- ════════════ Fila 1: Bar Zonas + Pie Niveles ════════════ --}}
    <div class="charts-grid">
        <div class="chart-card">
            <h3>📊 Delitos por zona</h3>
            <div class="chart-canvas-wrap">
                <canvas id="chartZonas"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h3>🎯 Distribución de niveles de riesgo</h3>
            <div class="chart-canvas-wrap">
                <canvas id="chartNiveles"></canvas>
            </div>
        </div>
    </div>

    {{-- ════════════ Fila 2: Línea por mes (full width) ════════════ --}}
    <div class="chart-card" style="margin-bottom: 28px;">
        <h3>📈 Tendencia de delitos en los últimos 12 meses</h3>
        <div class="chart-canvas-wrap">
            <canvas id="chartTendencia"></canvas>
        </div>
    </div>

    {{-- ════════════ Fila 3: Pie Tipos + Bar Gravedad ════════════ --}}
    <div class="charts-grid">
        <div class="chart-card">
            <h3>🔍 Tipos de delito</h3>
            <div class="chart-canvas-wrap">
                <canvas id="chartTipos"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h3>⚖️ Por gravedad</h3>
            <div class="chart-canvas-wrap">
                <canvas id="chartGravedad"></canvas>
            </div>
        </div>
    </div>

    {{-- ════════════ Fila 4: Bar Día semana + Top 5 Zonas ════════════ --}}
    <div class="charts-grid">
        <div class="chart-card">
            <h3>📅 Delitos por día de la semana</h3>
            <div class="chart-canvas-wrap">
                <canvas id="chartDias"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h3>🏆 Top 5 zonas con más delitos</h3>
            <div class="chart-canvas-wrap">
                <table class="top-table">
                    <thead>
                        <tr>
                            <th>Zona</th>
                            <th>Delitos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topZonas as $zona)
                        <tr>
                            <td>{{ $zona->zona }}</td>
                            <td><strong>{{ $zona->total }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
// ════════════ Datos del backend ════════════
const dataComunas = @json($delitosPorZona);
const dataTipos = @json($delitosPorTipo);
const dataMeses = @json($delitosPorMes);
const dataNiveles = @json($nivelesRiesgo);
const topZonas = @json($topZonas);
const dataGravedad = @json($porGravedad);
const dataDias = @json($delitosPorDia);

// Chart.js global config
Chart.defaults.color = '#94a3b8';
Chart.defaults.font.family = 'Roboto, sans-serif';
Chart.defaults.borderColor = 'rgba(56,114,191,0.16)';

// ════════════ 1. Delitos por zona (Bar) ════════════
new Chart(document.getElementById('chartZonas'), {
    type: 'bar',
    data: {
        labels: dataComunas.map(c => c.nombre),
        datasets: [{
            label: 'Delitos',
            data: dataComunas.map(c => c.total),
            backgroundColor: 'rgba(56, 189, 248, 0.7)',
            borderColor: '#38bdf8',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(56,114,191,0.1)' } },
            x: { grid: { display: false }, ticks: { maxRotation: 45, minRotation: 45 } }
        }
    }
});

// ════════════ 2. Niveles de riesgo (Doughnut) ════════════
new Chart(document.getElementById('chartNiveles'), {
    type: 'doughnut',
    data: {
        labels: dataNiveles.map(n => n.nivel),
        datasets: [{
            data: dataNiveles.map(n => n.total),
            backgroundColor: dataNiveles.map(n => n.color),
            borderWidth: 2,
            borderColor: '#0b1725',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 16 } }
        }
    }
});

// ════════════ 3. Tendencia mensual (Line) ════════════
new Chart(document.getElementById('chartTendencia'), {
    type: 'line',
    data: {
        labels: dataMeses.map(m => m.mes),
        datasets: [{
            label: 'Delitos registrados',
            data: dataMeses.map(m => m.total),
            backgroundColor: 'rgba(239, 68, 68, 0.15)',
            borderColor: '#ef4444',
            borderWidth: 2,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#ef4444',
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(56,114,191,0.1)' } },
            x: { grid: { display: false } }
        }
    }
});

// ════════════ 4. Tipos de delito (Pie) ════════════
new Chart(document.getElementById('chartTipos'), {
    type: 'pie',
    data: {
        labels: dataTipos.map(t => t.tipo),
        datasets: [{
            data: dataTipos.map(t => t.total),
            backgroundColor: [
                '#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4',
                '#3b82f6', '#8b5cf6', '#ec4899', '#64748b', '#374151'
            ],
            borderWidth: 2,
            borderColor: '#0b1725',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 8, boxWidth: 12 } }
        }
    }
});

// ════════════ 5. Por gravedad (Bar horizontal) ════════════
new Chart(document.getElementById('chartGravedad'), {
    type: 'bar',
    data: {
        labels: dataGravedad.map(g => g.label),
        datasets: [{
            label: 'Delitos',
            data: dataGravedad.map(g => g.total),
            backgroundColor: [
                'rgba(34, 197, 94, 0.7)',   // Verde - Leve
                'rgba(245, 158, 11, 0.7)',  // Amarillo - Medio
                'rgba(239, 68, 68, 0.7)'    // Rojo - Grave
            ],
            borderColor: ['#22c55e', '#f59e0b', '#ef4444'],
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, grid: { color: 'rgba(56,114,191,0.1)' } },
            y: { grid: { display: false } }
        }
    }
});

// ════════════ 6. Delitos por día de la semana (Bar) ════════════
new Chart(document.getElementById('chartDias'), {
    type: 'bar',
    data: {
        labels: dataDias.map(d => d.dia),
        datasets: [{
            label: 'Delitos',
            data: dataDias.map(d => d.total),
            backgroundColor: 'rgba(168, 85, 247, 0.7)',
            borderColor: '#a855f7',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(56,114,191,0.1)' } },
            x: { grid: { display: false } }
        }
    }
});
new Chart(document.getElementById('chartZonas'), {
    type: 'bar',
    data: {
        labels: dataComunas.map(c => c.nombre),
        datasets: [{
            label: 'Delitos',
            data: dataComunas.map(c => c.total),
            backgroundColor: 'rgba(56, 189, 248, 0.7)',
            borderColor: '#38bdf8',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(56,114,191,0.1)' } },
            x: { grid: { display: false }, ticks: { maxRotation: 45, minRotation: 45 } }
        }
    }
});
</script>

@endsection
