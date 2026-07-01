<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Link extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'href',
        'sref',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('user', fn (Builder $builder) => $builder->where('user_id', auth()->id()));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(LinkLog::class);
    }

    public static function generateUniqueSref(int $length = 6): string
    {
        do {
            $sref = preg_replace('{\W+}us', '', base64_encode(random_bytes($length)));
        } while (empty($sref) || self::where('sref', $sref)->exists());
        
        return $sref;
    }
}