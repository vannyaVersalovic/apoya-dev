<?php
// app/Http/Controllers/LinkController.php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'url'   => ['required', 'url'],
        ]);

        // ① Agrega el link a la página del usuario autenticado
        $request->user()
            ->creatorPage
            ->links()
            ->create($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Link agregado.');
    }

    public function update(Request $request, Link $link)
    {
        // ② Autorización manual: solo el dueño puede editar su link
        $this->authorizeLink($link, $request);

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'url'   => ['required', 'url'],
        ]);

        $link->update($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Link actualizado.');
    }

    public function destroy(Request $request, Link $link)
    {
        // ③ Misma verificación de propiedad antes de borrar
        $this->authorizeLink($link, $request);

        $link->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Link eliminado.');
    }

    // ④ Método privado reutilizable para verificar propiedad del link
    private function authorizeLink(Link $link, Request $request): void
    {
        $creatorPageId = $request->user()->creatorPage->id;

        if ($link->creator_page_id !== $creatorPageId) {
            abort(403, 'No autorizado.');
        }
    }
}