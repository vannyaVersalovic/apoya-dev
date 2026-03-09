<?php
// app/Http/Controllers/PublicProfileController.php

namespace App\Http\Controllers;           // ← sin esto, PHP no sabe dónde está

use App\Models\CreatorPage;
use Inertia\Inertia;
use Inertia\Response;

class PublicProfileController extends Controller  // ← la clase contenedora
{
    public function show(string $slug): Response
    {
        $creatorPage = CreatorPage::where('slug', $slug)
            ->with([
                'links',
                'supports' => fn($q) => $q->latest('created_at')->limit(5),
            ])
            ->withCount('supports')
            ->firstOrFail();

        return Inertia::render('PublicProfile', [
            'creatorPage' => $creatorPage,
        ]);
    }
}