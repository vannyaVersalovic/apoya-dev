<!-- resources/js/Pages/Dashboard.vue -->
<template>
  <div class="dashboard-root min-h-screen font-sans">

    <!-- ═══════════════════════════════════
         NAVBAR
         ═══════════════════════════════════ -->
    <nav class="navbar">
      <div class="mx-auto max-w-7xl px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="logo-icon">
            <Coffee :size="18" color="white" />
          </div>
          <span class="logo-text">apoya<span class="logo-accent">.dev</span></span>
        </div>
        <div class="flex items-center gap-4">
          <span class="nav-date">
            <CalendarDays :size="13" />
            {{ currentDate }}
          </span>
          <a
            v-if="creatorPage"
            :href="`/@${creatorPage.slug}`"
            target="_blank"
            class="nav-pill"
          >
            Ver perfil
            <ArrowUpRight :size="13" />
          </a>
          <Link href="/logout" method="post" as="button" class="nav-avatar">
            {{ (creatorPage?.name ?? $page.props.auth.user?.name)?.charAt(0).toUpperCase() }}
          </Link>
        </div>
      </div>
    </nav>

    <!-- ═══════════════════════════════════
         FLASH
         ═══════════════════════════════════ -->
    <Transition name="flash">
      <div v-if="$page.props.flash?.success" class="flash-bar">
        <PartyPopper :size="18" />
        {{ $page.props.flash.success }}
      </div>
    </Transition>

    <div class="mx-auto max-w-7xl px-6 py-8">

      <!-- ═══════════════════════════════════
           SIN PÁGINA: onboarding
           ═══════════════════════════════════ -->
      <section v-if="!creatorPage" class="flex items-center justify-center min-h-[70vh]">
        <div class="onboarding-card">
          <div class="onboarding-glow" />

          <Rocket :size="52" color="white" class="mx-auto mb-4 relative z-10 opacity-90" />
          <h2 class="text-3xl font-black text-white mb-2 relative z-10">Crea tu página</h2>
          <p class="text-sm text-white/50 mb-8 relative z-10">
            Configura tu perfil y empieza a recibir apoyos hoy
          </p>

          <form @submit.prevent="submitCreate" class="space-y-4 relative z-10 w-full">
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="glass-label">Nombre público</label>
                <input v-model="createForm.name" type="text" placeholder="Chloe Vance" class="glass-input" />
                <InputError :message="createForm.errors.name" />
              </div>
              <div>
                <label class="glass-label">Slug</label>
                <div class="slug-wrap">
                  <span class="slug-prefix">@</span>
                  <input v-model="createForm.slug" type="text" placeholder="chloe-vance" class="slug-input" />
                </div>
                <InputError :message="createForm.errors.slug" />
              </div>
            </div>
            <div>
              <label class="glass-label">Bio</label>
              <textarea v-model="createForm.bio" rows="2"
                placeholder="Cuéntale al mundo quién eres..." class="glass-input" />
              <InputError :message="createForm.errors.bio" />
            </div>
            <div>
              <label class="glass-label">Avatar (URL)</label>
              <input v-model="createForm.avatar_url" type="url" placeholder="https://..." class="glass-input" />
              <InputError :message="createForm.errors.avatar_url" />
            </div>
            <button type="submit" :disabled="createForm.processing" class="create-btn">
              <Sparkles :size="16" />
              {{ createForm.processing ? 'Creando...' : 'Crear mi página' }}
            </button>
          </form>
        </div>
      </section>

      <!-- ═══════════════════════════════════
           CON PÁGINA: layout principal
           ═══════════════════════════════════ -->
      <section v-else class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        <!-- ── COLUMNA IZQUIERDA ── -->
        <aside class="lg:col-span-4 space-y-5">

          <!-- Tarjeta de perfil -->
          <div class="profile-card">
            <div class="profile-banner">
              <div class="banner-orb banner-orb-1" />
              <div class="banner-orb banner-orb-2" />
            </div>
            <div class="profile-body">
              <div class="avatar-wrap">
                <img v-if="editForm.avatar_url" :src="editForm.avatar_url" class="avatar-img" />
                <div v-else class="avatar-fallback">
                  {{ creatorPage.name.charAt(0).toUpperCase() }}
                </div>
                <div class="verified-badge">
                  <BadgeCheck :size="13" color="white" />
                </div>
              </div>
              <h2 class="profile-name">{{ creatorPage.name }}</h2>
              <p class="profile-slug">@{{ creatorPage.slug }}</p>
              <p v-if="creatorPage.bio" class="profile-bio">{{ creatorPage.bio }}</p>

              <div class="stats-row">
                <div class="stat-pill stat-violet">
                  <span class="stat-num">{{ creatorPage.links?.length ?? 0 }}</span>
                  <span class="stat-lbl">links</span>
                </div>
                <div class="stat-pill stat-pink">
                  <span class="stat-num">{{ creatorPage.supports?.length ?? 0 }}</span>
                  <span class="stat-lbl">apoyos</span>
                </div>
                <div class="stat-pill stat-cyan">
                  <span class="stat-num">${{ totalAmount }}</span>
                  <span class="stat-lbl">total</span>
                </div>
              </div>

              <div v-if="creatorPage.links?.length" class="links-list">
                <a
                  v-for="(link, i) in creatorPage.links"
                  :key="link.id"
                  :href="link.url"
                  target="_blank"
                  :style="{ animationDelay: `${i * 80}ms` }"
                  class="link-btn"
                >
                  <ExternalLink :size="12" class="opacity-50" />
                  {{ link.label }}
                </a>
              </div>

              <a :href="`/@${creatorPage.slug}`" target="_blank" class="support-btn">
                <Coffee :size="16" />
                Apoyar a {{ creatorPage.name }}
              </a>
            </div>
          </div>

          <!-- Editar perfil -->
          <div class="glass-card">
            <button @click="showEdit = !showEdit"
              class="w-full flex items-center justify-between text-left">
              <span class="section-title">
                <Pencil :size="15" class="text-violet-400" />
                Editar perfil
              </span>
              <ChevronDown :size="16" class="text-white/40 transition-transform duration-200"
                :class="showEdit ? 'rotate-180' : ''" />
            </button>
            <Transition name="collapse">
              <form v-if="showEdit" @submit.prevent="submitUpdate" class="mt-4 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="glass-label">Nombre</label>
                    <input v-model="editForm.name" type="text" class="glass-input" />
                    <InputError :message="editForm.errors.name" />
                  </div>
                  <div>
                    <label class="glass-label">Slug</label>
                    <input v-model="editForm.slug" type="text" class="glass-input" />
                    <InputError :message="editForm.errors.slug" />
                  </div>
                </div>
                <div>
                  <label class="glass-label">Avatar URL</label>
                  <input v-model="editForm.avatar_url" type="url" class="glass-input" />
                  <InputError :message="editForm.errors.avatar_url" />
                </div>
                <div>
                  <label class="glass-label">Bio</label>
                  <textarea v-model="editForm.bio" rows="2" class="glass-input" />
                  <InputError :message="editForm.errors.bio" />
                </div>
                <button type="submit" :disabled="editForm.processing" class="save-btn">
                  <Save :size="13" />
                  {{ editForm.processing ? 'Guardando...' : 'Guardar cambios' }}
                </button>
              </form>
            </Transition>
          </div>

          <!-- Gestionar links -->
          <div class="glass-card">
            <button @click="showLinks = !showLinks"
              class="w-full flex items-center justify-between text-left">
              <span class="section-title">
                <Link2 :size="15" class="text-violet-400" />
                Gestionar links
              </span>
              <ChevronDown :size="16" class="text-white/40 transition-transform duration-200"
                :class="showLinks ? 'rotate-180' : ''" />
            </button>
            <Transition name="collapse">
              <div v-if="showLinks" class="mt-4 space-y-2">
                <div v-for="link in creatorPage.links" :key="link.id" class="link-manage-row">
                  <div v-if="editingLinkId === link.id" class="flex gap-2 flex-1">
                    <input v-model="linkEditForm.label" placeholder="Label"
                      class="glass-input text-xs py-1 flex-1" />
                    <input v-model="linkEditForm.url" placeholder="URL"
                      class="glass-input text-xs py-1 flex-1" />
                    <button @click="submitLinkUpdate(link.id)"
                      class="text-green-400 hover:text-green-300 transition">
                      <Check :size="15" />
                    </button>
                    <button @click="editingLinkId = null"
                      class="text-white/30 hover:text-white/60 transition">
                      <X :size="15" />
                    </button>
                  </div>
                  <div v-else class="flex items-center justify-between flex-1">
                    <div>
                      <p class="text-xs font-semibold text-white/80">{{ link.label }}</p>
                      <p class="text-xs text-white/30 truncate max-w-[120px]">{{ link.url }}</p>
                    </div>
                    <div class="flex gap-3">
                      <button @click="startEditLink(link)"
                        class="text-violet-400 hover:text-violet-300 transition">
                        <PencilLine :size="14" />
                      </button>
                      <button @click="deleteLink(link)" class="text-rose-400 hover:text-rose-300 transition">
                        <Trash2 :size="14" />
                      </button>
                      
                    </div>
                  </div>
                </div>

                <form @submit.prevent="submitLinkCreate"
                  class="pt-2 space-y-2 border-t border-white/10">
                  <input v-model="linkCreateForm.label" placeholder="Etiqueta (Ej: Instagram)"
                    class="glass-input text-xs" />
                  <input v-model="linkCreateForm.url" placeholder="https://..."
                    class="glass-input text-xs" />
                  <button type="submit" :disabled="linkCreateForm.processing" class="save-btn text-xs">
                    <Plus :size="13" />
                    Agregar link
                  </button>
                </form>
              </div>
            </Transition>
          </div>

        </aside>

        <!-- ── COLUMNA DERECHA ── -->
        <main class="lg:col-span-8 space-y-5">

          <div class="grid grid-cols-3 gap-4">
            <div class="metric-card metric-violet">
              <DollarSign :size="20" class="mb-2 opacity-60" style="color:#a78bfa" />
              <p class="metric-num">${{ totalAmount }}</p>
              <p class="metric-lbl">Total recibido</p>
            </div>
            <div class="metric-card metric-pink">
              <Heart :size="20" class="mb-2 opacity-60" style="color:#f9a8d4" />
              <p class="metric-num">{{ creatorPage.supports?.length ?? 0 }}</p>
              <p class="metric-lbl">Apoyos totales</p>
            </div>
            <div class="metric-card metric-cyan">
              <Link2 :size="20" class="mb-2 opacity-60" style="color:#67e8f9" />
              <p class="metric-num">{{ creatorPage.links?.length ?? 0 }}</p>
              <p class="metric-lbl">Links activos</p>
            </div>
          </div>

          <div class="glass-card">
            <div class="flex items-center justify-between mb-6">
              <h3 class="section-title text-lg">
                <Coffee :size="18" class="text-violet-400" />
                Apoyos recientes
              </h3>
              <span class="support-count-badge">
                {{ creatorPage.supports?.length ?? 0 }} total
              </span>
            </div>

            <div v-if="creatorPage.supports?.length" class="space-y-2">
              <div
                v-for="(support, i) in creatorPage.supports"
                :key="support.id"
                :style="{ animationDelay: `${i * 60}ms` }"
                class="support-row"
              >
                <div class="support-avatar">
                  {{ support.supporter_name.charAt(0).toUpperCase() }}
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-bold text-white">{{ support.supporter_name }}</p>
                  <p v-if="support.message" class="text-xs text-white/40 truncate mt-0.5 italic">
                    "{{ support.message }}"
                  </p>
                </div>
                <div class="text-right flex-shrink-0">
                  <p class="support-amount">${{ support.amount }}.00</p>
                  <p class="support-time">{{ formatDate(support.created_at) }}</p>
                </div>
              </div>
            </div>

            <div v-else class="empty-state">
              <Coffee :size="48" class="mb-4 opacity-30" color="white" />
              <p class="text-white/60 font-semibold">Aún no has recibido apoyos</p>
              <p class="text-sm text-white/30 mt-1">¡Comparte tu página y empieza a crecer!</p>
              <a :href="`/@${creatorPage.slug}`" target="_blank"
                class="mt-5 save-btn inline-flex items-center gap-2">
                <Share2 :size="13" />
                Compartir mi página
              </a>
            </div>
          </div>

        </main>
      </section>
    </div>
    <ConfirmModal
      :show="showConfirmModal"
      :link-label="linkToDelete?.label ?? ''"
      @confirm="confirmDelete"
      @cancel="showConfirmModal = false"
    />

  </div>  
  
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'

import {
  Coffee, CalendarDays, ArrowUpRight, PartyPopper, Rocket, Sparkles,
  BadgeCheck, Pencil, PencilLine, ChevronDown, Save, Link2, ExternalLink,
  Check, X, Trash2, Plus, DollarSign, Heart, Share2,
} from 'lucide-vue-next'

const props = defineProps({
  creatorPage: { type: Object, default: null },
})

const showEdit  = ref(false)
const showLinks = ref(false)

const currentDate = computed(() =>
  new Date().toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })
)
const showConfirmModal = ref(false)
const linkToDelete     = ref(null)  // guarda { id, label } del link a borrar

const totalAmount = computed(() =>
  (props.creatorPage?.supports ?? []).reduce((sum, s) => sum + Number(s.amount), 0)
)

function formatDate(dateStr) {
  if (!dateStr) return 'Sin fecha'

  const date = new Date(dateStr)

  // ① Verifica que sea una fecha válida
  if (isNaN(date.getTime())) return 'Sin fecha'

  const now  = new Date()
  const diff = Math.floor((now - date) / 1000) // diferencia en segundos

  // ② Hace menos de 1 minuto
  if (diff < 60)   return 'hace un momento'

  // ③ Hace menos de 1 hora → "hace 23 min"
  if (diff < 3600) {
    const mins = Math.floor(diff / 60)
    return `hace ${mins} min`
  }

  // ④ Hace menos de 24 horas → "hace 3 hrs"
  if (diff < 86400) {
    const hrs = Math.floor(diff / 3600)
    return `hace ${hrs} ${hrs === 1 ? 'hr' : 'hrs'}`
  }

  // ⑤ Hace menos de 7 días → "hace 3 días"
  if (diff < 604800) {
    const days = Math.floor(diff / 86400)
    return `hace ${days} ${days === 1 ? 'día' : 'días'}`
  }

  // ⑥ Más de 7 días → fecha completa "12 ene 2025"
  return date.toLocaleDateString('es-ES', {
    day:   'numeric',
    month: 'short',
    year:  'numeric',
  })
}

const createForm = useForm({ name: '', slug: '', bio: '', avatar_url: '' })
function submitCreate() { createForm.post(route('creator-page.store')) }

const editForm = useForm({
  name:       props.creatorPage?.name       ?? '',
  slug:       props.creatorPage?.slug       ?? '',
  bio:        props.creatorPage?.bio        ?? '',
  avatar_url: props.creatorPage?.avatar_url ?? '',
})
function submitUpdate() { editForm.put(route('creator-page.update')) }

const linkCreateForm = useForm({ label: '', url: '' })
function submitLinkCreate() {
  linkCreateForm.post(route('links.store'), { onSuccess: () => linkCreateForm.reset() })
}

const editingLinkId = ref(null)
const linkEditForm  = useForm({ label: '', url: '' })
function startEditLink(link) {
  editingLinkId.value = link.id
  linkEditForm.label  = link.label
  linkEditForm.url    = link.url
}
function submitLinkUpdate(linkId) {
  linkEditForm.put(route('links.update', { link: linkId }), {
    onSuccess: () => { editingLinkId.value = null },
  })
}

const deleteForm = useForm({})
function deleteLink(link) {
  linkToDelete.value = { id: link.id, label: link.label }
  showConfirmModal.value = true
}

function confirmDelete() {
  deleteForm.delete(route('links.destroy', { link: linkToDelete.value.id }), {
    onSuccess: () => {
      showConfirmModal.value = false
      linkToDelete.value = null
    }
  })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap');

.dashboard-root {
  font-family: 'Outfit', sans-serif;
  background: #0d0d1a;
  background-image:
    radial-gradient(ellipse 80% 50% at 20% -10%, rgba(109, 40, 217, 0.25) 0%, transparent 60%),
    radial-gradient(ellipse 60% 40% at 80% 100%, rgba(236, 72, 153, 0.15) 0%, transparent 60%);
}
.navbar {
  background: rgba(13, 13, 26, 0.8);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(255,255,255,0.06);
  position: sticky; top: 0; z-index: 50;
}
.logo-icon {
  width: 36px; height: 36px;
  background: linear-gradient(135deg, #7c3aed, #ec4899);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
}
.logo-text   { font-size: 1.2rem; font-weight: 800; color: white; letter-spacing: -0.5px; }
.logo-accent { color: #a78bfa; }
.nav-date {
  font-size: 0.75rem; color: rgba(255,255,255,0.3);
  display: flex; align-items: center; gap: 5px;
}
.nav-pill {
  font-size: 0.75rem; font-weight: 600; padding: 6px 14px;
  background: rgba(124,58,237,0.2); border: 1px solid rgba(124,58,237,0.4);
  border-radius: 999px; color: #a78bfa; transition: all 0.2s; text-decoration: none;
  display: flex; align-items: center; gap: 5px;
}
.nav-pill:hover { background: rgba(124,58,237,0.35); }
.nav-avatar {
  width: 36px; height: 36px;
  background: linear-gradient(135deg, #7c3aed, #ec4899);
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  font-size: 0.85rem; font-weight: 800; color: white; cursor: pointer; border: none;
}
.flash-bar {
  max-width: 80rem; margin: 12px auto; padding: 12px 20px;
  background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3);
  border-radius: 14px; color: #86efac; font-size: 0.875rem; font-weight: 600;
  display: flex; align-items: center; gap: 10px;
}
.flash-enter-from, .flash-leave-to { opacity: 0; transform: translateY(-8px); }
.flash-enter-active, .flash-leave-active { transition: all 0.3s ease; }

.glass-card {
  background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
  border-radius: 24px; padding: 24px; backdrop-filter: blur(12px);
}
.glass-label {
  display: block; font-size: 0.7rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.08em;
  color: rgba(255,255,255,0.4); margin-bottom: 6px;
}
.glass-input {
  width: 100%; background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.1); border-radius: 10px;
  padding: 9px 12px; font-size: 0.875rem; color: white;
  font-family: 'Outfit', sans-serif; outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.glass-input:focus {
  border-color: rgba(124,58,237,0.6);
  box-shadow: 0 0 0 3px rgba(124,58,237,0.15);
}
.glass-input::placeholder { color: rgba(255,255,255,0.2); }

.onboarding-card {
  position: relative; width: 100%; max-width: 520px;
  background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
  border-radius: 32px; padding: 48px; overflow: hidden; text-align: center;
}
.onboarding-glow {
  position: absolute; inset: -40px;
  background: radial-gradient(circle at 50% 0%, rgba(124,58,237,0.3), transparent 60%);
  pointer-events: none;
}
.slug-wrap {
  display: flex; align-items: center;
  background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
  border-radius: 10px; overflow: hidden;
}
.slug-prefix {
  padding: 9px 10px 9px 12px; font-size: 0.875rem; color: rgba(255,255,255,0.3);
  border-right: 1px solid rgba(255,255,255,0.08);
}
.slug-input {
  flex: 1; padding: 9px 12px; background: transparent;
  font-size: 0.875rem; color: white; font-family: 'Outfit', sans-serif; outline: none;
}
.slug-input::placeholder { color: rgba(255,255,255,0.2); }
.create-btn {
  width: 100%; padding: 13px;
  background: linear-gradient(135deg, #7c3aed, #ec4899); border-radius: 12px;
  font-size: 0.875rem; font-weight: 800; color: white; border: none; cursor: pointer;
  box-shadow: 0 8px 24px rgba(124,58,237,0.4); transition: transform 0.2s, box-shadow 0.2s;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  font-family: 'Outfit', sans-serif;
}
.create-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(124,58,237,0.5); }
.create-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

.profile-card {
  border-radius: 28px; overflow: hidden;
  border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.04);
}
.profile-banner {
  height: 90px; background: linear-gradient(135deg, #4c1d95, #7c3aed, #ec4899);
  position: relative; overflow: hidden;
}
.banner-orb { position: absolute; border-radius: 50%; filter: blur(20px); opacity: 0.6; }
.banner-orb-1 { width: 80px; height: 80px; background: rgba(236,72,153,0.8); top: -20px; right: 20px; }
.banner-orb-2 { width: 60px; height: 60px; background: rgba(6,182,212,0.6); bottom: -10px; left: 30px; }
.profile-body { padding: 0 24px 24px; }
.avatar-wrap { position: relative; display: inline-block; margin-top: -36px; margin-bottom: 12px; }
.avatar-img { width: 72px; height: 72px; border-radius: 20px; object-fit: cover; border: 3px solid #0d0d1a; display: block; }
.avatar-fallback {
  width: 72px; height: 72px; border-radius: 20px;
  background: linear-gradient(135deg, #7c3aed, #ec4899); border: 3px solid #0d0d1a;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.5rem; font-weight: 900; color: white;
}
.verified-badge {
  position: absolute; bottom: -4px; right: -4px;
  width: 22px; height: 22px; background: linear-gradient(135deg, #06b6d4, #7c3aed);
  border-radius: 50%; border: 2px solid #0d0d1a;
  display: flex; align-items: center; justify-content: center;
}
.profile-name { font-size: 1.25rem; font-weight: 900; color: white; margin-bottom: 2px; }
.profile-slug { font-size: 0.8rem; font-weight: 600; color: #a78bfa; margin-bottom: 8px; }
.profile-bio  { font-size: 0.78rem; color: rgba(255,255,255,0.45); line-height: 1.5; margin-bottom: 16px; }

.stats-row { display: flex; gap: 8px; margin-bottom: 16px; }
.stat-pill  { flex: 1; border-radius: 12px; padding: 8px; text-align: center; border: 1px solid; }
.stat-violet { background: rgba(124,58,237,0.15); border-color: rgba(124,58,237,0.3); }
.stat-pink   { background: rgba(236,72,153,0.15); border-color: rgba(236,72,153,0.3); }
.stat-cyan   { background: rgba(6,182,212,0.15);  border-color: rgba(6,182,212,0.3); }
.stat-num { display: block; font-size: 1rem; font-weight: 800; color: white; }
.stat-lbl { display: block; font-size: 0.65rem; color: rgba(255,255,255,0.4); }

.links-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }
.link-btn {
  display: flex; align-items: center; gap: 8px; padding: 10px 16px;
  background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
  border-radius: 12px; font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.8);
  text-decoration: none; transition: all 0.2s; justify-content: center;
  animation: fadeSlide 0.4s ease both;
}
.link-btn:hover {
  background: rgba(124,58,237,0.2); border-color: rgba(124,58,237,0.5);
  color: white; transform: translateY(-1px);
}
.support-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px;
  background: linear-gradient(135deg, #7c3aed, #ec4899); border-radius: 14px;
  font-size: 0.875rem; font-weight: 800; color: white; text-decoration: none;
  box-shadow: 0 4px 20px rgba(124,58,237,0.35); transition: transform 0.2s, box-shadow 0.2s;
}
.support-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(124,58,237,0.5); }

.section-title {
  font-size: 0.95rem; font-weight: 800; color: white;
  display: flex; align-items: center; gap: 8px;
}
.save-btn {
  width: 100%; padding: 9px; background: rgba(124,58,237,0.25);
  border: 1px solid rgba(124,58,237,0.4); border-radius: 10px;
  font-size: 0.8rem; font-weight: 700; color: #c4b5fd; cursor: pointer;
  font-family: 'Outfit', sans-serif; transition: all 0.2s;
  display: flex; align-items: center; justify-content: center; gap: 6px;
}
.save-btn:hover  { background: rgba(124,58,237,0.4); }
.save-btn:disabled { opacity: 0.4; cursor: not-allowed; }

.link-manage-row {
  display: flex; align-items: center; gap: 8px; padding: 8px 10px;
  background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
  border-radius: 10px;
}
.metric-card { border-radius: 20px; padding: 20px; border: 1px solid; }
.metric-violet { background: rgba(124,58,237,0.12); border-color: rgba(124,58,237,0.25); }
.metric-pink   { background: rgba(236,72,153,0.12); border-color: rgba(236,72,153,0.25); }
.metric-cyan   { background: rgba(6,182,212,0.12);  border-color: rgba(6,182,212,0.25); }
.metric-num { font-size: 2rem; font-weight: 900; color: white; line-height: 1; }
.metric-lbl { font-size: 0.75rem; color: rgba(255,255,255,0.4); margin-top: 4px; font-weight: 500; }

.support-count-badge {
  padding: 4px 12px; background: rgba(124,58,237,0.2);
  border: 1px solid rgba(124,58,237,0.35); border-radius: 999px;
  font-size: 0.75rem; font-weight: 700; color: #a78bfa;
}
.support-row {
  display: flex; align-items: center; gap: 14px; padding: 12px 14px;
  border-radius: 14px; border: 1px solid transparent; transition: all 0.2s;
  animation: fadeSlide 0.4s ease both;
}
.support-row:hover { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.07); }
.support-avatar {
  width: 42px; height: 42px; flex-shrink: 0; border-radius: 14px;
  background: linear-gradient(135deg, #7c3aed, #ec4899);
  display: flex; align-items: center; justify-content: center;
  font-size: 0.9rem; font-weight: 800; color: white;
  box-shadow: 0 4px 12px rgba(124,58,237,0.3);
}
.support-amount {
  font-size: 1rem; font-weight: 900;
  background: linear-gradient(135deg, #a78bfa, #f472b6);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.support-time { font-size: 0.7rem; color: rgba(255,255,255,0.25); margin-top: 2px; }
.empty-state {
  display: flex; flex-direction: column; align-items: center;
  padding: 48px 24px; text-align: center;
}
@keyframes fadeSlide {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}
.collapse-enter-from, .collapse-leave-to { opacity: 0; transform: translateY(-8px); }
.collapse-enter-active, .collapse-leave-active { transition: all 0.25s ease; }
</style>