{{--
    Componente de Modal de Confirmación
    
    Uso:
    @include('components.confirm-modal', [
        'id' => 'modal-eliminar-cliente',
        'title' => 'Eliminar Cliente',
        'message' => '¿Estás seguro de eliminar este cliente? Esta acción no se puede deshacer.',
        'confirmText' => 'Eliminar',
        'cancelText' => 'Cancelar'
    ])
    
    Luego en el botón:
    <button @click="$dispatch('open-modal', 'modal-eliminar-cliente')" ...>
    
    Y en el formulario, agregar:
    x-ref="form-eliminar-cliente"
--}}

<div x-data="{ 
    show: false, 
    currentModal: null,
    init() {
        window.addEventListener('open-modal', (e) => {
            if (e.detail === '{{ $id }}') {
                this.show = true;
                this.currentModal = '{{ $id }}';
            }
        });
        
        window.addEventListener('close-modal', () => {
            this.show = false;
            this.currentModal = null;
        });
    }
}" 
     x-show="show" 
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     @keydown.escape.window="show = false">
    
    <!-- Backdrop -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm"
         @click="show = false">
    </div>
    
    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-xl text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
             style="background-color: var(--bg-primary); border: 1px solid var(--border-color);"
             @click.away="show = false">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b" style="border-color: var(--border-color);">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold" style="color: var(--text-primary);">{{ $title ?? 'Confirmar acción' }}</h3>
                </div>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-4">
                <p class="text-sm" style="color: var(--text-secondary);">
                    {{ $message ?? '¿Estás seguro de realizar esta acción?' }}
                </p>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 flex flex-row-reverse gap-3" style="background-color: var(--bg-secondary);">
                <button type="button"
                        @click="$dispatch('confirm-modal-{{ $id }}'); show = false"
                        class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    {{ $confirmText ?? 'Confirmar' }}
                </button>
                <button type="button"
                        @click="show = false"
                        class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-medium transition-colors"
                        style="background-color: var(--bg-primary); color: var(--text-secondary); border: 1px solid var(--border-color);"
                        onmouseover="this.style.backgroundColor='var(--bg-tertiary)'"
                        onmouseout="this.style.backgroundColor='var(--bg-primary)'">
                    {{ $cancelText ?? 'Cancelar' }}
                </button>
            </div>
        </div>
    </div>
</div>
