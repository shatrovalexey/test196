<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Создаем роли
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        Role::create(['name' => 'super_admin']);
    }

    public function down(): void
    {
        // Удаляем роли
        \Spatie\Permission\Models\Role::whereIn('name', ['admin', 'user', 'super_admin'])->delete();
    }
};