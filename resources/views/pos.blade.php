@extends('layouts.app')

@section('title', 'Punto de Venta - ERP Portugal')

@section('content')
<div x-data="{
    productosBase: {{ json_encode($productos) }},
    lineas: [{ id: '', cantidad: 1, precio: 0.00 }],
    boletaProcesada: null,
    fechaBoleta: '',
    openBoletaModal: false,
    errorMessage: '',

    agregarLinea() {
        this.lineas.push({ id: '', cantidad: 1, precio: 0.00 });
    },
    removerLinea(index) {
        if (this.lineas.length > 1) {
            this.lineas.splice(index, 1);
        }
    },
    actualizarPrecio(index) {
        const prod = this.productosBase.find(p => p.id == this.lineas[index].id);
        this.lineas[index].precio = prod ? parseFloat(prod.precio_view) : 0.00;
    },
    get subtotal() {
        return this.lineas.reduce((sum, item) => sum + (item.cantidad * item.precio), 0);
    },
    get igv() {
        return this.subtotal * 0.18;
    },
    get total() {
        return this.subtotal + this.igv;
    },
    procesarVenta() {
        this.errorMessage = '';
        if (this.lineas.some(l => !l.id)) {
            this.errorMessage = 'Por favor, seleccione un producto válido para cada línea.';
            return;
        }

        fetch('{{ route('pos.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ productos: this.lineas })
        })
        .then(res => res.json().then(data => ({ status: res.status, data })))
        .then(obj => {
            if (obj.status !== 200) {
                this.errorMessage = obj.data.message || 'Error crítico al procesar la venta.';
                return;
            }
            this.boletaProcesada = obj.data.boleta;
            this.fechaBoleta = obj.data.fecha_formateada;
            this.openBoletaModal = true;
            
            this.lineas = [{ id: '', cantidad: 1, precio: 0.00 }];
        })
        .catch(() => this.errorMessage = 'No se pudo conectar con el servidor.');
    }
}" class="max-w-7xl mx-auto">

    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Punto de Venta</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Generación rápida de órdenes y facturación sincronizada con inventario</p>
    </div>

    <div x-show="errorMessage" class="bg-red-50 text-red-600 p-4 rounded-xl text-xs font-semibold border border-red-100 mb-6" style="display: none;" x-text="errorMessage"></div>

    <!-- CONTENEDOR RESPONSIVE: Stacking en móvil, 2 columnas en desktop -->
    <div class="flex flex-col lg:flex-row gap-6 items-start w-full">
        
        <!-- COLUMNA IZQUIERDA: DETALLE DE VENTA -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-6 w-full lg:flex-1 min-w-0">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <div class="flex items-center gap-2 text-slate-800 font-bold text-sm">
                    <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    Detalle de Venta
                </div>
                <button @click="agregarLinea()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-1.5 px-3.5 rounded-xl text-xs transition-all flex items-center gap-1">
                    + Añadir Línea
                </button>
            </div>

            <!-- FILAS RESPONSIVE DE PRODUCTOS -->
            <div class="space-y-3">
                <template x-for="(linea, index) in lineas" :key="index">
                    <div class="bg-slate-50/60 p-3 sm:p-4 border border-slate-100 rounded-xl flex flex-col sm:flex-row gap-3 sm:gap-4 sm:items-end justify-between">
                        
                        <!-- SELECCIÓN DE PRODUCTO -->
                        <div class="flex-1 min-w-0">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Producto</label>
                            <select x-model="linea.id" @change="actualizarPrecio(index)" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none font-medium text-slate-700">
                                <option value="">Seleccione un producto...</option>
                                <template x-for="p in productosBase" :key="p.id">
                                    <option :value="p.id" x-text="p.nombre + ' (Stock: ' + p.stock_actual + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <div class="flex items-end gap-3 w-full sm:w-auto">
                            <!-- INPUT CANTIDAD -->
                            <div class="w-20 sm:w-24 shrink-0">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider text-center">Cant.</label>
                                <input type="number" x-model.number="linea.cantidad" min="1" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-xs text-center focus:outline-none font-bold text-slate-700">
                            </div>

                            <!-- MOSTRAR PRECIO UNITARIO -->
                            <div class="flex-1 sm:w-28 shrink-0">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider text-center">P. Unitario ($)</label>
                                <div class="w-full bg-slate-100/70 text-slate-600 border border-slate-200 rounded-lg py-2 text-xs text-center font-mono font-bold" x-text="linea.precio.toFixed(2)"></div>
                            </div>

                            <!-- ELIMINAR LÍNEA -->
                            <div class="pb-1 shrink-0">
                                <button type="button" @click="removerLinea(index)" class="text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all p-2 block" title="Eliminar fila">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>

        <!-- COLUMNA DERECHA: CARD RESUMEN DE ORDEN -->
        <div class="bg-[#051c11] text-white rounded-2xl shadow-lg border border-emerald-950 w-full lg:w-80 lg:min-w-[320px] shrink-0 flex flex-col justify-between">
            <div class="p-6 space-y-5">
                <div class="flex items-center gap-2 text-slate-300 text-xs font-bold uppercase tracking-wider">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    Resumen de Orden
                </div>

                <div class="space-y-3 text-xs font-semibold text-emerald-100/60 border-b border-emerald-900/40 pb-4">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-mono text-white font-bold" x-text="'$' + subtotal.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>IGV (18%)</span>
                        <span class="font-mono text-white font-bold" x-text="'$' + igv.toFixed(2)"></span>
                    </div>
                </div>

                <div class="flex justify-between items-baseline pt-1">
                    <span class="text-base font-bold text-slate-200">Total</span>
                    <span class="text-2xl sm:text-3xl font-black font-mono text-[#10b981]" x-text="'$' + total.toFixed(2)"></span>
                </div>
            </div>

            <!-- Botón inferior empotrado -->
            <button @click="procesarVenta()" class="w-full bg-[#1a3828] hover:bg-[#234b37] text-white font-bold py-4 px-6 text-xs transition-all flex items-center justify-center gap-2 tracking-wider uppercase border-t border-emerald-950/60 rounded-b-2xl">
                Procesar Pago
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </button>
        </div>
    </div>

    <!-- MODAL BOLETA ELECTRÓNICA -->
    <div x-show="openBoletaModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition style="display: none;">
        <div class="bg-white w-full max-w-[620px] rounded-[1.5rem] shadow-2xl relative flex flex-col max-h-[85vh] overflow-hidden" @click.away="openBoletaModal = false">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-emerald-50/40 shrink-0">
                <div class="flex items-center gap-2 text-emerald-600 text-sm font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Venta procesada y stock actualizado
                </div>
                <button @click="openBoletaModal = false" class="text-slate-400 hover:text-slate-600 text-xs font-bold transition-all focus:outline-none">Cerrar ✕</button>
            </div>

            <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-[#051c11] text-white p-2 font-black rounded-xl text-sm tracking-tighter">CP</span>
                            <div>
                                <h4 class="text-sm font-black text-slate-900 leading-tight">Corporación Portugal</h4>
                                <p class="text-[10px] text-slate-400 font-medium">Soluciones Industriales S.A.C.</p>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">RUC: 20546789123</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-sm font-black text-slate-400 tracking-widest uppercase">Boleta Electrónica</h3>
                        <span class="text-xs font-mono font-black text-slate-900 mt-1 block" x-text="boletaProcesada ? boletaProcesada.numero_boleta : ''"></span>
                        <p class="text-[10px] text-slate-400 font-medium mt-1" x-text="fechaBoleta"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-slate-50/70 border border-slate-100 rounded-xl p-4 text-xs">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Cliente</span>
                        <strong class="text-slate-800 font-bold">Cliente Mostrador</strong>
                        <p class="text-slate-400 font-medium mt-0.5">DNI/CE: Varios</p>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Condición de Pago</span>
                        <strong class="text-slate-800 font-bold">Contado / Efectivo</strong>
                        <p class="text-slate-400 font-medium mt-0.5">Moneda: USD ($)</p>
                    </div>
                </div>

                <div class="border border-slate-100 rounded-xl overflow-hidden">
                    <table class="w-full border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="py-2.5 px-4 text-center w-16">Cant.</th>
                                <th class="py-2.5 px-4 text-center">Descripción</th>
                                <th class="py-2.5 px-4 text-center w-28">P. Unitario</th>
                                <th class="py-2.5 px-4 text-center w-28">Importe</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/60 font-medium text-slate-600">
                            <template x-if="boletaProcesada">
                                <template x-for="d in boletaProcesada.detalles" :key="d.id">
                                    <tr class="border-b border-slate-50">
                                        <td class="py-3 px-4 text-center text-slate-400" x-text="d.cantidad"></td>
                                        <td class="py-3 px-4 font-bold text-slate-800 text-center" x-text="d.producto.nombre"></td>
                                        <td class="py-3 px-4 text-center text-slate-500 font-mono" x-text="'$' + parseFloat(d.precio_unitario).toFixed(2)"></td>
                                        <td class="py-3 px-4 text-center text-slate-900 font-bold font-mono" x-text="'$' + parseFloat(d.subtotal).toFixed(2)"></td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                <button @click="window.print()" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-1.5">
                    Imprimir Boleta
                </button>
                <template x-if="boletaProcesada">
                    <a :href="'/pos/boleta/' + boletaProcesada.id + '/pdf'" class="bg-[#051c11] hover:bg-[#0c2a1c] text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-1.5 no-underline">
                        Descargar PDF
                    </a>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection