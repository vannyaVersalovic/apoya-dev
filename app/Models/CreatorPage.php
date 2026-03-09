<?php
// app/Models/CreatorPage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreatorPage extends Model
{
    // ① Qué campos se pueden llenar masivamente (formularios)
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'bio',
        'avatar_url',
    ];

    // ② El creador pertenece a UN usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ③ Una página tiene MUCHOS links
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    // ④ Una página tiene MUCHOS apoyos
    public function supports(): HasMany
    {
        return $this->hasMany(Support::class);
    }
}