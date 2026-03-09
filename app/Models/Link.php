<?php
// app/Models/Link.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Link extends Model
{
    protected $fillable = [
        'creator_page_id',
        'label',
        'url',
    ];

    // ① Un link pertenece a UNA página de creador
    public function creatorPage(): BelongsTo
    {
        return $this->belongsTo(CreatorPage::class);
    }
}