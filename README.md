# ☕ apoya.dev

Mini plataforma de apoyos simbólicos tipo Ko-fi / Patreon, construida con Laravel 11, Vue 3, Inertia.js y Tailwind CSS.

---

## 🧱 Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel 11 + PHP 8.2 |
| Frontend | Vue 3 (Composition API) |
| Bridge | Inertia.js |
| Estilos | Tailwind CSS + CSS Scoped |
| Auth | Laravel Breeze (Vue + Inertia) |
| Base de datos | SQLite |
| Íconos | Lucide Vue Next |

---

## ✨ Funcionalidades

- ✅ Registro e inicio de sesión (Laravel Breeze)
- ✅ Cada usuario crea **una página de creador** con nombre, slug, bio y avatar
- ✅ Gestión de **links tipo Linktree** (crear, editar, eliminar)
- ✅ Página pública en `/@{slug}` con perfil completo y lista de links
- ✅ Modal de apoyo con nombre, mensaje y monto simbólico (1–100)
- ✅ Dashboard con lista de apoyos recibidos, montos y fechas relativas
- ✅ Diseño dark mode con glassmorphism, totalmente responsivo

---

## 📋 Requisitos previos

Antes de instalar, asegurate de tener instalado:

| Herramienta | Versión mínima | Verificar con |
|---|---|---|
| PHP | 8.2+ | `php -v` |
| Composer | 2.x | `composer -V` |
| Node.js | 18+ | `node -v` |
| npm | 9+ | `npm -v` |

> ✅ No necesitás instalar MySQL. El proyecto usa **SQLite** que viene incluido con PHP.

---

## 📁 Estructura del proyecto

```
app/
├── Http/Controllers/
│   ├── CreatorPageController.php
│   ├── LinkController.php
│   ├── SupportController.php
│   └── PublicProfileController.php
└── Models/
    ├── User.php
    ├── CreatorPage.php
    ├── Link.php
    └── Support.php

database/
├── migrations/
│   ├── ..._create_creator_pages_table.php
│   ├── ..._create_links_table.php
│   └── ..._create_supports_table.php
└── database.sqlite        ← archivo de base de datos (se crea automáticamente)

resources/js/
├── Pages/
│   ├── Dashboard.vue
│   └── PublicProfile.vue
└── Components/
    ├── SupportModal.vue
    └── ConfirmModal.vue

routes/
└── web.php
```

---

## 🗄️ Modelo de datos

```
User
 └── hasOne ──► CreatorPage
                 ├── hasMany ──► Link
                 └── hasMany ──► Support
```

| Tabla | Campos principales |
|---|---|
| `users` | id, name, email, password |
| `creator_pages` | id, user_id, name, slug, bio, avatar_url |
| `links` | id, creator_page_id, label, url |
| `supports` | id, creator_page_id, supporter_name, message, amount, created_at |

---

## 🚀 Instalación paso a paso

### 1. Clonar el repositorio

```bash
git clone https://github.com/vannyaVersalovic/apoya-dev.git
cd apoya-dev
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Instalar dependencias JavaScript

```bash
npm install
```

### 4. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configurar el `.env`

El proyecto usa **SQLite**. Verificá que tu `.env` tenga esto:

```env
DB_CONNECTION=sqlite
```

> ✅ No hace falta configurar host, puerto ni contraseña.
> Laravel crea el archivo `database/database.sqlite` automáticamente.

### 6. Crear el archivo de base de datos

```bash
# En Mac/Linux
touch database/database.sqlite

# En Windows (PowerShell)
New-Item -Path database/database.sqlite -ItemType File
```

### 7. Correr las migraciones

```bash
php artisan migrate
```

### 8. Limpiar caché (recomendado)

```bash
php artisan optimize:clear
```

### 9. Levantar los servidores

En **dos terminales separadas**:

```bash
# Terminal 1 — servidor Laravel
php artisan serve

# Terminal 2 — compilador Vite
npm run dev
```

La app estará disponible en: **http://localhost:8000**

---

## 🌐 Rutas principales

| Método | Ruta | Descripción | Auth |
|---|---|---|---|
| GET | `/@{slug}` |  — Página pública del creador | ❌ |
| POST | `/@{slug}/support` | Se dispara desde el modal de apoyo | ❌ |
| GET | `/dashboard` | Panel del creador | ✅ |
| POST | `/creator-page` | Crear página de creador | ✅ |
| PUT | `/creator-page` | Editar página de creador | ✅ |
| POST | `/links` | Agregar link | ✅ |
| PUT | `/links/{link}` | Editar link | ✅ |
| DELETE | `/links/{link}` | Eliminar link | ✅ |

---

## 🎨 Decisiones de diseño

- **Dark mode** con fondo `#0d0d1a` y gradientes radiales para dar profundidad
- **Glassmorphism** en las cards con `backdrop-filter: blur` y bordes semitransparentes
- **Fuente Outfit** (Google Fonts) para una tipografía moderna y legible
- **Lucide Vue Next** para íconos consistentes en todo el proyecto
- **Animaciones CSS** con `@keyframes fadeSlide` y `animation-delay` escalonado en listas

---

## 🧠 Decisiones técnicas

- `Inertia::render()` en todos los controladores — sin necesidad de una API REST separada
- `useForm()` de Inertia maneja automáticamente errores de validación, estado `processing` y reset
- `eager loading` con `with(['links', 'supports'])` para evitar el problema N+1
- `withCount('supports')` en la página pública para mostrar el contador sin cargar todos los registros
- Slug con validación `alpha_dash` + `unique` ignorando el ID propio al editar
- `$casts = ['created_at' => 'datetime']` en `Support` para fechas relativas correctas

---

## 👤 Uso rápido

1. Registrarse en `/register`
2. En el dashboard, crear la página con nombre, slug, bio y avatar
3. Agregar links desde el panel lateral
4. Compartir la URL `/@tu-slug`
5. Cualquier visitante puede enviar un apoyo desde el botón en la página pública

---

## 🤖 Herramientas de IA utilizadas

- **Gemini (Google)** — para interpretar el enunciado del desafío y estructurar el prompt inicial
- **Claude (Anthropic)** — migraciones, modelos, controladores, rutas y componentes Vue

---

## 📹 Video demostración

[Ver en YouTube →](https://youtu.be/8H7Um7neOwk)

---

## 👩‍💻 Autora

**vannyaVersalovic** — [github.com/vannyaVersalovic](https://github.com/vannyaVersalovic)

Desarrollado como desafío técnico para eSponsor.