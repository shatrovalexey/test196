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
        static::addGlobalScope('user', function (Builder $builder) {
            // Получаем ID пользователя из сессии
            $userId = session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
            
            Log::info('Global scope - session user_id:', ['user_id' => $userId]);
            
            if ($userId) {
                $builder->where('user_id', $userId);
            }
            // Если нет user_id - ничего не показываем
            else {
                $builder->whereRaw('1 = 0');
            }
        });
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