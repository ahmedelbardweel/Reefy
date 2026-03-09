<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 py-2" dir="rtl">
            <a href="{{ route('community.index') }}" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm rounded-lg">
                <i class="bi bi-arrow-right fs-5"></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('View Post') }}</h2>
                <p class="text-xs text-gray-500">{{ __('Engage and share knowledge') }}</p>
            </div>
        </div>
    </x-slot>



    <div class="py-10 px-4" dir="rtl">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden rounded-2xl">
                <!-- Post Header -->
                <div class="p-6 pb-0">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 flex items-center justify-center bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 font-bold text-xl border border-green-100 dark:border-green-800 rounded-full">
                                {{ $post->user ? mb_substr($post->user->name, 0, 1) : 'ز' }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h6 class="font-bold text-gray-900 dark:text-white">{{ $post->user ? $post->user->name : 'زائر ريفي' }}</h6>
                                    @php
                                        $badges = [
                                            'post' => ['bg' => 'bg-gray-100 text-gray-600', 'text' => __('Post')],
                                            'question' => ['bg' => 'bg-blue-50 text-blue-600', 'text' => __('Question')],
                                            'inquiry' => ['bg' => 'bg-cyan-50 text-cyan-600', 'text' => __('Inquiry')],
                                            'tip' => ['bg' => 'bg-yellow-50 text-yellow-600', 'text' => __('Tip')],
                                            'poll' => ['bg' => 'bg-red-50 text-red-600', 'text' => __('Poll')],
                                        ];
                                        $badge = $badges[$post->type] ?? $badges['post'];
                                    @endphp
                                    <span class="px-2 py-0.5 text-[10px] font-bold {{ $badge['bg'] }} rounded-full dark:bg-opacity-20">{{ $badge['text'] }}</span>
                                </div>
                                <div class="text-[11px] text-gray-400 mt-0.5">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Post Body -->
                <div class="p-6">
                    <p class="text-lg text-gray-800 dark:text-gray-200 leading-relaxed mb-6 whitespace-pre-wrap">{{ $post->content }}</p>

                    @if($post->image_path)
                        <div class="mb-6 border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-center overflow-hidden shadow-sm rounded-xl">
                            <img src="{{ $post->image_url }}" class="max-w-full max-h-[600px] object-contain shadow-inner" alt="Post content">
                        </div>
                    @endif

                    <!-- Stats Bar -->
                    <div class="flex justify-between items-center py-3 border-y border-gray-50 dark:border-gray-700 mb-2 px-1">
                        <div class="text-xs text-gray-500 flex items-center gap-1.5">
                            <span class="w-5 h-5 flex items-center justify-center bg-red-50 dark:bg-red-900/20 rounded-full">
                                <i class="bi bi-heart-fill text-red-500 text-[10px]"></i>
                            </span>
                            <span>{{ $post->likes->count() }}</span> {{ __('likes') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            <span>{{ $post->comments->count() }}</span> {{ __('comments') }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <form action="{{ route('community.like', $post) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl transition text-sm {{ $post->isLikedBy(auth()->user()) ? 'text-red-500 font-bold bg-red-50 dark:bg-red-900/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                <span>{{ __('Like') }}</span>
                            </button>
                        </form>

                        <a href="#commentsModal" class="flex-1 flex items-center justify-center gap-2 py-2.5 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm rounded-xl">
                            <i class="bi bi-chat-dots"></i>
                            <span>{{ __('Comment') }}</span>
                        </a>

                        <a href="{{ url('/community/post/' . $post->id) }}" class="flex-1 flex items-center justify-center gap-2 py-2.5 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm rounded-xl">
                            <i class="bi bi-share"></i>
                            <span>{{ __('Share') }}</span>
                        </a>
                    </div>
                                        <!-- Last 3 Comments -->
                    <div class="mt-3 space-y-2">
                        @php
                            $comments = $post->comments()->whereNull('parent_id')->latest()->take(3)->get();
                        @endphp
                        @foreach($comments as $comment)
                            <div class="flex gap-3">
                                <div class="shrink-0 w-8 h-8 bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300 rounded-full">
                                    {{ $comment->user ? mb_substr($comment->user->name, 0, 1) : 'ز' }}
                                </div>
                                <div class="flex-1">
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-2xl rounded-tr-none">
                                        <div class="font-bold text-xs text-gray-900 dark:text-gray-100 mb-1">
                                            {{ $comment->user ? $comment->user->name : 'زائر ريفي' }}
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add Comment Form -->
                    <form action="{{ route('community.comment', $post) }}" method="POST" class="mt-2 flex gap-2">
                        @csrf
                        <input type="text" name="content" class="flex-1 bg-gray-100 dark:bg-gray-700 border-0 px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 dark:text-white rounded-lg" placeholder="{{ __('Write a comment...') }}" required autocomplete="off">
                        <button type="submit" class="w-10 h-10 bg-green-600 text-white flex items-center justify-center hover:bg-green-700 transition shadow-sm rounded-lg">
                            <i class="bi bi-send-fill text-xs"></i>
                        </button>
                    </form>
                </div>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</x-app-layout>
