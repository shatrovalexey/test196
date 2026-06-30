<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <!-- Аватар -->
            <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                {{ $this->getUser()?->name ? substr($this->getUser()->name, 0, 2) : 'U' }}
            </div>
            
            <!-- Информация о пользователе -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $this->getUser()?->name }}
                </h2>
                <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                    <div>
                        <span class="font-medium">Email:</span> 
                        {{ $this->getUser()?->email }}
                    </div>
                    <div>
                        <span class="font-medium">ID пользователя:</span> 
                        {{ $this->getUser()?->id }}
                    </div>
                    <div>
                        <span class="font-medium">Роль:</span> 
                        @php
                            $roles = $this->getUser()?->roles->pluck('name')->join(', ');
                        @endphp
                        {{ $roles ?: 'Пользователь' }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Кнопка выхода -->
        <div>
            <form method="POST" action="{{ route('filament.app.auth.logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Выход
                </button>
            </form>
        </div>
    </div>
</div>