<?php
// app/Http/Controllers/CreatorPageController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CreatorPageController extends Controller
{
    // ① Muestra el dashboard del creador
    public function index(Request $request): Response
    {
        // Carga la página del creador con sus links
        // "with" hace eager loading → evita el problema N+1
        $creatorPage = $request->user()
            ->creatorPage()
            ->with(['links', 
            'supports' => fn($q) => $q->latest('created_at'),
            ])
            ->first();

        // ② Inertia::render envía los datos como props al componente Vue
        return Inertia::render('Dashboard', [
            'creatorPage' => $creatorPage,
        ]);
    }

    // ③ Crea la página de creador (solo si no tiene una)
    public function store(Request $request)
    {
        // Valida los datos del formulario
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:50', 'unique:creator_pages,slug', 'alpha_dash'],
            'bio'  => ['nullable', 'string', 'max:500'],
            'avatar_url' => ['nullable', 'url'],
        ]);

        // Crea asociada al usuario autenticado
        $request->user()->creatorPage()->create($validated);

        // ④ Redirect con mensaje flash
        return redirect()->route('dashboard')
            ->with('success', '¡Página creada exitosamente!');
    }

    // ⑤ Actualiza la página de creador existente
    public function update(Request $request)
    {
        $creatorPage = $request->user()->creatorPage;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            // ⑥ "unique" ignorando el registro actual para no chocar consigo mismo
            'slug' => ['required', 'string', 'max:50', 'alpha_dash',
                'unique:creator_pages,slug,' . $creatorPage->id],
            'bio'  => ['nullable', 'string', 'max:500'],
            'avatar_url' => ['nullable', 'url'],
        ]);

        $creatorPage->update($validated);

        return redirect()->route('dashboard')
            ->with('success', '¡Página actualizada!');
    }
}