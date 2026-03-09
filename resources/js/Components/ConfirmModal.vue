<!-- resources/js/Components/ConfirmModal.vue -->
<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="show" class="modal-overlay">

        <!-- Fondo oscuro, click fuera cancela -->
        <div class="modal-backdrop" @click="$emit('cancel')" />

        <!-- Tarjeta -->
        <div class="modal-card">

          <!-- Ícono de advertencia -->
          <div class="modal-icon">
            <Trash2 :size="24" color="white" />
          </div>

          <!-- Texto -->
          <h2 class="modal-title">¿Eliminar link?</h2>
          <p class="modal-desc">
            Esta acción no se puede deshacer.
            El link <span class="modal-highlight">"{{ linkLabel }}"</span> será eliminado permanentemente.
          </p>

          <!-- Botones -->
          <div class="modal-actions">
            <button @click="$emit('cancel')" class="btn-cancel">
              <X :size="15" />
              Cancelar
            </button>
            <button @click="$emit('confirm')" class="btn-confirm">
              <Trash2 :size="15" />
              Sí, eliminar
            </button>
          </div>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { Trash2, X } from 'lucide-vue-next'

defineProps({
  show:      { type: Boolean, required: true },
  linkLabel: { type: String,  default: '' },
})

defineEmits(['confirm', 'cancel'])
</script>

<style scoped>
.modal-overlay {
  position: fixed; inset: 0; z-index: 100;
  display: flex; align-items: center; justify-content: center;
  padding: 16px;
}
.modal-backdrop {
  position: absolute; inset: 0;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(6px);
}
.modal-card {
  position: relative; z-index: 10;
  width: 100%; max-width: 380px;
  background: #13131f;
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 24px;
  padding: 32px 28px;
  text-align: center;
  box-shadow: 0 24px 60px rgba(0, 0, 0, 0.6);
}
.modal-icon {
  width: 56px; height: 56px;
  background: linear-gradient(135deg, #ef4444, #dc2626);
  border-radius: 16px;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 20px;
  box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
}
.modal-title {
  font-size: 1.2rem; font-weight: 900; color: white;
  margin-bottom: 10px;
}
.modal-desc {
  font-size: 0.875rem; color: rgba(255,255,255,0.45);
  line-height: 1.6; margin-bottom: 28px;
}
.modal-highlight {
  color: rgba(255,255,255,0.75); font-weight: 600;
}
.modal-actions {
  display: flex; gap: 10px;
}
.btn-cancel {
  flex: 1; padding: 11px;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 12px;
  font-size: 0.875rem; font-weight: 700; color: rgba(255,255,255,0.6);
  cursor: pointer; font-family: inherit;
  display: flex; align-items: center; justify-content: center; gap: 6px;
  transition: all 0.2s;
}
.btn-cancel:hover {
  background: rgba(255,255,255,0.1);
  color: white;
}
.btn-confirm {
  flex: 1; padding: 11px;
  background: linear-gradient(135deg, #ef4444, #dc2626);
  border: none; border-radius: 12px;
  font-size: 0.875rem; font-weight: 700; color: white;
  cursor: pointer; font-family: inherit;
  display: flex; align-items: center; justify-content: center; gap: 6px;
  box-shadow: 0 4px 16px rgba(239, 68, 68, 0.35);
  transition: all 0.2s;
}
.btn-confirm:hover {
  transform: translateY(-1px);
  box-shadow: 0 8px 24px rgba(239, 68, 68, 0.5);
}

/* Animación del modal */
.modal-enter-from { opacity: 0; }
.modal-leave-to   { opacity: 0; }
.modal-enter-from .modal-card { transform: scale(0.9) translateY(10px); }
.modal-leave-to   .modal-card { transform: scale(0.9) translateY(10px); }
.modal-enter-active { transition: opacity 0.25s ease; }
.modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-active .modal-card,
.modal-leave-active .modal-card { transition: transform 0.25s ease; }
</style>