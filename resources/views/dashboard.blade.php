@extends('layouts.app')

@section('title', 'ERP Portugal - Escritorio Principal')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="mb-6 sm:mb-8">
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Escritorio Principal</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Resumen de indicadores clave de rendimiento (KPIs)</p>
    </div>

    <!-- REJILLA DE CARDS DE INDICADORES REALES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        
        <!-- INDICADOR 1: VENTAS TOTALES -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase tracking-wider">
                    Finanzas
                </span>
            </div>
            <div class="mt-4">
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Ventas Totales</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 block mt-1">${{ number_format($ventasTotales, 2) }}</span>
            </div>
        </div>

        <!-- INDICADOR 2: UNIDADES PROCESADAS -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-blue-50 text-blue-500 rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider">
                    Volumen
                </span>
            </div>
            <div class="mt-4">
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Unidades Vendidas</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 block mt-1">{{ number_format($unidadesVendidas) }} u.</span>
            </div>
        </div>

        <!-- INDICADOR 3: ÓRDENES REALIZADAS -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-indigo-50 text-indigo-500 rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg uppercase tracking-wider">
                    Despacho
                </span>
            </div>
            <div class="mt-4">
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Boletas Emitidas</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 block mt-1">{{ number_format($ordenesProcesadas) }}</span>
            </div>
        </div>

        <!-- INDICADOR 4: STOCK ALERTA CRÍTICA -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="p-3 {{ $stockBajo > 0 ? 'bg-red-50 text-red-500' : 'bg-slate-50 text-slate-400' }} rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold {{ $stockBajo > 0 ? 'text-red-600 bg-red-50' : 'text-slate-500 bg-slate-50' }} px-2 py-1 rounded-lg uppercase tracking-wider">
                    Inventario
                </span>
            </div>
            <div class="mt-4">
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Stock Crítico</span>
                <span class="text-xl sm:text-2xl font-black {{ $stockBajo > 0 ? 'text-red-600' : 'text-slate-900' }} block mt-1">{{ $stockBajo }}</span>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE GRÁFICOS DIARIOS ASOCIADOS A VENTAS -->
    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white p-4 sm:p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-4 tracking-tight">Evolución de Ingresos Monetarios Diarios</h3>
            <div class="h-64 sm:h-80">
                <canvas id="chartVentasReales"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const ctxVentas = document.getElementById('chartVentasReales').getContext('2d');
        new Chart(ctxVentas, {
            type: 'line',
            data: {
                labels: {!! json_encode($labelsGrafico) !!},
                datasets: [{
                    label: 'Ingresos ($)',
                    data: {!! json_encode($dataVentasGrafico) !!},
                    borderColor: '#10b981',
                    borderWidth: 3.5,
                    fill: true,
                    backgroundColor: 'rgba(16, 185, 129, 0.04)',
                    tension: 0.35,
                    pointBackgroundColor: '#10b981',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                    }
                }
            }
        });
    </script>
@endpush