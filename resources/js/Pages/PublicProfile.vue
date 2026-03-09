<!-- resources/js/Pages/PublicProfile.vue -->
<template>
  <div class="profile-root min-h-screen font-sans">

    <!-- ═══════════════════════════════════
         NAVBAR
         ═══════════════════════════════════ -->
    <nav class="navbar">
      <div class="mx-auto max-w-2xl px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="logo-icon">
            <Coffee :size="18" color="white" />
          </div>
          <span class="logo-text">apoya<span class="logo-accent">.dev</span></span>
        </div>
        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="nav-pill">
          <LayoutDashboard :size="13" />
          Mi dashboard
        </Link>
        <Link v-else :href="route('login')" class="nav-pill">
          <LogIn :size="13" />
          Iniciar sesión
        </Link>
      </div>
    </nav>

    <!-- ═══════════════════════════════════
         FLASH
         ═══════════════════════════════════ -->
    <Transition name="flash">
      <div v-if="$page.props.flash?.success" class="flash-bar mx-auto max-w-2xl mt-4 px-6">
        <PartyPopper :size="18" />
        {{ $page.props.flash.success }}
      </div>
    </Transition>

    <div class="mx-auto max-w-2xl px-6 py-10 space-y-5">

      <!-- ═══════════════════════════════════
           TARJETA PRINCIPAL
           ═══════════════════════════════════ -->
      <div class="profile-card">
        <div class="profile-banner">
          <div class="banner-orb banner-orb-1" />
          <div class="banner-orb banner-orb-2" />
          <div class="banner-orb banner-orb-3" />
        </div>

        <div class="profile-body">
          <div class="avatar-wrap">
            <img v-if="creatorPage.avatar_url" :src="creatorPage.avatar_url"
              :alt="creatorPage.name" class="avatar-img" />
            <div v-else class="avatar-fallback">
              {{ creatorPage.name.charAt(0).toUpperCase() }}
            </div>
            <div class="verified-badge">
              <BadgeCheck :size="13" color="white" />
            </div>
          </div>

          <h1 class="profile-name">{{ creatorPage.name }}</h1>
          <p class="profile-slug">@{{ creatorPage.slug }}</p>
          <p v-if="creatorPage.bio" class="profile-bio">{{ creatorPage.bio }}</p>

          <div class="supports-badge">
            <Coffee :size="14" />
            {{ creatorPage.supports_count }}
            {{ creatorPage.supports_count === 1 ? 'apoyo recibido' : 'apoyos recibidos' }}
          </div>

          <button @click="openModal" class="support-btn">
            <Heart :size="16" />
            Apoyar a {{ creatorPage.name }}
          </button>
        </div>
      </div>

      <!-- ═══════════════════════════════════
           LINKS
           ═══════════════════════════════════ -->
      <div v-if="creatorPage.links.length" class="glass-card">
        <p class="section-label flex items-center gap-2">
          <Link2 :size="13" />
          Links
        </p>
        <div class="space-y-2 mt-3">
          <a
            v-for="(link, index) in creatorPage.links"
            :key="link.id"
            :href="link.url"
            target="_blank"
            rel="noopener noreferrer"
            :style="{ animationDelay: `${index * 70}ms` }"
            class="link-btn"
          >
            <span class="flex items-center gap-2">
              <ExternalLink :size="13" class="opacity-40" />
              {{ link.label }}
            </span>
            <ArrowRight :size="15" class="link-arrow" />
          </a>
        </div>
      </div>

      <!-- ═══════════════════════════════════
           APOYOS RECIENTES
           ═══════════════════════════════════ -->
      <div v-if="recentSupports.length" class="glass-card">
        <p class="section-label flex items-center gap-2">
          <Heart :size="13" />
          Apoyos recientes
        </p>
        <div class="space-y-2 mt-3">
          <div
            v-for="(support, i) in recentSupports"
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
            <p class="support-amount">${{ support.amount }}.00</p>
          </div>
        </div>
      </div>

      <p class="text-center text-xs pb-4 flex items-center justify-center gap-1"
        style="color: rgba(255,255,255,0.15)">
        Hecho con <Coffee :size="11" /> en apoya.dev
      </p>

    </div>

    <!-- ═══════════════════════════════════
         MODAL
         ═══════════════════════════════════ -->
    <SupportModal
      :show="showModal"
      :creator-name="creatorPage.name"
      :slug="creatorPage.slug"
      @close="showModal = false"
    />

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import SupportModal from '@/Components/SupportModal.vue'

import {
  Coffee, LayoutDashboard, LogIn, PartyPopper, BadgeCheck,
  Heart, Link2, ExternalLink, ArrowRight,
} from 'lucide-vue-next'

const props = defineProps({
  creatorPage: { type: Object, required: true },
})

const showModal = ref(false)
function openModal() { showModal.value = true }

const recentSupports = computed(() =>
  (props.creatorPage.supports ?? []).slice(0, 5)
)
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap');

.profile-root {
  font-family: 'Outfit', sans-serif;
  background: #0d0d1a;
  background-image:
    radial-gradient(ellipse 80% 50% at 20% -10%, rgba(109, 40, 217, 0.25) 0%, transparent 60%),
    radial-gradient(ellipse 60% 40% at 80% 100%, rgba(236, 72, 153, 0.15) 0%, transparent 60%);
}
.navbar {
  background: rgba(13, 13, 26, 0.8); backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(255,255,255,0.06);
  position: sticky; top: 0; z-index: 50;
}
.logo-icon {
  width: 36px; height: 36px; background: linear-gradient(135deg, #7c3aed, #ec4899);
  border-radius: 10px; display: flex; align-items: center; justify-content: center;
}
.logo-text   { font-size: 1.2rem; font-weight: 800; color: white; letter-spacing: -0.5px; }
.logo-accent { color: #a78bfa; }
.nav-pill {
  font-size: 0.75rem; font-weight: 600; padding: 6px 14px;
  background: rgba(124,58,237,0.2); border: 1px solid rgba(124,58,237,0.4);
  border-radius: 999px; color: #a78bfa; transition: all 0.2s; text-decoration: none;
  display: flex; align-items: center; gap: 6px;
}
.nav-pill:hover { background: rgba(124,58,237,0.35); }

.flash-bar {
  padding: 12px 20px; background: rgba(34,197,94,0.15);
  border: 1px solid rgba(34,197,94,0.3); border-radius: 14px;
  color: #86efac; font-size: 0.875rem; font-weight: 600;
  display: flex; align-items: center; gap: 10px;
}
.flash-enter-from, .flash-leave-to  { opacity: 0; transform: translateY(-8px); }
.flash-enter-active, .flash-leave-active { transition: all 0.3s ease; }

.glass-card {
  background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
  border-radius: 24px; padding: 24px; backdrop-filter: blur(12px);
}
.section-label {
  font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.08em; color: rgba(255,255,255,0.3);
}

.profile-card {
  border-radius: 28px; overflow: hidden;
  border: 1px solid rgba(255,255,255,0.08);
  background: rgba(255,255,255,0.04); backdrop-filter: blur(12px);
}
.profile-banner {
  height: 100px; background: linear-gradient(135deg, #4c1d95, #7c3aed, #ec4899);
  position: relative; overflow: hidden;
}
.banner-orb { position: absolute; border-radius: 50%; filter: blur(24px); opacity: 0.7; }
.banner-orb-1 { width: 100px; height: 100px; background: rgba(236,72,153,0.8); top: -30px; right: 40px; }
.banner-orb-2 { width: 80px; height: 80px; background: rgba(6,182,212,0.6); bottom: -20px; left: 60px; }
.banner-orb-3 { width: 60px; height: 60px; background: rgba(250,204,21,0.4); top: 10px; left: 40%; }

.profile-body { padding: 0 28px 28px; }
.avatar-wrap { position: relative; display: inline-block; margin-top: -40px; margin-bottom: 14px; }
.avatar-img { width: 80px; height: 80px; border-radius: 22px; object-fit: cover; border: 3px solid #0d0d1a; display: block; }
.avatar-fallback {
  width: 80px; height: 80px; border-radius: 22px;
  background: linear-gradient(135deg, #7c3aed, #ec4899); border: 3px solid #0d0d1a;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.75rem; font-weight: 900; color: white;
}
.verified-badge {
  position: absolute; bottom: -4px; right: -4px;
  width: 24px; height: 24px; background: linear-gradient(135deg, #06b6d4, #7c3aed);
  border-radius: 50%; border: 2px solid #0d0d1a;
  display: flex; align-items: center; justify-content: center;
}
.profile-name { font-size: 1.6rem; font-weight: 900; color: white; line-height: 1.1; }
.profile-slug { font-size: 0.85rem; font-weight: 600; color: #a78bfa; margin: 4px 0 10px; }
.profile-bio  {
  font-size: 0.875rem; color: rgba(255,255,255,0.45);
  line-height: 1.6; margin-bottom: 18px; max-width: 480px;
}
.supports-badge {
  display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px;
  background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3);
  border-radius: 999px; font-size: 0.8rem; font-weight: 600; color: #c4b5fd;
  margin-bottom: 20px;
}
.support-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%; padding: 14px;
  background: linear-gradient(135deg, #7c3aed, #ec4899); border-radius: 16px;
  font-size: 0.95rem; font-weight: 800; color: white; border: none; cursor: pointer;
  font-family: 'Outfit', sans-serif; box-shadow: 0 8px 28px rgba(124,58,237,0.4);
  transition: transform 0.2s, box-shadow 0.2s;
}
.support-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 36px rgba(124,58,237,0.55); }
.support-btn:active { transform: scale(0.98); }

.link-btn {
  display: flex; align-items: center; justify-content: space-between;
  padding: 13px 16px; background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.08); border-radius: 14px;
  text-decoration: none; color: rgba(255,255,255,0.75);
  font-size: 0.875rem; font-weight: 600; transition: all 0.2s;
  animation: fadeSlide 0.4s ease both;
}
.link-btn:hover {
  background: rgba(124,58,237,0.2); border-color: rgba(124,58,237,0.45);
  color: white; transform: translateY(-2px);
}
.link-arrow { color: rgba(167,139,250,0.4); transition: transform 0.2s, color 0.2s; }
.link-btn:hover .link-arrow { color: #a78bfa; transform: translateX(3px); }

.support-row {
  display: flex; align-items: center; gap: 12px; padding: 10px 12px;
  border-radius: 14px; border: 1px solid transparent;
  transition: all 0.2s; animation: fadeSlide 0.4s ease both;
}
.support-row:hover { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.07); }
.support-avatar {
  width: 40px; height: 40px; flex-shrink: 0; border-radius: 12px;
  background: linear-gradient(135deg, #7c3aed, #ec4899);
  display: flex; align-items: center; justify-content: center;
  font-size: 0.875rem; font-weight: 800; color: white;
  box-shadow: 0 4px 12px rgba(124,58,237,0.3);
}
.support-amount {
  font-size: 0.95rem; font-weight: 900; flex-shrink: 0;
  background: linear-gradient(135deg, #a78bfa, #f472b6);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}

@keyframes fadeSlide {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>