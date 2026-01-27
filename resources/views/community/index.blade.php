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
                <i class="bi bi-broadcast animate-pulse"></i> {{ __('Live Now') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Feed Column -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Create Post Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 p-4" x-data="{ postType: 'post' }">
                    <div class="flex gap-3">
                        <div class="shrink-0">
                            <div class="w-10 h-10 bg-green-100 text-green-700 flex items-center justify-center font-bold text-lg dark:bg-green-900 dark:text-green-300">
                                @auth
                                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                                @else
                                    <i class="bi bi-person"></i>
                                @endauth
                            </div>
                        </div>
                        <div class="flex-grow">
                            <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" x-model="postType">
                                
                                <textarea name="content" class="w-full border-0 bg-gray-50 dark:bg-gray-700/50 p-3 text-sm focus:ring-1 focus:ring-green-500 resize-none dark:text-white placeholder-gray-400" rows="3" placeholder="{{ auth()->check() ? __('What is new in your farm today, :name?', ['name' => auth()->user()->name]) : __('Share your experience with the community now...') }}"></textarea>
                                
                                <div id="imagePreviewContainer" class="mt-3 hidden relative group">
                                    <img id="imagePreview" src="#" alt="Preview" class="w-full max-h-60 object-cover border border-gray-100 dark:border-gray-600">
                                    <button type="button" class="absolute top-2 left-2 bg-red-500 text-white p-1 opacity-0 group-hover:opacity-100 transition" onclick="removeImage()">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>

                                <div class="flex flex-wrap items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 gap-2">
                                    <div class="flex gap-2 text-[11px] overflow-x-auto no-scrollbar pb-1 max-w-full">
                                        <button type="button" @click="postType = 'post'" :class="postType === 'post' ? 'bg-green-50 text-green-700 border-green-200 shadow-sm' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'" class="flex items-center gap-1 px-3 py-1.5 border transition whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                            <i class="bi bi-chat-left-text"></i> {{ __('Post') }}
                                        </button>
                                        <button type="button" @click="postType = 'question'" :class="postType === 'question' ? 'bg-blue-50 text-blue-700 border-blue-200 shadow-sm' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'" class="flex items-center gap-1 px-3 py-1.5 rounded-full border transition whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                            <i class="bi bi-question-circle"></i> {{ __('Question') }}
                                        </button>
                                        <button type="button" @click="postType = 'tip'" :class="postType === 'tip' ? 'bg-yellow-50 text-yellow-700 border-yellow-200 shadow-sm' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'" class="flex items-center gap-1 px-3 py-1.5 rounded-full border transition whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                            <i class="bi bi-lightbulb"></i> {{ __('Tip') }}
                                        </button>
                                        <label class="flex items-center gap-1 px-3 py-1.5 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 cursor-pointer transition whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                            <i class="bi bi-image text-green-600"></i> {{ __('Image') }}
                                            <input type="file" name="image" id="postImage" class="hidden" accept="image/*" onchange="previewImage(this)">
                                        </label>
                                    </div>
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-1.5 text-xs font-bold shadow-sm transition">{{ __('Share') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Posts List -->
                @forelse($posts as $post)
                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 flex items-center justify-center font-bold text-gray-600 dark:text-gray-300">
                                        {{ $post->user ? mb_substr($post->user->name, 0, 1) : 'ز' }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h6 class="font-bold text-sm text-gray-900 dark:text-white">{{ $post->user ? $post->user->name : 'زائر ريفي' }}</h6>
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
                                        <span class="text-[10px] text-gray-400 block mt-0.5">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                @if(auth()->id() === $post->user_id)
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-three-dots-vertical"></i></button>
                                        <div x-show="open" class="absolute left-0 mt-1 w-32 bg-white dark:bg-gray-700 shadow-lg py-1 border border-gray-100 dark:border-gray-600 z-10" x-cloak>
                                            <form action="{{ route('community.destroy', $post) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full text-start px-4 py-2 text-xs text-red-500 hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('Delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed mb-3 whitespace-pre-wrap">{{ $post->content }}</p>

                            @if($post->image_path)
                                <div class="overflow-hidden mb-3 border border-gray-100 dark:border-gray-700">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" class="w-full max-h-96 object-cover" alt="Post content">
                                </div>
                            @endif

                             <!-- Stats -->
                             <div class="flex items-center justify-between text-xs text-gray-500 py-2 border-b border-gray-50 dark:border-gray-700">
                                <div class="flex items-center gap-1">
                                    <div class="flex -space-x-1 space-x-reverse">
                                        <div class="w-4 h-4 bg-red-100 flex items-center justify-center border border-white"><i class="bi bi-heart-fill text-[8px] text-red-500"></i></div>
                                    </div>
                                    <span id="likesCount{{ $post->id }}">{{ $post->likes->count() }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span id="commentsCount{{ $post->id }}">{{ $post->comments->count() }}</span> {{ __('comments') }}
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center pt-2">
                                 <button type="button" 
                                    onclick="toggleLike({{ $post->id }})"
                                    id="likeBtn{{ $post->id }}"
                                    class="flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm transition {{ $post->isLikedBy(auth()->user()) ? 'text-red-500 font-bold bg-red-50 dark:bg-red-900/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                    <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                    <span>{{ __('Like') }}</span>
                                </button>
                                <button type="button" onclick="openComments({{ $post->id }})" class="flex-1 flex items-center justify-center gap-2 py-2 text-sm text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <i class="bi bi-chat"></i>
                                    <span>{{ __('Comment') }}</span>
                                </button>
                                <button type="button" onclick="sharePost({{ $post->id }}, '{{ url('/community/post/' . $post->id) }}')" class="flex-1 flex items-center justify-center gap-2 py-2 text-sm text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <i class="bi bi-share"></i>
                                    <span>{{ __('Share') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i class="bi bi-chat-dots text-4xl text-gray-300 dark:text-gray-600 mb-3 block"></i>
                        <h4 class="text-gray-500 dark:text-gray-400 font-medium">{{ __('No posts yet') }}</h4>
                        <p class="text-xs text-gray-400 mt-1">{{ __('Be the first to share your experience in Reefy community!') }}</p>
                    </div>
                @endforelse

                <div class="py-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Sheet Comments (Alpine.js State handled in layout for visibility but here tailored for Tailwind) -->
    <div id="commentBottomSheet" class="fixed inset-0 z-50 invisible transition-all duration-300" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-300 overlay" onclick="closeComments()"></div>
        
        <!-- Sheet -->
        <div class="absolute bottom-0 left-0 w-full h-[85%] lg:h-[80%] lg:w-1/2 lg:left-1/4 bg-white dark:bg-gray-800 shadow-2xl transform translate-y-full transition-transform duration-300 flex flex-col sheet-content">
            <!-- Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('Comments') }}</h3>
                <button onclick="closeComments()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"><i class="bi bi-x-lg"></i></button>
            </div>
            
            <!-- List -->
            <div id="commentsList" class="flex-1 overflow-y-auto p-4 space-y-4">
                <!-- Loaded via JS -->
            </div>

            <!-- Input -->
            <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                <form onsubmit="postComment(event)" id="commentForm">
                    @csrf
                    <input type="hidden" name="post_id" id="sheetPostId">
                    <input type="hidden" name="parent_id" id="sheetParentId">
                    
                    <div id="replyingToIndicator" class="hidden text-xs text-green-600 mb-2 flex items-center gap-2">
                        <span>جاري الرد على <span id="replyingToName" class="font-bold"></span></span>
                        <button type="button" class="text-red-500 hover:text-red-700" onclick="cancelReply()"><i class="bi bi-x-circle"></i></button>
                    </div>
                    
                    <div class="flex gap-2">
                        <input type="text" name="content" id="commentInput" class="flex-1 bg-gray-100 dark:bg-gray-700 border-0 px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 dark:text-white" placeholder="{{ __('Write a comment...') }}" required autocomplete="off">
                        <button type="submit" class="w-10 h-10 bg-green-600 text-white flex items-center justify-center hover:bg-green-700 transition shadow-sm transform rotate-180">
                            <i class="bi bi-send-fill text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dynamic Interactions -->
    <script>
        // Image Preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('imagePreview');
                    img.src = e.target.result;
                    document.getElementById('imagePreviewContainer').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('postImage').value = "";
            document.getElementById('imagePreviewContainer').classList.add('hidden');
        }

        // Like Logic
        async function toggleLike(postId) {
            try {
                const response = await fetch(`/community/post/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.status === 401) {
                    window.location.href = '{{ route("login") }}';
                    return;
                }

                const data = await response.json();
                const btn = document.getElementById(`likeBtn${postId}`);
                const icon = btn.querySelector('i');
                const countSpan = document.getElementById(`likesCount${postId}`);

                if (data.liked) {
                    btn.className = "flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm transition text-red-500 font-bold bg-red-50 dark:bg-red-900/20";
                    icon.className = "bi bi-heart-fill";
                } else {
                    btn.className = "flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm transition text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700";
                    icon.className = "bi bi-heart";
                }
                countSpan.innerText = data.likes_count;
            } catch (error) {
                console.error('Error toggling like:', error);
            }
        }

        // Comments System
        let currentPostsData = @json($posts->items());
        const sheet = document.getElementById('commentBottomSheet');
        const overlay = sheet.querySelector('.overlay');
        const content = sheet.querySelector('.sheet-content');

        function openComments(postId) {
            const list = document.getElementById('commentsList');
            const postIdInput = document.getElementById('sheetPostId');
            
            postIdInput.value = postId;
            list.innerHTML = '<div class="flex justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div></div>';
            
            sheet.classList.remove('invisible');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                content.classList.remove('translate-y-full');
            }, 10);
            
            document.body.style.overflow = 'hidden';

            // Load comments
            const post = currentPostsData.find(p => p.id === postId);
            renderComments(post.comments);
        }

        function closeComments() {
            overlay.classList.add('opacity-0');
            content.classList.add('translate-y-full');
            
            setTimeout(() => {
                sheet.classList.add('invisible');
                document.body.style.overflow = 'auto';
                cancelReply();
            }, 300);
        }

        function renderComments(comments) {
            const list = document.getElementById('commentsList');
            if (comments.length === 0) {
                list.innerHTML = '<div class="text-center py-8 text-gray-400 text-sm">لا توجد تعليقات بعد. كن أول من يعلق!</div>';
                return;
            }

            let html = '';
            comments.forEach(comment => {
                const userInitial = comment.user ? comment.user.name.charAt(0) : 'ز';
                const userName = comment.user ? comment.user.name : 'زائر ريفي';
                
                html += `
                    <div class="flex gap-3">
                        <div class="shrink-0 w-8 h-8 bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300">${userInitial}</div>
                        <div class="flex-1">
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-2xl rounded-tr-none">
                                <div class="font-bold text-xs text-gray-900 dark:text-gray-100 mb-1">${userName}</div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">${comment.content}</p>
                            </div>
                            <div class="flex items-center gap-4 mt-1 mr-2 text-[10px] text-gray-400">
                                <span>منذ فترة</span>
                                <button onclick="setReply(${comment.id}, '${userName}')" class="font-bold hover:text-green-600 transition">رد</button>
                            </div>
                            <!-- Replies -->
                            <div class="mt-2 space-y-2 mr-2 border-r-2 border-gray-100 dark:border-gray-700 pr-3">
                                ${renderReplies(comment.replies || [])}
                            </div>
                        </div>
                    </div>
                `;
            });
            list.innerHTML = html;
        }

        function renderReplies(replies) {
            if (!replies.length) return '';
            return replies.map(reply => `
                <div class="flex gap-2">
                    <div class="shrink-0 w-6 h-6 bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-[10px] font-bold text-gray-600 dark:text-gray-300">${reply.user ? reply.user.name.charAt(0) : 'ز'}</div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-2">
                        <div class="font-bold text-[10px] text-gray-900 dark:text-gray-100">${reply.user ? reply.user.name : 'زائر ريفي'}</div>
                        <p class="text-xs text-gray-700 dark:text-gray-300">${reply.content}</p>
                    </div>
                </div>
            `).join('');
        }

        function setReply(commentId, userName) {
            document.getElementById('sheetParentId').value = commentId;
            document.getElementById('replyingToName').innerText = userName;
            document.getElementById('replyingToIndicator').classList.remove('hidden');
            document.getElementById('commentInput').focus();
        }

        function cancelReply() {
            document.getElementById('sheetParentId').value = '';
            document.getElementById('replyingToIndicator').classList.add('hidden');
        }

        async function postComment(e) {
            e.preventDefault();
            const postId = document.getElementById('sheetPostId').value;
            const content = document.getElementById('commentInput').value;
            const parentId = document.getElementById('sheetParentId').value;

            try {
                const response = await fetch(`/community/post/${postId}/comment`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ content, parent_id: parentId })
                });

                if (response.ok) {
                    const data = await response.json();
                    const comment = data.comment;
                    
                    document.getElementById('commentInput').value = '';
                    cancelReply();
                    
                    // Update Local State
                    const post = currentPostsData.find(p => p.id == postId);
                    if (parentId) {
                        const parent = post.comments.find(c => c.id == parentId);
                        if (!parent.replies) parent.replies = [];
                        parent.replies.push(comment);
                    } else {
                        post.comments.push(comment);
                    }
                    
                    renderComments(post.comments);
                    
                    // Update Counter
                    const counter = document.getElementById(`commentsCount${postId}`);
                    if (counter) counter.innerText = post.comments.length + countReplies(post.comments);
                }
            } catch (error) {
                console.error('Error posting comment:', error);
            }
        }

        function countReplies(comments) {
            return comments.reduce((acc, comment) => acc + (comment.replies ? comment.replies.length : 0), 0);
        }

        function sharePost(postId, url) {
             if (navigator.share) {
                navigator.share({
                    title: 'منشور ريفي',
                    text: 'شاهد هذا المنشور على مجتمع ريفي',
                    url: url
                }).catch(console.error);
            } else {
                navigator.clipboard.writeText(url).then(() => alert('تم نسخ الرابط!'));
            }
        }
    </script>
</x-app-layout>
