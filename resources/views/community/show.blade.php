<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 py-2" dir="rtl">
            <a href="{{ route('community.index') }}" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                <i class="bi bi-arrow-right fs-5"></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('View Post') }}</h2>
                <p class="text-xs text-gray-500">{{ __('Engage and share knowledge') }}</p>
            </div>
        </div>
    </x-slot>

    <!-- Alpine Post Component -->
    <div x-data="postComponent({{ $post->id }}, {{ $post->likes->count() }}, {{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ json_encode($post->comments) }})" class="py-10 px-4" dir="rtl">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Post Header -->
                <div class="p-6 pb-0">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 flex items-center justify-center bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 font-bold text-xl border border-green-100 dark:border-green-800">
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
                                    <span class="px-2 py-0.5 text-[10px] font-bold {{ $badge['bg'] }} dark:bg-opacity-20">{{ $badge['text'] }}</span>
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
                        <div class="mb-6 border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-center overflow-hidden shadow-sm">
                            <img src="{{ asset('storage/' . $post->image_path) }}" class="max-w-full max-h-[600px] object-contain shadow-inner" alt="Post content">
                        </div>
                    @endif

                    <!-- Stats Bar -->
                    <div class="flex justify-between items-center py-3 border-y border-gray-50 dark:border-gray-700 mb-2 px-1">
                        <div class="text-xs text-gray-500 flex items-center gap-1.5">
                            <span class="w-5 h-5 flex items-center justify-center bg-red-50 dark:bg-red-900/20 rounded-full">
                                <i class="bi bi-heart-fill text-red-500 text-[10px]"></i>
                            </span>
                            <span x-text="likesCount"></span> {{ __('likes') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            <span x-text="comments.length"></span> {{ __('comments') }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <button type="button" 
                            @click="toggleLike"
                            :class="isLiked ? 'text-red-500 font-bold bg-red-50 dark:bg-red-900/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700'"
                            class="flex-1 flex items-center justify-center gap-2 py-2.5 transition text-sm">
                            <i class="bi" :class="isLiked ? 'bi-heart-fill' : 'bi-heart'"></i>
                            <span>{{ __('Like') }}</span>
                        </button>
                        
                        <button type="button" @click="showComments = true" class="flex-1 flex items-center justify-center gap-2 py-2.5 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm">
                            <i class="bi bi-chat-dots"></i>
                            <span>{{ __('Comment') }}</span>
                        </button>
                        
                        <button type="button" @click="sharePost" class="flex-1 flex items-center justify-center gap-2 py-2.5 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm">
                            <i class="bi bi-share"></i>
                            <span>{{ __('Share') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Sheet Comments (Alpine) -->
        <div x-show="showComments" 
             x-transition:enter="transition-all duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-all duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[60] flex flex-col items-center" 
             style="display: none;">
            
            <!-- Backdrop -->
            <div @click="showComments = false" class="absolute inset-0 bg-black/40"></div>
            
            <!-- Sheet -->
            <div x-show="showComments"
                 x-transition:enter="transition-transform duration-300"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition-transform duration-300"
                 x-transition:leave-start="translate-y-0"
                 x-transition:leave-end="translate-y-full"
                 class="absolute bottom-0 left-0 w-full h-[85%] lg:h-[80%] lg:w-1/2 lg:left-1/4 bg-white dark:bg-gray-800 shadow-2xl flex flex-col">
                
                <!-- Header -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-1 bg-gray-200 dark:bg-gray-700 rounded-full my-3"></div>
                    <div class="flex items-center justify-between w-full px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('Comments') }}</h3>
                        <button @click="showComments = false" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
                
                <!-- List -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4" dir="rtl">
                    <template x-if="comments.length === 0">
                        <div class="text-center py-10">
                            <i class="bi bi-chat-dots text-4xl text-gray-200 dark:text-gray-700 mb-2 block"></i>
                            <p class="text-gray-400 text-sm">{{ __('No comments yet. Be the first to comment!') }}</p>
                        </div>
                    </template>

                    <template x-for="comment in comments" :key="comment.id">
                        <div class="flex gap-3">
                            <div class="shrink-0 w-8 h-8 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-xs font-bold text-gray-600 dark:text-gray-300" x-text="comment.user ? comment.user.name.charAt(0) : 'ز'"></div>
                            <div class="flex-1">
                                <div class="bg-gray-100 dark:bg-gray-700/50 p-3 rounded-2xl rounded-tr-none">
                                    <div class="font-bold text-xs text-gray-900 dark:text-white mb-1" x-text="comment.user ? comment.user.name : 'زائر ريفي'"></div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300" x-text="comment.content"></p>
                                </div>
                                <div class="flex items-center gap-4 mt-1 mr-2 text-[10px] text-gray-400">
                                    <span>{{ __('Just now') }}</span>
                                    <button @click="setReply(comment.id, comment.user ? comment.user.name : 'زائر ريفي')" class="font-bold hover:text-green-600 transition">{{ __('Reply') }}</button>
                                </div>

                                <!-- Replies -->
                                <div x-show="comment.replies && comment.replies.length > 0" class="mt-2 space-y-3 mr-2 border-r-2 border-gray-100 dark:border-gray-700 pr-3">
                                    <template x-for="reply in comment.replies" :key="reply.id">
                                        <div class="flex gap-2">
                                            <div class="shrink-0 w-6 h-6 flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-[10px] font-bold text-gray-600 dark:text-gray-300" x-text="reply.user ? reply.user.name.charAt(0) : 'ز'"></div>
                                            <div class="flex-1 bg-gray-50 dark:bg-gray-700/30 p-2">
                                                <div class="font-bold text-[10px] text-gray-900 dark:text-white mb-0.5" x-text="reply.user ? reply.user.name : 'زائر ريفي'"></div>
                                                <p class="text-xs text-gray-700 dark:text-gray-300" x-text="reply.content"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Input -->
                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <form @submit.prevent="postComment" dir="rtl">
                        <div x-show="parentId" class="text-xs text-green-600 mb-2 flex items-center gap-2" style="display: none;">
                            <span>{{ __('Replying to') }} <span x-text="replyingTo" class="font-bold"></span></span>
                            <button type="button" class="text-red-500 hover:text-red-700" @click="parentId = null"><i class="bi bi-x-circle"></i></button>
                        </div>
                        
                        <div class="flex gap-2">
                            <textarea x-model="newComment" class="flex-1 bg-gray-100 dark:bg-gray-700 border-0 px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 dark:text-white resize-none" rows="1" placeholder="{{ __('Write a comment...') }}" required></textarea>
                            <button type="submit" class="w-10 h-10 bg-green-600 text-white flex items-center justify-center hover:bg-green-700 transition shadow-sm" :disabled="!newComment.trim()">
                                <i class="bi bi-send-fill text-xs {{ app()->getLocale() == 'ar' ? 'rotate-180' : '' }}"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function postComponent(postId, initialLikes, initialIsLiked, initialComments) {
            return {
                postId: postId,
                likesCount: initialLikes,
                isLiked: initialIsLiked,
                comments: initialComments || [],
                showComments: false,
                newComment: '',
                parentId: null,
                replyingTo: '',

                async toggleLike() {
                    try {
                        const response = await fetch(`/community/post/${this.postId}/like`, {
                            method: 'POST',
                            headers: { 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                'Accept': 'application/json' 
                            }
                        });
                        if (response.status === 401) { window.location.href = '{{ route("login") }}'; return; }
                        const data = await response.json();
                        this.isLiked = (data.status === 'liked');
                        this.likesCount = data.likes_count;
                    } catch (error) { console.error(error); }
                },

                setReply(id, name) {
                    this.parentId = id;
                    this.replyingTo = name;
                    this.$nextTick(() => {
                        // Focus comment input
                        this.$el.querySelector('textarea').focus();
                    });
                },

                async postComment() {
                    if (!this.newComment.trim()) return;
                    try {
                        const response = await fetch(`/community/post/${this.postId}/comment`, {
                            method: 'POST',
                            headers: { 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                'Accept': 'application/json', 
                                'Content-Type': 'application/json' 
                            },
                            body: JSON.stringify({ 
                                content: this.newComment, 
                                parent_id: this.parentId 
                            })
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            const comment = data.comment;
                            
                            if (this.parentId) {
                                let parentComment = this.comments.find(c => c.id === this.parentId);
                                if (parentComment) {
                                    if (!parentComment.replies) parentComment.replies = [];
                                    parentComment.replies.push(comment);
                                }
                            } else {
                                this.comments.push(comment);
                            }
                            
                            this.newComment = '';
                            this.parentId = null;
                        }
                    } catch (error) { console.error(error); }
                },

                sharePost() {
                    const url = window.location.href;
                    if (navigator.share) {
                        navigator.share({ title: 'منشور ريفي', text: 'شاهد هذا المنشور', url: url }).catch(console.error);
                    } else {
                        navigator.clipboard.writeText(url).then(() => alert('{{ __("Link copied!") }}'));
                    }
                }
            }
        }
    </script>
</x-app-layout>
