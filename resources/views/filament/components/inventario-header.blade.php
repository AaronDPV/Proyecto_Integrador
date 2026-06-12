<x-filament-widgets::widget>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
        
        <div class="flex items-start gap-4 p-4 bg-[#fffdfd] border border-gray-100 border-l-4 border-l-red-600 rounded-xl shadow-sm">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-200 shrink-0 font-bold text-sm">
                !
            </div>
            <div class="flex flex-col">
                <span class="text-base font-bold text-gray-800 tracking-tight">Stock Crítico</span>
                <span class="text-xs font-semibold text-red-600 mt-0.5">
                    {{ $stockCritico }} artículo(s) requiere(n) atención inmediata
                </span>
            </div>
        </div>

        <div class="flex items-start gap-4 p-4 bg-[#fffbeb] border border-gray-100 border-l-4 border-l-orange-500 rounded-xl shadow-sm">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-amber-50 text-orange-600 border border-amber-200 shrink-0 font-bold text-sm">
                !
            </div>
            <div class="flex flex-col">
                <span class="text-base font-bold text-gray-800 tracking-tight">Reabastecimiento</span>
                <span class="text-xs font-semibold text-orange-700 mt-0.5">
                    {{ $reabastecimiento }} artículo(s) por debajo del mínimo
                </span>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>