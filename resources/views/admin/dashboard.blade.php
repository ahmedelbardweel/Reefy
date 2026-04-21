<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="h4 font-weight-bold mb-0 text-gray-800 dark:text-gray-200">
                <i class="fas fa-chart-line me-2 text-green-500"></i> {{ __('Comprehensive Admin Center') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- OVERVIEW TAB -->
            <div x-show="$store.admin.activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <!-- Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-2xl">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-400 dark:text-gray-500 font-bold mb-1">{{ __('Total Farmers') }}</p>
                        <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ $farmersCount }}</h3>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-2xl">
                                <i class="fas fa-user-tie text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-400 dark:text-gray-500 font-bold mb-1">{{ __('Manage Expert Council') }}</p>
                        <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ $expertsCount }}</h3>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-2xl">
                                <i class="fas fa-paper-plane text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-400 dark:text-gray-500 font-bold mb-1">{{ __('Recent Posts') }}</p>
                        <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ $latestPosts->count() }}</h3>
                    </div>
                </div>

                <!-- Recent Activity Grid (Latest 3) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Recent Farmers -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-5 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/30 dark:bg-gray-900/10">
                            <h5 class="font-bold text-gray-900 dark:text-white text-sm">{{ __('Latest Farmers') }}</h5>
                            <button @click="$store.admin.activeTab = 'farmers'" class="text-[10px] font-black uppercase tracking-wider text-green-600 hover:text-green-700 transition-colors">{{ __('View All') }}</button>
                        </div>
                        <div class="divide-y divide-gray-50 dark:divide-gray-700">
                            @foreach($latestFarmers as $farmer)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors flex items-center gap-3">
                                <div class="h-10 w-10 min-w-[2.5rem] rounded-xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center font-bold text-xs ring-1 ring-green-100 dark:ring-green-900/50">
                                    {{ $farmer->initials }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $farmer->name }}</div>
                                    <div class="text-[10px] text-gray-400 dark:text-gray-500 truncate">{{ $farmer->email }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Experts -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-5 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/30 dark:bg-gray-900/10">
                            <h5 class="font-bold text-gray-900 dark:text-white text-sm">{{ __('Latest Experts') }}</h5>
                            <button @click="$store.admin.activeTab = 'experts'" class="text-[10px] font-black uppercase tracking-wider text-blue-600 hover:text-blue-700 transition-colors">{{ __('View All') }}</button>
                        </div>
                        <div class="divide-y divide-gray-50 dark:divide-gray-700">
                            @foreach($latestExperts as $expert)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors flex items-center gap-3">
                                <div class="h-10 w-10 min-w-[2.5rem] rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs ring-1 ring-blue-100 dark:ring-blue-900/50">
                                    {{ $expert->initials }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $expert->name }}</div>
                                    <div class="text-[10px] text-gray-400 dark:text-gray-500 truncate">{{ $expert->email }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-5 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/30 dark:bg-gray-900/10">
                            <h5 class="font-bold text-gray-900 dark:text-white text-sm">{{ __('Latest Posts') }}</h5>
                            <button @click="$store.admin.activeTab = 'moderation'" class="text-[10px] font-black uppercase tracking-wider text-amber-600 hover:text-amber-700 transition-colors">{{ __('View All') }}</button>
                        </div>
                        <div class="divide-y divide-gray-50 dark:divide-gray-700">
                            @foreach($latestPosts as $post)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="h-5 w-5 rounded-md bg-gray-100 dark:bg-gray-700 text-[8px] flex items-center justify-center font-black">{{ $post->user->initials }}</div>
                                    <span class="text-[10px] font-bold text-gray-700 dark:text-gray-300">{{ $post->user->name }}</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">{{ $post->content }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- FARMERS TAB -->
            <div x-show="$store.admin.activeTab === 'farmers'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/20 dark:bg-gray-900/10">
                        <h4 class="font-black text-gray-900 dark:text-white text-lg">{{ __('Manage Farmers') }}</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-sm">
                            <thead class="bg-gray-50/50 dark:bg-gray-900/50 text-gray-400 font-black uppercase tracking-widest text-[10px]">
                                <tr>
                                    <th class="px-8 py-4">{{ __('Farmer') }}</th>
                                    <th class="px-8 py-4">{{ __('Contact') }}</th>
                                    <th class="px-8 py-4 text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($farmers as $farmer)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-green-100 to-emerald-100 text-green-600 flex items-center justify-center font-bold me-3">
                                                {{ $farmer->initials }}
                                            </div>
                                            <div class="font-black text-gray-900 dark:text-white">{{ $farmer->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 text-gray-600 dark:text-gray-400 font-medium">{{ $farmer->email }}</td>
                                    <td class="px-8 py-4 flex justify-center space-x-2 space-x-reverse">
                                        <a href="{{ route('farmer.profile.public', $farmer->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $farmer->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this farmer?') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                        {{ $farmers->links() }}
                    </div>
                </div>
            </div>

            <!-- EXPERTS TAB -->
            <div x-show="$store.admin.activeTab === 'experts'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/20 dark:bg-gray-900/10">
                        <h4 class="font-black text-gray-900 dark:text-white text-lg">{{ __('Manage Expert Council') }}</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-sm">
                            <thead class="bg-gray-50/50 dark:bg-gray-900/50 text-gray-400 font-black uppercase tracking-widest text-[10px]">
                                <tr>
                                    <th class="px-8 py-4">{{ __('Expert') }}</th>
                                    <th class="px-8 py-4">{{ __('Contact') }}</th>
                                    <th class="px-8 py-4 text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($experts as $expert)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold me-3">
                                                {{ $expert->initials }}
                                            </div>
                                            <div class="font-black text-gray-900 dark:text-white">{{ $expert->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 text-gray-600 dark:text-gray-400 font-medium">{{ $expert->email }}</td>
                                    <td class="px-8 py-4 flex justify-center space-x-2 space-x-reverse">
                                        <a href="{{ route('expert.profile.public', $expert->id) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $expert->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this expert?') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                        {{ $experts->links() }}
                    </div>
                </div>
            </div>

            <!-- MODERATION TAB -->
            <div x-show="$store.admin.activeTab === 'moderation'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 dark:border-gray-700 font-black bg-gray-50/20 dark:bg-gray-900/10">{{ __('Content Moderation') }}</div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($recentPosts as $post)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex gap-4">
                                    <div class="h-10 w-10 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center font-black text-xs text-gray-500">{{ $post->user->initials }}</div>
                                    <div>
                                        <div class="font-black text-gray-900 dark:text-white text-sm">{{ $post->user->name }}</div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">{{ $post->content }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this post permanently?') }}')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-orange-500 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-xl transition-all">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                        {{ $recentPosts->links() }}
                    </div>
                </div>
            </div>

            <!-- PUBLISHING TAB -->
            <div x-show="$store.admin.activeTab === 'publishing'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Community Post -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="p-3 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 rounded-2xl">
                                <i class="fas fa-paper-plane text-xl"></i>
                            </div>
                            <h4 class="font-black text-gray-900 dark:text-white text-xl">{{ __('Community Announcement') }}</h4>
                        </div>
                        <form action="{{ route('admin.publish_post') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Announcement Content') }}</label>
                                <textarea name="content" rows="4" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 focus:ring-purple-500 text-sm p-4" placeholder="{{ __('Write announcement details here...') }}"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-black py-4 rounded-2xl transition-all shadow-xl shadow-purple-200 dark:shadow-none">{{ __('Publish Now') }}</button>
                        </form>
                    </div>

                    <!-- Technical Instructions -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-2xl">
                                <i class="fas fa-lightbulb text-xl"></i>
                            </div>
                            <h4 class="font-black text-gray-900 dark:text-white text-xl">{{ __('Technical Instructions') }}</h4>
                        </div>
                        <form action="{{ route('admin.publish_instruction') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Instruction Title') }}</label>
                                <input type="text" name="title" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 focus:ring-amber-500 text-sm p-4" placeholder="{{ __('e.g., How to prune trees...') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Technical Content') }}</label>
                                <textarea name="content" rows="4" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 focus:ring-amber-500 text-sm p-4" placeholder="{{ __('Explain technical steps in detail...') }}"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-4 rounded-2xl transition-all shadow-xl shadow-amber-200 dark:shadow-none">{{ __('Save and Publish Instructions') }}</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
