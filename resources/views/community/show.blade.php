<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-3" style="direction: rtl;">
            <a href="{{ route('community.index') }}" class="btn d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 0; background: var(--bg-primary); color: var(--text-primary); border: 1px solid var(--border-color);">
                <i class="bi bi-arrow-right fs-5"></i>
            </a>
            <div>
                <h2 class="h4 mb-0 font-weight-bold" style="color: var(--heading-color) !important;">عرض المنشور</h2>
                <p class="text-muted small mb-0">بالتفاعل نشارك المعرفة</p>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4 p-0" style="border-radius: 0; direction: rtl; background: var(--bg-secondary) !important; border: 1px solid var(--border-color) !important;">
                    <div class="card-header border-0 pt-4 px-4 pb-0" style="background: var(--bg-secondary) !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center text-success fw-bold border" style="width: 45px; height: 45px; border-radius: 0; background-color: var(--bg-primary); border-color: var(--border-color) !important;">
                                    {{ $post->user ? mb_substr($post->user->name, 0, 1) : 'ز' }}
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="mb-0 fw-bold" style="color: var(--heading-color) !important;">{{ $post->user ? $post->user->name : 'زائر ريفي' }}</h6>
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
                                        <span class="badge {{ $badgeClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $badgeClass) }} very-small" style="border-radius: 0;">{{ $badgeText }}</span>
                                    </div>
                                    <div class="very-small text-muted">{{ $post->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4 pt-3">
                        <p class="mb-3" style="line-height: 1.6; font-size: 1.1rem; color: var(--text-primary) !important;">{{ $post->content }}</p>
                        
                        @if($post->image_path)
                            <div class="overflow-hidden mb-3 border shadow-sm" style="border-radius: 0; border-color: var(--border-color) !important;">
                                <img src="{{ asset('storage/' . $post->image_path) }}" class="w-100" style="max-height: 500px; object-fit: contain; background: var(--bg-primary);" alt="Post content">
                            </div>
                        @endif

                        <div class="d-flex justify-content-between border-bottom pb-2 mb-2 px-1">
                            <div class="very-small text-muted px-2">
                                <i class="bi bi-heart-fill text-danger me-1"></i>
                                <span id="likesCount{{ $post->id }}">{{ $post->likes->count() }}</span> إعجاب
                            </div>
                            <div class="very-small text-muted px-2">
                                <span id="commentsCount{{ $post->id }}">{{ $post->comments->count() }}</span> تعليق
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" 
                                onclick="toggleLike({{ $post->id }})"
                                id="likeBtn{{ $post->id }}"
                                class="btn flex-grow-1 py-1 border-0 d-flex align-items-center justify-content-center gap-2 {{ $post->isLikedBy(auth()->user()) ? 'text-danger fw-bold bg-danger bg-opacity-10' : 'hover-bg-light' }}" style="border-radius: 0; transition: all 0.2s; color: var(--text-secondary);">
                                <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                إعجاب
                            </button>
                            
                            <button type="button" class="btn flex-grow-1 py-1 border-0 d-flex align-items-center justify-content-center gap-2 hover-bg-light" style="border-radius: 0; color: var(--text-secondary);" onclick="openComments({{ $post->id }})">
                                <i class="bi bi-chat-left-text"></i>
                                تعليق
                            </button>
                            <button type="button" class="btn flex-grow-1 py-1 border-0 d-flex align-items-center justify-content-center gap-2 hover-bg-light" style="border-radius: 0; color: var(--text-secondary);" onclick="shareLocalPost()">
                                <i class="bi bi-share"></i>
                                مشاركة
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Sheet Comments (Reused) -->
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
            <div class="sheet-body p-3" id="commentsList" style="direction: rtl; background-color: var(--bg-secondary);">
                <!-- Comments will be loaded here -->
            </div>
            <div class="sheet-footer border-top p-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important;">
                <form onsubmit="postComment(event)" id="commentForm" style="direction: rtl;">
                    @csrf
                    <input type="hidden" name="post_id" id="sheetPostId">
                    <input type="hidden" name="parent_id" id="sheetParentId">
                    <div id="replyingToIndicator" class="very-small text-success mb-2 d-none">
                        جاري الرد على <span id="replyingToName"></span>
                        <button type="button" class="btn btn-link very-small text-danger p-0 ms-2" onclick="cancelReply()">إلغاء</button>
                    </div>
                    <div class="d-flex gap-2">
                        <textarea name="content" id="commentInput" class="form-control px-3 py-2 border-0" rows="1" placeholder="أضف تعليقاً..." required style="resize: none; border-radius: 0; background-color: var(--bg-primary); color: var(--text-primary);"></textarea>
                        <button type="submit" class="btn btn-success d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 0;">
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
        
        /* Bottom Sheet Styles */
        .comment-bottom-sheet { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1050; visibility: hidden; transition: visibility 0.3s; }
        .comment-bottom-sheet.show { visibility: visible; }
        .sheet-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); opacity: 0; transition: opacity 0.3s; }
        .comment-bottom-sheet.show .sheet-overlay { opacity: 1; }
        .sheet-content { position: absolute; bottom: -100%; left: 0; width: 100%; height: 70%; background: var(--bg-secondary); border-radius: 0; transition: bottom 0.3s ease-out; display: flex; flex-direction: column; }
        .comment-bottom-sheet.show .sheet-content { bottom: 0; }
        .drag-handle { width: 40px; height: 5px; background: #e0e0e0; border-radius: 0; margin: 10px auto; }
        .sheet-body { flex-grow: 1; overflow-y: auto; }
        @media (min-width: 992px) { .sheet-content { width: 50%; left: 25%; height: 80%; } }
    </style>

    <script>
        // Use post data from PHP
        let currentPost = @json($post);

        async function toggleLike(postId) {
            try {
                const response = await fetch(`/community/post/${postId}/like`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if (response.status === 401) { window.location.href = '{{ route("login") }}'; return; }
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
                    btn.style.color = 'var(--text-secondary)';
                    icon.classList.replace('bi-heart-fill', 'bi-heart');
                }
                countSpan.innerText = data.likes_count;
            } catch (error) { console.error(error); }
        }

        function openComments(postId) {
            const sheet = document.getElementById('commentBottomSheet');
            const list = document.getElementById('commentsList');
            document.getElementById('sheetPostId').value = postId;
            list.innerHTML = '';
            sheet.classList.add('show');
            document.body.style.overflow = 'hidden';
            renderComments(currentPost.comments);
        }

        function closeComments() {
            document.getElementById('commentBottomSheet').classList.remove('show');
            document.body.style.overflow = 'auto';
            cancelReply();
        }

        function renderComments(comments) {
            const list = document.getElementById('commentsList');
            if (!comments || comments.length === 0) {
                list.innerHTML = '<div class="text-center py-5 text-muted small">لا توجد تعليقات بعد.</div>';
                return;
            }
            let html = '';
            comments.forEach(comment => {
                html += `
                    <div class="d-flex gap-2 mb-3">
                        <div class="flex-shrink-0">
                            <div class="border d-flex align-items-center justify-content-center text-success fw-bold" style="width: 35px; height: 35px; border-radius: 0; background-color: var(--bg-primary); border-color: var(--border-color) !important;">
                                ${comment.user ? comment.user.name.charAt(0) : 'ز'}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="p-2 shadow-sm px-3" style="border-radius: 0; background-color: var(--bg-primary);">
                                <div class="fw-bold small" style="color: var(--heading-color);">${comment.user ? comment.user.name : 'زائر ريفي'}</div>
                                <p class="mb-0 small mt-1" style="color: var(--text-primary);">${comment.content}</p>
                            </div>
                            <div class="d-flex gap-2 mt-1 px-2">
                                <button onclick="setReply(${comment.id}, '${comment.user.name}')" class="btn btn-link p-0 very-small text-decoration-none" style="color: var(--text-secondary);">رد</button>
                            </div>
                            <div class="ms-4 mt-2 border-end pe-3" style="border-right: 2px solid var(--border-color);">
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
                            <div class="border d-flex align-items-center justify-content-center text-success fw-bold" style="width: 25px; height: 25px; border-radius: 0; background-color: var(--bg-primary); border-color: var(--border-color) !important;">
                                ${reply.user ? reply.user.name.charAt(0) : 'ز'}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="border p-2 px-3" style="border-radius: 0; background-color: var(--bg-primary); border-color: var(--border-color) !important;">
                                <span class="fw-bold small" style="color: var(--heading-color);">${reply.user ? reply.user.name : 'زائر ريفي'}</span>
                                <p class="mb-0 very-small mt-1" style="color: var(--text-primary);">${reply.content}</p>
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
            const postId = document.getElementById('sheetPostId').value;
            const content = document.getElementById('commentInput').value;
            const parentId = document.getElementById('sheetParentId').value;
            try {
                const response = await fetch(`/community/post/${postId}/comment`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ content, parent_id: parentId })
                });
                if (response.ok) {
                    const data = await response.json();
                    const comment = data.comment;
                    document.getElementById('commentInput').value = '';
                    cancelReply();
                    if (parentId) {
                        const parent = currentPost.comments.find(c => c.id == parentId);
                        if (!parent.replies) parent.replies = [];
                        parent.replies.push(comment);
                    } else {
                        currentPost.comments.push(comment);
                    }
                    renderComments(currentPost.comments);
                    document.getElementById(`commentsCount${postId}`).innerText = (parseInt(document.getElementById(`commentsCount${postId}`).innerText) || 0) + 1;
                }
            } catch (error) { console.error(error); }
        }

        function shareLocalPost() {
            const url = window.location.href;
            if (navigator.share) {
                navigator.share({ title: 'منشور ريفي', text: 'شاهد هذا المنشور', url: url }).catch(console.error);
            } else {
                navigator.clipboard.writeText(url).then(() => alert('تم نسخ الرابط!'));
            }
        }
    </script>
</x-app-layout>
