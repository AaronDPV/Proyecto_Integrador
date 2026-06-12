<div class="flex items-start gap-3 p-2">
    <div class="flex items-center justify-center w-8 h-8 rounded-full border {{ $type === 'critico' ? 'border-red-200 bg-red-50 text-red-600' : 'border-amber-200 bg-amber-50 text-amber-600' }}">
        @if($type === 'critico')
            <span class="font-bold text-base">!</span>
        @else
            <span class="font-bold text-base">!</span>
        @endif
    </div>
    
    <div class="flex flex-col">
        <span class="text-sm font-bold text-gray-800 tracking-tight">{{ $title }}</span>
        <span class="text-xs font-medium text-gray-500 {{ $type === 'critico' ? 'text-red-600' : 'text-amber-700' }}">{{ $desc }}</span>
    </div>
</div>