<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold mb-1 text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-people-fill text-green-600"></i> {{ __('Reefy Community') }}
                </h2>
                <p class="text-xs text-gray-500">{{ __('Connect with farmers and share your experiences from the land') }}</p>
            </div>
            <div class="px-3 py-1 bg-green-50 text-green-700 text-xs font-bold border border-green-100 flex items-center gap-1 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                <i class="bi bi-broadcast"></i> {{ __('Live Now') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4">
        <div class="max-w-6xl mx-auto space-y-6">

            <!-- Create Post Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                <div class="flex gap-3">
                    <div class="shrink-0">
                        <div class="w-10 h-10 bg-green-100 text-green-700 flex items-center justify-center font-bold text-lg dark:bg-green-900 dark:text-green-300">
                            @auth
                                {{ mb_substr(auth()->user()->name, 0, 2) }}
                            @else
                                <i class="bi bi-person"></i>
                            @endauth
                        </div>
                    </div>
                    <div class="flex-grow">
                        <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <select name="type" class="text-xs bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 dark:text-gray-300 focus:ring-green-500 rounded-lg">
                                    <option value="post">{{ __('Post') }}</option>
                                    <option value="question">{{ __('Question') }}</option>
                                    <option value="tip">{{ __('Tip') }}</option>
                                </select>
                            </div>
                            <textarea name="content" class="w-full border-0 bg-gray-50 dark:bg-gray-700/50 p-3 text-sm focus:ring-1 focus:ring-green-500 resize-none dark:text-white placeholder-gray-400 rounded-lg" rows="3" placeholder="{{ auth()->check() ? __('What is new in your farm today, :name?', ['name' => auth()->user()->name]) : __('Share your experience with the community now...') }}"></textarea>
                            <div class="flex flex-wrap items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 gap-2">
                                <div class="flex gap-2">
                                    <label class="flex items-center gap-1 px-3 py-1.5 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 cursor-pointer transition whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 text-xs">
                                        <i class="bi bi-image text-green-600"></i> {{ __('Image') }}
                                        <input type="file" name="image" id="postImage" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-1.5 text-xs font-bold shadow-sm transition rounded-lg">{{ __('Share') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Posts List -->
            @forelse($posts as $post)
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden rounded-xl p-4">

                    <!-- Post Header -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 flex items-center justify-center font-bold text-gray-600 dark:text-gray-300 rounded-full">
                                {{ $post->user ? mb_substr($post->user->name, 0, 2) : 'ز' }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h6 class="font-bold text-sm text-gray-900 dark:text-white">{{ $post->user ? $post->user->name : 'زائر ريفي' }}</h6>
                                    @php
                                        $badges = [
                                            'post' => ['bg' => 'bg-gray-100 text-gray-600', 'text' => __('Post')],
                                            'question' => ['bg' => 'bg-blue-50 text-blue-600', 'text' => __('Question')],
                                            'tip' => ['bg' => 'bg-yellow-50 text-yellow-600', 'text' => __('Tip')],
                                        ];
                                        $badge = $badges[$post->type] ?? $badges['post'];
                                    @endphp
                                    <span class="px-2 py-0.5 text-[10px] font-bold {{ $badge['bg'] }} rounded-full dark:bg-opacity-20">{{ $badge['text'] }}</span>
                                </div>
                                <span class="text-[10px] text-gray-400 block mt-0.5">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if(auth()->id() === $post->user_id)
                            <form action="{{ route('community.destroy', $post) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600"><i class="bi bi-trash"></i></button>
                            </form>
                        @endif
                    </div>

                    <!-- Post Content -->
                    <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed mb-3 whitespace-pre-wrap">{{ $post->content }}</p>
                    @if($post->image_path)
                        <div class="overflow-hidden mb-3 border border-gray-100 dark:border-gray-700 rounded-lg">
                            <img src="{{ $post->image_url }}" class="w-full max-h-96 object-cover" alt="Post content">
                        </div>
                    @endif

                    <!-- Stats -->
                    <div class="flex items-center justify-between text-xs text-gray-500 py-2 border-b border-gray-50 dark:border-gray-700">
                        <div class="flex items-center gap-1">
                            <div class="flex -space-x-1 space-x-reverse">
                                <div class="w-4 h-4 bg-red-100 flex items-center justify-center border border-white rounded-full"><i class="bi bi-heart-fill text-[8px] text-red-500"></i></div>
                            </div>
                            <span>{{ $post->likes->count() }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span>{{ $post->comments->count() }}</span> {{ __('comments') }}
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center pt-2 gap-2">
                        <form action="{{ route('community.like', $post) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-lg text-sm transition {{ $post->isLikedBy(auth()->user()) ? 'text-red-500 font-bold bg-red-50 dark:bg-red-900/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                <span>{{ __('Like') }}</span>
                            </button>
                        </form>
                            <a href="{{ url('/community/post/' . $post->id) }}" class="flex-1 flex items-center justify-center gap-2 py-2 text-sm text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition rounded-lg">
                            <span>{{ __('Comment') }}</span>
                        </a>
                        <a href="{{ url('/community/post/' . $post->id) }}" class="flex-1 flex items-center justify-center gap-2 py-2 text-sm text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition rounded-lg">
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
            @empty
                <div class="text-center py-12">
                    <i class="bi bi-chat-dots text-4xl text-gray-300 dark:text-gray-600 mb-3 block"></i>
                    <h4 class="text-gray-500 dark:text-gray-400 font-medium">{{ __('No posts yet') }}</h4>
                    <p class="text-xs text-gray-400 mt-1">{{ __('Be the first to share your experience in Reefy community!') }}</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="py-4">
                {{ $posts->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
