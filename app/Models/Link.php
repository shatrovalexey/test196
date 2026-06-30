<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Связь с пользователем
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с логами переходов
     */
    public function logs(): HasMany
    {
        return $this->hasMany(LinkLog::class);
    }

    /**
     * Генерация уникальной короткой строки
     */
    public static function generateUniqueSref(int $length = 6): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        
        do {
            $sref = '';
            for ($i = 0; $i < $length; $i++) {
                $sref .= $characters[random_int(0, $charactersLength - 1)];
            }
        } while (self::where('sref', $sref)->exists());
        
        return $sref;
    }
}