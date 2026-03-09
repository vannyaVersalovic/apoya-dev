<?php
// app/Http/Controllers/SupportController.php

namespace App\Http\Controllers;

use App\Models\CreatorPage;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function store(Request $request, string $slug)
    {
        // ① Busca la página por slug o lanza 404 automáticamente
        $creatorPage = CreatorPage::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'supporter_name' => ['required', 'string', 'max:100'],
            'message'        => ['nullable', 'string', 'max:500'],
            'amount'         => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        // ② Guarda el apoyo asociado a esa página
        $creatorPage->supports()->create($validated);

        // ③ Redirige de vuelta al perfil público con mensaje de éxito
        return redirect()->route('public.profile', ['slug' => $slug])
            ->with('success', '¡Gracias por tu apoyo! 🎉');
    }
}