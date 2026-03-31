<x-app-layout>
    <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-[#0A0A0A]">

        <!-- Header -->
        <div class="h-14 flex items-center justify-between px-4 border-b border-gray-200 dark:border-slate-800 max-w-2xl mx-auto w-full">
            <h1 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                {{ __('REEFY')}} <span class="text-emerald-500">{{ __('AI')}}</span>
            </h1>

            <form action="{{ route('farmer.ai.clear') }}" method="POST">
                @csrf
                <button class="text-xs text-red-500 hover:underline">
                    {{ __('Clear') }}
                </button>
            </form>
        </div>

        <!-- Empty State -->
        @if(count($messages) == 0)
        <div class="flex-1 flex items-center justify-center">
            <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 p-10 text-center rounded-xl shadow-sm max-w-md w-full mx-4">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 mb-6 rounded-full">
                    <i class="bi bi-patch-question text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">
                    {{ __('Need advice') }}
                </h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    {{ __('Your intelligent farming assistant for better decisions') }}
                </p>
            </div>
        </div>
        @endif

        <!-- Chat -->
        <div id="chat-container" class="flex-1 overflow-y-auto px-3 py-4 space-y-3 max-w-2xl mx-auto w-full">

            @foreach($messages as $msg)
                @php $isUser = $msg['role'] === 'user'; @endphp

                <div class="flex {{ $isUser ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-sm px-3 py-2 text-sm rounded-2xl shadow-sm
                        {{ $isUser
                            ? 'bg-emerald-600 text-white rounded-br-none'
                            : 'bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-slate-800 rounded-bl-none' }}">

                        @if($isUser && isset($msg['image_url']))
                            <img src="{{ $msg['image_url'] }}" class="mb-2 rounded-lg max-h-40">
                        @endif

                        <div class="leading-relaxed break-words">
                            {!! $msg['content'] !!}
                        </div>

                        <div class="text-[10px] mt-1 opacity-50 text-right">
                            {{ $msg['time'] }}
                        </div>
                    </div>
                </div>
            @endforeach

            <div id="bottom"></div>
        </div>

<div class="fixed bottom-0 left-0 right-0 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0A0A0A] py-3">
    <div class="max-w-2xl mx-auto px-3">

        <form id="chat-form" method="POST" enctype="multipart/form-data"
              class="flex items-center gap-2 bg-gray-100 dark:bg-slate-800 rounded-full px-3 py-2">
            @csrf

            <!-- زر رفع صورة (أيقونة) -->
            <label class="cursor-pointer text-gray-500 hover:text-emerald-600 text-lg">
                <i class="bi bi-image"></i>
                <input type="file" name="image" id="file-input" class="hidden">
            </label>

            <!-- الانبوت -->
            <input type="text" name="message" id="message-input"
                   placeholder="{{ __('Describe your problem...') }}"
                   class="flex-1 bg-transparent border-0 focus:outline-none text-sm text-black dark:text-white placeholder-gray-400">

            <!-- زر الإرسال (أيقونة) -->
            <button id="submit-btn"
                    class="text-emerald-600 hover:text-emerald-500 text-lg">
                <i class="bi bi-send-fill"></i>
            </button>
        </form>
     </div>
   </div>
</div>

    <script>
        const form = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const chatContainer = document.getElementById('chat-container');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const message = messageInput.value.trim();
            const fileInput = document.getElementById('file-input');

            if (!message && !fileInput.files.length) return;

            const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

            // User message
            const userHtml = `
                <div class="flex justify-end">
                    <div class="max-w-sm px-3 py-2 text-sm rounded-2xl bg-emerald-600 text-white rounded-br-none">
                        ${message}
                        <div class="text-[10px] mt-1 opacity-50 text-right">${time}</div>
                    </div>
                </div>
            `;

            chatContainer.insertAdjacentHTML('beforeend', userHtml);
            messageInput.value = '';

            try {
                const res = await fetch("{{ route('farmer.ai.chat_api') }}", {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await res.json();

                if (data.success) {
                    const ai = data.message;

                    const aiHtml = `
                        <div class="flex justify-start">
                            <div class="max-w-sm px-3 py-2 text-sm rounded-2xl bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-slate-800 rounded-bl-none">
                                ${ai.content}
                                <div class="text-[10px] mt-1 opacity-50 text-right">${ai.time}</div>
                            </div>
                        </div>
                    `;

                    chatContainer.insertAdjacentHTML('beforeend', aiHtml);
                } else {
                    alert(data.error);
                }

            } catch (err) {
                alert('Error');
            }

            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    </script>
</x-app-layout>

