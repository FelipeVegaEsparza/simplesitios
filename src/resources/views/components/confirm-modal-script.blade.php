{{--
    Script helper para el modal de confirmación
    
    Este script escucha el evento 'confirm-modal-{id}' y envía el formulario correspondiente.
    Uso:
    1. Incluir el modal: @include('components.confirm-modal', ['id' => 'mi-modal', ...])
    2. Incluir este script: @include('components.confirm-modal-script')
    3. En el botón: @click="$dispatch('open-modal', 'mi-modal')"
    4. En el formulario: x-ref="mi-modal-form" data-modal="mi-modal"
--}}

<script>
document.addEventListener('alpine:init', () => {
    // Escuchar eventos de confirmación de modales
    @foreach($modals ?? ['modal-delete'] as $modalId)
    window.addEventListener('confirm-modal-{{ $modalId }}', () => {
        const form = document.querySelector('[data-modal="{{ $modalId }}"]');
        if (form) {
            form.submit();
        }
    });
    @endforeach
});

// Función global para abrir modales
window.openConfirmModal = function(modalId) {
    window.dispatchEvent(new CustomEvent('open-modal', { detail: modalId }));
};
</script>
