<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center" style="direction: rtl;">
            <div>
                <h2 class="h4 text-dark mb-0 font-weight-bold">
                    <i class="bi bi-people-fill me-2 text-success"></i>مجتمع ريفي
                </h2>
                <p class="text-muted small mb-0">تواصل مع المزارعين وشارك خبراتك في الأرض</p>
            </div>
            <div class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 0.8rem;">
                <i class="bi bi-broadcast me-1"></i> مباشر الآن
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <!-- Create Post Card -->
                <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 mb-3" style="direction: rtl;">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white fw-bold" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    @auth
                                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                                    @else
                                        <i class="bi bi-person"></i>
                                    @endauth
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <select name="type" class="form-select border-0 bg-light rounded-pill px-3 mb-2" style="width: auto; font-size: 0.85rem; color: var(--reefy-success); font-weight: 600;">
                                            <option value="post">منشور عادي</option>
                                            <option value="question">سؤال</option>
                                            <option value="inquiry">استفسار</option>
                                            <option value="tip">نصيحة</option>
                                            <option value="poll">استفتاء</option>
                                        </select>
                                        <textarea name="content" class="form-control border-0 bg-light p-3" rows="3" placeholder="{{ auth()->check() ? 'ما الجديد في مزرعتك اليوم يا ' . auth()->user()->name . '؟' : 'شارك خبرتك مع المجتمع الآن...' }}" style="border-radius: 12px; resize: none; direction: rtl;"></textarea>
                                    </div>
                                    
                                    <div id="imagePreviewContainer" class="mt-3 d-none">
                                        <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded-3 shadow-sm" style="max-height: 300px; width: 100%; object-fit: cover;">
                                        <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">حذف الصورة</button>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-3" style="direction: rtl;">
                                        <div class="d-flex gap-2">
                                            <label class="btn btn-light rounded-pill px-3 py-2 mb-0" style="cursor: pointer; color: var(--reefy-success); border: 1px solid #e2eee8;">
                                                <i class="bi bi-image me-1"></i> صورة
                                                <input type="file" name="image" id="postImage" class="d-none" accept="image/*" onchange="previewImage(this)">
                                            </label>
                                            <button type="button" class="btn btn-light rounded-pill px-3 py-2 mb-0" style="color: #00aeef; border: 1px solid #e2eee8;">
                                                <i class="bi bi-geo-alt me-1"></i> الموقع
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold">نشر الآن</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posts Feed -->
                @forelse($posts as $post)
                    <div class="card border-0 shadow-sm mb-4 p-0" style="border-radius: 15px; direction: rtl;">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-success fw-bold border" style="width: 45px; height: 45px;">
                                        {{ $post->user ? mb_substr($post->user->name, 0, 1) : 'ز' }}
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6 class="mb-0 fw-bold text-dark">{{ $post->user ? $post->user->name : 'زائر ريفي' }}</h6>
                                            @php
                                                $badgeClass = [
                                                    'post' => 'bg-secondary',
                                                    'question' => 'bg-primary',
                                                    'inquiry' => 'bg-info',
                                                    'tip' => 'bg-warning',
                                                    'poll' => 'bg-danger'
                                                ][$post->type] ?? 'bg-secondary';
                                                
                                                $badgeText = [
                                                    'post' => 'منشور',
                                                    'question' => 'سؤال',
                                                    'inquiry' => 'استفسار',
                                                    'tip' => 'نصيحة',
                                                    'poll' => 'استفتاء'
                                                ][$post->type] ?? 'منشور';
                                            @endphp
                                            <span class="badge {{ $badgeClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $badgeClass) }} very-small rounded-pill">{{ $badgeText }}</span>
                                        </div>
                                        <div class="very-small text-muted">{{ $post->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @auth
                                        @if($post->user_id === auth()->id())
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                                    <li>
                                                        <form action="{{ route('community.destroy', $post) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                                                <i class="bi bi-trash"></i> حذف المنشور
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <div class="card-body px-4 pt-3">
                            <p class="text-dark mb-3" style="line-height: 1.6;">{{ $post->content }}</p>
                            
                            @if($post->image_path)
                                <div class="rounded-3 overflow-hidden mb-3 border shadow-sm">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" class="w-100" style="max-height: 400px; object-fit: cover;" alt="Post content">
                                </div>
                            @endif

                            <!-- Interaction Stats -->
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2 px-1">
                                <div class="very-small text-muted px-2">
                                    <i class="bi bi-heart-fill text-danger me-1"></i>
                                    <span id="likesCount{{ $post->id }}">{{ $post->likes->count() }}</span> إعجاب
                                </div>
                                <div class="very-small text-muted px-2">
                                    <span id="commentsCount{{ $post->id }}">{{ $post->comments->count() }}</span> تعليق
                                </div>
                            </div>

                            <!-- Interaction Buttons -->
                            <div class="d-flex gap-2">
                                <button type="button" 
                                    onclick="toggleLike({{ $post->id }})"
                                    id="likeBtn{{ $post->id }}"
                                    class="btn flex-grow-1 py-2 border-0 d-flex align-items-center justify-content-center gap-2 {{ $post->isLikedBy(auth()->user()) ? 'text-danger fw-bold bg-danger bg-opacity-10' : 'text-muted hover-bg-light' }}" style="border-radius: 10px; transition: all 0.2s;">
                                    <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                    إعجاب
                                </button>
                                
                                <button type="button" class="btn flex-grow-1 py-2 border-0 text-muted d-flex align-items-center justify-content-center gap-2 hover-bg-light" style="border-radius: 10px;" onclick="openComments({{ $post->id }})">
                                    <i class="bi bi-chat-left-text"></i>
                                    تعليق
                                </button>
                                <button type="button" class="btn flex-grow-1 py-2 border-0 text-muted d-flex align-items-center justify-content-center gap-2 hover-bg-light" style="border-radius: 10px;" onclick="sharePost({{ $post->id }}, '{{ url('/community/post/' . $post->id) }}')">
                                    <i class="bi bi-share"></i>
                                    مشاركة
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-chat-dots display-1 text-muted opacity-25"></i>
                        <h4 class="mt-3 text-muted">لا توجد منشورات حتى الآن</h4>
                        <p class="text-muted small">كن أول من يشارك خبرته في مجتمع ريفي!</p>
                    </div>
                @endforelse

                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            </div>

            <!-- Sidebar Info (Trends/Suggestions) -->
            <div class="col-lg-3 d-none d-lg-block" style="direction: rtl;">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-lightbulb text-warning me-2"></i>نصائح المجتمع</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <a href="#" class="text-decoration-none d-flex gap-2 align-items-start">
                                    <div class="badge bg-success bg-opacity-10 text-success rounded-circle p-2"><i class="bi bi-droplets"></i></div>
                                    <div>
                                        <div class="small fw-bold text-dark">أفضل وقت للري اليوم</div>
                                        <div class="very-small text-muted">بناءً على التفاعل الأخير</div>
                                    </div>
                                </a>
                            </li>
                            <li class="mb-0">
                                <a href="#" class="text-decoration-none d-flex gap-2 align-items-start">
                                    <div class="badge bg-warning bg-opacity-10 text-warning rounded-circle p-2"><i class="bi bi-bug"></i></div>
                                    <div>
                                        <div class="small fw-bold text-dark">مكافحة دودة الطماطم</div>
                                        <div class="very-small text-muted">نقاش نشط حالياً</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm sticky-top" style="border-radius: 12px; top: 120px;">
                    <div class="card-body text-center p-4">
                        <img src="{{ asset('logo.png') }}" alt="Reefy" class="mb-3 opacity-50" style="height: 40px; filter: grayscale(1);">
                        <p class="very-small text-muted mb-0">جميع حقوق النشر محفوظة لـ ريفي 2026 &copy;</p>
                        <div class="d-flex justify-content-center gap-2 mt-2">
                             <a href="#" class="text-muted very-small text-decoration-none">سياسة الخصوصية</a>
                             <a href="#" class="text-muted very-small text-decoration-none">شروط الاستخدام</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Sheet Comments -->
    <div id="commentBottomSheet" class="comment-bottom-sheet">
        <div class="sheet-overlay" onclick="closeComments()"></div>
        <div class="sheet-content">
            <div class="sheet-header">
                <div class="drag-handle"></div>
                <div class="d-flex justify-content-between align-items-center w-100 px-3 py-2 border-bottom">
                    <h6 class="mb-0 fw-bold">التعليقات</h6>
                    <button class="btn btn-link text-dark p-0" onclick="closeComments()"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            <div class="sheet-body p-3" id="commentsList" style="direction: rtl;">
                <!-- Comments will be loaded here -->
            </div>
            <div class="sheet-footer border-top p-3 bg-white">
                <form onsubmit="postComment(event)" id="commentForm" style="direction: rtl;">
                    @csrf
                    <input type="hidden" name="post_id" id="sheetPostId">
                    <input type="hidden" name="parent_id" id="sheetParentId">
                    <div id="replyingToIndicator" class="very-small text-success mb-2 d-none">
                        جاري الرد على <span id="replyingToName"></span>
                        <button type="button" class="btn btn-link very-small text-danger p-0 ms-2" onclick="cancelReply()">إلغاء</button>
                    </div>
                    <div class="d-flex gap-2">
                        <textarea name="content" id="commentInput" class="form-control rounded-4 px-3 py-2 border-0 bg-light" rows="1" placeholder="أضف تعليقاً..." required style="resize: none;"></textarea>
                        <button type="submit" class="btn btn-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-send-fill fs-6" style="transform: rotate(180deg);"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .hover-bg-light:hover { background-color: #f8fafc !important; }
        .very-small { font-size: 0.7rem; }
        .last-child-mb-0:last-child { margin-bottom: 0 !important; }
        .card { transition: transform 0.2s ease-in-out; }
        .text-info { color: #00aeef !important; }
        
        /* Bottom Sheet Styles */
        .comment-bottom-sheet {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1050;
            visibility: hidden;
            transition: visibility 0.3s;
        }
        
        .comment-bottom-sheet.show {
            visibility: visible;
        }

        .sheet-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .comment-bottom-sheet.show .sheet-overlay {
            opacity: 1;
        }

        .sheet-content {
            position: absolute;
            bottom: -100%;
            left: 0;
            width: 100%;
            height: 70%;
            background: white;
            border-radius: 20px 20px 0 0;
            transition: bottom 0.3s ease-out;
            display: flex;
            flex-direction: column;
        }

        .comment-bottom-sheet.show .sheet-content {
            bottom: 0;
        }

        .drag-handle {
            width: 40px;
            height: 5px;
            background: #e0e0e0;
            border-radius: 10px;
            margin: 10px auto;
        }

        .sheet-body {
            flex-grow: 1;
            overflow-y: auto;
        }

        /* Desktop specific Adjustments */
        @media (min-width: 992px) {
            .sheet-content {
                width: 50%;
                left: 25%;
                height: 80%;
                border-radius: 20px 20px 0 0;
            }
        }
    </style>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').classList.remove('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('postImage').value = "";
            document.getElementById('imagePreviewContainer').classList.add('d-none');
        }

        // Like Functionality
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

                if (data.status === 'liked') {
                    btn.classList.add('text-danger', 'fw-bold', 'bg-danger', 'bg-opacity-10');
                    btn.classList.remove('text-muted');
                    icon.classList.replace('bi-heart', 'bi-heart-fill');
                } else {
                    btn.classList.remove('text-danger', 'fw-bold', 'bg-danger', 'bg-opacity-10');
                    btn.classList.add('text-muted');
                    icon.classList.replace('bi-heart-fill', 'bi-heart');
                }
                countSpan.innerText = data.likes_count;
            } catch (error) {
                console.error('Error toggling like:', error);
            }
        }

        // Comments System
        let currentPostsData = @json($posts->items());
        
        function openComments(postId) {
            const sheet = document.getElementById('commentBottomSheet');
            const list = document.getElementById('commentsList');
            const postIdInput = document.getElementById('sheetPostId');
            
            postIdInput.value = postId;
            list.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-success" role="status"></div></div>';
            
            sheet.classList.add('show');
            document.body.style.overflow = 'hidden';

            // Find post data
            const post = currentPostsData.find(p => p.id === postId);
            renderComments(post.comments);
        }

        function closeComments() {
            document.getElementById('commentBottomSheet').classList.remove('show');
            document.body.style.overflow = 'auto';
            cancelReply();
        }

        function renderComments(comments) {
            const list = document.getElementById('commentsList');
            if (comments.length === 0) {
                list.innerHTML = '<div class="text-center py-5 text-muted small">لا توجد تعليقات بعد. كن أول من يعلق!</div>';
                return;
            }

            let html = '';
            comments.forEach(comment => {
                html += `
                    <div class="d-flex gap-2 mb-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-success fw-bold" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                ${comment.user ? comment.user.name.charAt(0) : 'ز'}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="bg-light p-2 rounded-3 shadow-sm px-3">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold small">${comment.user ? comment.user.name : 'زائر ريفي'}</span>
                                </div>
                                <p class="mb-0 small text-dark mt-1">${comment.content}</p>
                            </div>
                            <div class="d-flex gap-2 mt-1 px-2">
                                <button onclick="setReply(${comment.id}, '${comment.user.name}')" class="btn btn-link p-0 text-muted very-small text-decoration-none">رد</button>
                                <span class="very-small text-muted opacity-50">منذ فترة</span>
                            </div>
                            
                            <!-- Replies -->
                            <div class="replies-container ms-4 mt-2 border-end pe-3" style="border-right-width: 2px !important;">
                                ${renderReplies(comment.replies || [])}
                            </div>
                        </div>
                    </div>
                `;
            });
            list.innerHTML = html;
        }

        function renderReplies(replies) {
            let html = '';
            replies.forEach(reply => {
                html += `
                    <div class="d-flex gap-2 mb-2">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center text-success fw-bold" style="width: 25px; height: 25px; font-size: 0.7rem;">
                                ${reply.user ? reply.user.name.charAt(0) : 'ز'}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="bg-white border p-2 rounded-3 px-3">
                                <span class="fw-bold small" style="font-size: 0.75rem;">${reply.user ? reply.user.name : 'زائر ريفي'}</span>
                                <p class="mb-0 very-small text-dark mt-1">${reply.content}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            return html;
        }

        function setReply(commentId, userName) {
            document.getElementById('sheetParentId').value = commentId;
            document.getElementById('replyingToName').innerText = userName;
            document.getElementById('replyingToIndicator').classList.remove('d-none');
            document.getElementById('commentInput').focus();
        }

        function cancelReply() {
            document.getElementById('sheetParentId').value = '';
            document.getElementById('replyingToIndicator').classList.add('d-none');
        }

        async function postComment(e) {
            e.preventDefault();
            const form = e.target;
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
                    
                    // Clear input and reset reply state
                    document.getElementById('commentInput').value = '';
                    cancelReply();
                    
                    // Update the local data structure (optional but good for consistency)
                    const post = currentPostsData.find(p => p.id == postId);
                    if (parentId) {
                        const parentComment = post.comments.find(c => c.id == parentId);
                        if (!parentComment.replies) parentComment.replies = [];
                        parentComment.replies.push(comment);
                    } else {
                        post.comments.push(comment);
                    }
                    
                    // Re-render comments to show the new one
                    renderComments(post.comments);
                    
                    // Increment the comment counter on the post card
                    const counter = document.getElementById(`commentsCount${postId}`);
                    if (counter) {
                        counter.innerText = parseInt(counter.innerText) + 1;
                    }
                }
            } catch (error) {
                console.error('Error posting comment:', error);
            }
        }

        // Sharing Functionality
        function sharePost(postId, url) {
            if (navigator.share) {
                navigator.share({
                    title: 'منشور ريفي',
                    text: 'شاهد هذا المنشور على مجتمع ريفي الزراعي',
                    url: url
                }).catch(console.error);
            } else {
                // Fallback copy to clipboard
                const dummy = document.createElement('input');
                document.body.appendChild(dummy);
                dummy.value = url;
                dummy.select();
                document.execCommand('copy');
                document.body.removeChild(dummy);
                alert('تم نسخ الرابط!')
            }
        }
    </script>
</x-app-layout>
