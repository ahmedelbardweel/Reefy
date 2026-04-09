<x-app-layout>
    <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-[#0A0A0A]">

        <div class="h-14 flex items-center justify-between px-4 border-b border-gray-200 dark:border-slate-800 max-w-2xl mx-auto w-full">
            <h1 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                {{ __('REEFY')}} <span class="text-emerald-500">{{ __('AI')}}</span>
            </h1>
            <form action="{{ route('farmer.ai.clear') }}" method="POST">
                @csrf
                <button class="text-xs text-red-500 hover:underline">{{ __('Clear') }}</button>
            </form>
        </div>

        <div id="chat-container" class="flex-1 overflow-y-auto px-3 py-4 space-y-4 max-w-2xl mx-auto w-full pb-24">
            @if(count($messages) == 0)
                <div class="text-center py-10 opacity-50">
                    <i class="bi bi-robot text-4xl"></i>
                    <p class="mt-2 text-sm"> {{ __('How can I help you with your farm today?') }}</p>
                </div>
            @endif

            @foreach($messages as $msg)
                @php $isUser = $msg['role'] === 'user'; @endphp
                <div class="flex {{ $isUser ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[85%] px-4 py-2 text-sm rounded-2xl shadow-sm
                        {{ $isUser ? 'bg-emerald-600 text-white rounded-br-none' : 'bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-slate-800 rounded-bl-none' }}">

                        @if($isUser && isset($msg['image_url']))
                            <img src="{{ $msg['image_url'] }}" class="mb-2 rounded-lg max-h-60 w-full object-cover">
                        @endif

                        <div class="leading-relaxed break-words">
                            {!! $msg['content'] !!}
                        </div>

                        @if(!$isUser && !empty($msg['action_form']))
                            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-slate-700">
                                {!! $msg['action_form'] !!}
                            </div>
                        @endif

                        <div class="text-[10px] mt-1 opacity-50 text-right">{{ $msg['time'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="fixed bottom-0 left-0 right-0 border-t border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-[#0A0A0A]/80 backdrop-blur-md py-3">
            <div class="max-w-2xl mx-auto px-3">
                <form id="chat-form" enctype="multipart/form-data" class="flex items-center gap-2 bg-gray-100 dark:bg-slate-800 rounded-2xl px-3 py-2">
                    @csrf
                    <label class="cursor-pointer text-gray-500 hover:text-emerald-600 p-1">
                        <i class="bi bi-image text-xl"></i>
                        <input type="file" name="image" id="file-input" class="hidden" accept="image/*">
                    </label>

                    <input type="text" name="message" id="message-input" autocomplete="off"
                           placeholder="{{ __('Describe your problem...') }}"
                           class="flex-1 bg-transparent border-0 focus:ring-0 text-sm text-black dark:text-white placeholder-gray-400">

                    <button type="submit" id="submit-btn" class="text-emerald-600 hover:text-emerald-500 p-1">
                        <i class="bi bi-send-fill text-xl"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const fileInput = document.getElementById('file-input');
        const chatContainer = document.getElementById('chat-container');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const message = messageInput.value.trim();
            if (!message && !fileInput.files.length) return;

            const formData = new FormData(form);
            const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

            // 1. عرض رسالة المستخدم فوراً (بما في ذلك معاينة الصورة)
            let imgHtml = '';
            if (fileInput.files[0]) {
                const imgUrl = URL.createObjectURL(fileInput.files[0]);
                imgHtml = `<img src="${imgUrl}" class="mb-2 rounded-lg max-h-60 w-full object-cover">`;
            }

            const userHtml = `
                <div class="flex justify-end animate-pulse">
                    <div class="max-w-[85%] px-4 py-2 text-sm rounded-2xl bg-emerald-600 text-white rounded-br-none">
                        ${imgHtml} <p>${message}</p>
                        <div class="text-[10px] mt-1 opacity-50 text-right">${time}</div>
                    </div>
                </div>`;

            chatContainer.insertAdjacentHTML('beforeend', userHtml);
            form.reset();
            chatContainer.scrollTop = chatContainer.scrollHeight;

            try {
                const res = await fetch("{{ route('farmer.ai.chat_api') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await res.json();

                // إزالة انيميشن الـ pulse بعد وصول الرد
                chatContainer.lastElementChild.classList.remove('animate-pulse');

                if (data.success) {
                    const ai = data.message;
                    const aiHtml = `
                        <div class="flex justify-start">
                            <div class="max-w-[85%] px-4 py-2 text-sm rounded-2xl bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-slate-800 rounded-bl-none shadow-sm">
                                <div>${ai.content}</div>
                                ${ai.action_form ? `<div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">${ai.action_form}</div>` : ''}
                                <div class="text-[10px] mt-1 opacity-50 text-right">${ai.time}</div>
                            </div>
                        </div>`;
                    chatContainer.insertAdjacentHTML('beforeend', aiHtml);
                } else {
                    alert(data.error || 'حدث خطأ ما');
                }
            } catch (err) {
                alert('فشل الاتصال بالخادم');
            }
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    </script>
</x-app-layout>
