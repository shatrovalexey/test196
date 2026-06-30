<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Информация о пользователе -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Аватар -->
                    <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                        {{ $this->getUser()?->name ? substr($this->getUser()->name, 0, 2) : 'U' }}
                    </div>
                    
                    <!-- Информация -->
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

        <!-- Статистика -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Всего ссылок</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $this->getTotalLinks() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Всего переходов</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $this->getTotalClicks() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Популярная ссылка</h3>
                        @php
                            $mostClicked = $this->getMostClickedLinks()->first();
                        @endphp
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]">
                            {{ $mostClicked ? $mostClicked->href : 'Нет данных' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Последние ссылки -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Последние ссылки</h3>
                    <a href="{{ \App\Filament\App\Resources\LinkResource::getUrl() }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Все ссылки →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($this->getRecentLinks() as $link)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-1">
                                <p class="text-sm text-gray-900 dark:text-white truncate max-w-[400px]">
                                    {{ $link->href }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Переходов: {{ $link->logs_count }} | {{ $link->created_at->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded">
                                    {{ $link->sref }}
                                </span>
                                <a href="{{ url('/l/' . $link->sref) }}" target="_blank" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                <p>У вас пока нет ссылок</p>
                                <a href="{{ \App\Filament\App\Resources\LinkResource::getUrl('create') }}" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Создать первую ссылку
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>