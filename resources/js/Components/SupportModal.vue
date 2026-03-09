<!-- resources/js/Components/SupportModal.vue -->
<template>
  <!-- ① Overlay oscuro de fondo, cierra al hacer click fuera -->
  <Teleport to="body">
    <div
      v-if="show"
      class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
      <!-- Fondo semitransparente -->
      <div
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        @click="$emit('close')"
      />

      <!-- ② Tarjeta del modal (z-10 para estar encima del overlay) -->
      <div class="relative z-10 w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">

        <!-- Cabecera -->
        <div class="mb-6 flex items-center justify-between">
          <h2 class="text-xl font-bold text-gray-800">
            ☕ Apoyar a {{ creatorName }}
          </h2>
          <button
            @click="$emit('close')"
            class="rounded-full p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition"
          >
            ✕
          </button>
        </div>

        <!-- ③ Formulario con useForm de Inertia -->
        <form @submit.prevent="submit" class="space-y-4">

          <!-- Nombre -->
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
              Tu nombre
            </label>
            <input
              v-model="form.supporter_name"
              type="text"
              placeholder="Ej: Juan Pérez"
              class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm
                     focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
            />
            <!-- ④ Muestra error de validación si existe -->
            <p v-if="form.errors.supporter_name" class="mt-1 text-xs text-red-500">
              {{ form.errors.supporter_name }}
            </p>
          </div>

          <!-- Monto con botones rápidos -->
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
              Monto (1–100)
            </label>

            <!-- ⑤ Botones de selección rápida -->
            <div class="mb-2 flex gap-2">
              <button
                v-for="preset in [1, 5, 10, 25]"
                :key="preset"
                type="button"
                @click="form.amount = preset"
                :class="[
                  'flex-1 rounded-lg border py-1.5 text-sm font-semibold transition',
                  form.amount === preset
                    ? 'border-indigo-500 bg-indigo-500 text-white'
                    : 'border-gray-300 text-gray-600 hover:border-indigo-400'
                ]"
              >
                {{ preset }}
              </button>
            </div>

            <input
              v-model.number="form.amount"
              type="number"
              min="1"
              max="100"
              class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm
                     focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
            />
            <p v-if="form.errors.amount" class="mt-1 text-xs text-red-500">
              {{ form.errors.amount }}
            </p>
          </div>

          <!-- Mensaje -->
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
              Mensaje (opcional)
            </label>
            <textarea
              v-model="form.message"
              rows="3"
              placeholder="Escribe algo bonito..."
              class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm
                     focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
            />
            <p v-if="form.errors.message" class="mt-1 text-xs text-red-500">
              {{ form.errors.message }}
            </p>
          </div>

          <!-- Botón submit -->
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-semibold
                   text-white transition hover:bg-indigo-700 disabled:opacity-50"
          >
            {{ form.processing ? 'Enviando...' : '🎉 Enviar apoyo' }}
          </button>

        </form>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

// ① Props que recibe el componente desde el padre
const props = defineProps({
  show:        { type: Boolean, required: true },
  creatorName: { type: String,  required: true },
  slug:        { type: String,  required: true },
})

// ② Eventos que puede emitir hacia el padre
const emit = defineEmits(['close'])

// ③ useForm de Inertia: maneja estado, errores y loading automáticamente
const form = useForm({
  supporter_name: '',
  message: '',
  amount: 5,
})

function submit() {
  form.post(route('public.support.store', { slug: props.slug }), {
    // ④ Al completarse exitosamente, cierra el modal y resetea el form
    onSuccess: () => {
      form.reset()
      emit('close')
    },
  })
}
</script>