<?php
// app/Models/Support.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Support extends Model
{
    // ① Le decimos a Laravel que esta tabla NO tiene updated_at
    public $timestamps = false;

    // ② Solo usamos created_at, lo manejamos como fecha
    protected $dates = ['created_at'];

    protected $fillable = [
        'creator_page_id',
        'supporter_name',
        'message',
        'amount',
    ];
    // ✅ FIX: castear created_at como datetime
    // Sin esto Laravel lo envía como string plano a Vue
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ③ Un apoyo pertenece a UNA página de creador
    public function creatorPage(): BelongsTo
    {
        return $this->belongsTo(CreatorPage::class);
    }
}