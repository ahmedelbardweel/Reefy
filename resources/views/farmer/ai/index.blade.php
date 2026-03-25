<x-app-layout>
    <!-- Full-screen Loading Overlay -->
    <div id="page-loader" class="fixed inset-0 z-[100] bg-white dark:bg-[#0A0A0A] flex flex-col items-center justify-center transition-all duration-700">
        <div class="relative flex items-center justify-center">
            <div class="absolute inset-0 bg-emerald-500/20 blur-[60px] rounded-full animate-pulse"></div>
            <div class="w-24 h-24 bg-gradient-to-tr from-emerald-600 to-green-500 text-white rounded-[32px] flex items-center justify-center shadow-2xl relative z-10 animate-bounce">
                <i class="bi bi-robot text-4xl"></i>
            </div>
            <!-- Progress Circle -->
            <div class="absolute inset-[-15px] border-4 border-emerald-500/10 border-t-emerald-500 rounded-full animate-spin"></div>
        </div>
        <div class="mt-10 text-center relative z-10">
            <h2 class="text-xl font-black text-black dark:text-white tracking-widest uppercase mb-2">Reefy AI</h2>
            <div class="flex items-center gap-1 justify-center">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">{{ __('Loading Secure Session...') }}</span>
            </div>
        </div>
    </div>

    <div class="fixed top-[64px] bottom-0 left-0 right-0 flex flex-col bg-white dark:bg-[#0A0A0A] border-0 transition-colors duration-300">
        <!-- Header -->
        <div class="h-20 flex-shrink-0 bg-white/90 dark:bg-[#0A0A0A]/90 backdrop-blur-xl border-b border-slate-200/60 dark:border-slate-800/60 flex justify-between items-center px-6 md:px-10 z-20">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-12 h-12 bg-gradient-to-tr from-emerald-600 to-green-500 text-white rounded-2xl flex items-center justify-center shadow-xl shadow-emerald-500/20 dark:shadow-emerald-500/10 transform hover:scale-110 hover:rotate-3 transition-all duration-300">
                        <i class="bi bi-robot text-2xl"></i>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-4 border-white dark:border-[#0A0A0A] rounded-full animate-pulse"></div>
                </div>
                <div>
                    <h1 class="text-lg md:text-xl font-black tracking-tight text-black dark:text-white leading-none">
                        Reefy <span class="bg-gradient-to-r from-emerald-600 to-green-500 bg-clip-text text-transparent italic">AI</span>
                    </h1>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="text-[10px] font-bold text-black dark:text-white uppercase tracking-widest flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span class="bg-emerald-50 dark:bg-emerald-950/30 px-2 py-0.5 rounded-full opacity-80">Expert System Ready</span>
                        </span>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('farmer.ai.clear') }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to clear the conversation?') }}')">
                @csrf
                <button type="submit" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800/50 text-slate-400 dark:text-slate-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-all flex items-center justify-center group border border-slate-200 dark:border-slate-700/50">
                    <i class="bi bi-trash3 text-lg transition-transform group-hover:scale-110"></i>
                </button>
            </form>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 overflow-y-auto px-4 py-8 md:px-10 space-y-10 scroll-smooth custom-scrollbar bg-[#F8FAFC] dark:bg-[#0A0A0A]" id="chat-container">
            @if(empty($messages))
                <div class="flex flex-col items-center justify-center h-full text-center space-y-8 animate-in fade-in zoom-in duration-1000">
                    <div class="relative flex items-center justify-center">
                        <!-- Animated Background -->
                        <div class="absolute inset-0 bg-emerald-500/5 dark:bg-emerald-500/10 blur-[100px] rounded-full animate-pulse"></div>
                        
                        <!-- Floating Elements -->
                        <div class="absolute w-16 h-16 bg-emerald-500/10 dark:bg-emerald-500/20 rounded-full -top-10 -right-10 animate-float"></div>
                        <div class="absolute w-20 h-20 bg-green-500/10 dark:bg-green-500/20 rounded-full -bottom-10 -left-10 animate-float-delayed"></div>
                        
                        <!-- Main Icon -->
                        <div class="w-32 h-32 bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-950/30 dark:to-[#0A0A0A] rounded-[40px] shadow-2xl dark:shadow-slate-900/50 flex items-center justify-center border border-slate-200 dark:border-slate-800/60 relative z-10 overflow-hidden group">
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 via-emerald-500/5 to-transparent dark:from-emerald-400/0 dark:via-emerald-400/5 group-hover:translate-x-full transition-transform duration-1000"></div>
                            <i class="bi bi-stars text-6xl text-emerald-600 dark:text-emerald-400 animate-pulse"></i>
                        </div>
                    </div>
                    
                    <div class="max-w-md relative z-10 px-6 space-y-4">
                        <h2 class="text-3xl md:text-4xl font-black text-black dark:text-white mb-4 tracking-tight leading-tight">
                            {{ __('Your Farm\'s Intelligent') }}
                            <span class="block text-[#059669]">{{ __('Companion') }}</span>
                        </h2>
                        <p class="text-black dark:text-white font-bold leading-relaxed opacity-70">
                            {{ __('Ask about diseases, fertilization, or crop management.') }}
                        </p>
                        
                        <!-- Feature Pills -->
                        <div class="flex flex-wrap items-center justify-center gap-2 pt-4">
                            <span class="px-3 py-1 bg-white dark:bg-emerald-900/30 text-[#059669] dark:text-emerald-300 text-xs font-black rounded-full border border-slate-200 dark:border-emerald-800/50 shadow-sm">🌱 Disease Detection</span>
                            <span class="px-3 py-1 bg-white dark:bg-emerald-900/30 text-[#059669] dark:text-emerald-300 text-xs font-black rounded-full border border-slate-200 dark:border-emerald-800/50 shadow-sm">💧 Irrigation Advice</span>
                            <span class="px-3 py-1 bg-white dark:bg-emerald-900/30 text-[#059669] dark:text-emerald-300 text-xs font-black rounded-full border border-slate-200 dark:border-emerald-800/50 shadow-sm">🌿 Fertilizer Tips</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="max-w-5xl mx-auto space-y-12 pb-20 px-2 sm:px-4">
                @if(session('success'))
                    <div class="bg-emerald-500 text-white p-4 rounded-2xl font-black text-center shadow-lg shadow-emerald-500/20 animate-in">
                        <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-500 text-white p-4 rounded-2xl font-black text-center shadow-lg shadow-red-500/20 animate-in">
                        <i class="bi bi-exclamation-triangle-fill mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                @foreach($messages as $index => $msg)
                    @php 
                        $role = strtolower($msg['role'] ?? '');
                        $isUser = ($role === 'user'); 
                        $isAi = ($role === 'ai');
                    @endphp
                    
                    <div class="flex w-full {{ $isUser ? 'flex-row-reverse' : 'flex-row' }} items-start gap-3 sm:gap-4 message-enter" style="animation-delay: {{ $index * 0.1 }}s">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 mt-1">
                            @if($isAi)
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-green-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20 transform hover:scale-110 transition-transform">
                                    <i class="bi bi-robot text-lg sm:text-xl"></i>
                                </div>
                            @elseif($isUser)
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-md transform hover:scale-110 transition-transform">
                                    <i class="bi bi-person-fill text-xl sm:text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Bubble -->
                        <div class="flex flex-col {{ $isUser ? 'items-end' : 'items-start' }} max-w-[85%] sm:max-w-[75%] space-y-1.5">
                            <div class="relative px-5 py-4 sm:px-7 sm:py-5 rounded-[24px] shadow-sm
                                {{ $isUser 
                                    ? 'text-white rounded-tr-none shadow-emerald-500/10' 
                                    : 'bg-white dark:bg-slate-900 text-black dark:text-white rounded-tl-none border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none' }}"
                                {!! $isUser ? 'style="background-color: #059669 !important; color: white !important;"' : '' !!}>
                                
                                {{-- User Image --}}
                                @if($isUser && isset($msg['image_url']))
                                    <div class="mb-4 rounded-xl overflow-hidden border border-white/20 shadow-lg">
                                        <img src="{{ $msg['image_url'] }}" class="w-full max-h-[300px] object-cover hover:scale-105 transition-transform duration-500">
                                    </div>
                                @endif

                                {{-- Text Content --}}
                                <div class="text-[15px] sm:text-[16px] leading-[1.7] font-bold prose-custom" 
                                     {!! $isUser ? 'style="color: white !important;"' : '' !!}>
                                    @if(empty(trim(strip_tags($msg['content']))) && $isUser && isset($msg['image_url']))
                                        <span class="opacity-60 italic text-xs">{{ __('Analysis request for image...') }}</span>
                                    @elseif(!empty($msg['content']))
                                        {!! $msg['content'] !!}
                                    @else
                                        <span class="opacity-50 italic text-xs">{{ __('Empty message') }}</span>
                                    @endif
                                </div>
                                
                                {{-- Footer --}}
                                <div class="flex items-center gap-3 mt-4 pt-3 border-t {{ $isUser ? 'border-white/10' : 'border-slate-100 dark:border-slate-800' }}">
                                    <span class="text-[10px] font-black uppercase tracking-widest {{ $isUser ? 'text-white/60' : 'text-slate-400 dark:text-slate-500' }}">
                                        {{ $msg['time'] }}
                                    </span>
                                    
                                    @if($isAi)
                                        <button onclick="copyMessage(this)" class="text-slate-300 hover:text-emerald-500 transition-colors ml-auto">
                                            <i class="bi bi-copy text-[12px]"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            @if($isAi)
                                <div class="px-2 text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400 opacity-50">
                                    Expert System Response
                                </div>
                            @elseif($isUser)
                                <div class="px-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 opacity-50">
                                    Your Inquiry
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Typing Indicator -->
            <div id="typing-indicator" class="hidden max-w-5xl mx-auto flex justify-start items-start gap-4 message-enter">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-600 to-green-500 text-white flex items-center justify-center shadow-xl">
                    <i class="bi bi-robot text-lg"></i>
                </div>
                <div class="bg-white dark:bg-slate-900/95 px-6 py-5 rounded-[28px] rounded-tl-none border border-slate-200 dark:border-slate-800 shadow-xl">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
            </div>

            <!-- Bottom Anchor -->
            <div id="bottom" class="h-10 w-full shrink-0"></div>
        </div>

        <!-- Input Section -->
        <div class="px-4 py-6 md:px-10 bg-gradient-to-t from-white via-white to-white/95 dark:from-[#0A0A0A] dark:via-[#0A0A0A] dark:to-[#0A0A0A]/95 border-t border-slate-200/60 dark:border-slate-800/60">
            <div class="max-w-4xl mx-auto relative">
                <form action="{{ route('farmer.ai.chat') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3" id="chat-form">
                    @csrf
                    
                    @if(session('success'))
                        <div class="absolute -top-12 left-0 right-0 flex justify-center animate-in slide-in-from-bottom duration-300 z-50">
                            <div class="bg-emerald-500 text-white text-[11px] font-black uppercase tracking-wider py-2 px-6 rounded-full inline-flex items-center gap-2 shadow-xl shadow-emerald-500/30">
                                <i class="bi bi-check-circle-fill"></i>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="absolute -top-12 left-0 right-0 flex justify-center animate-in slide-in-from-bottom duration-300 z-50">
                            <div class="bg-red-500 dark:bg-red-600 text-white text-[11px] font-black uppercase tracking-wider py-2 px-6 rounded-full inline-flex items-center gap-2 shadow-xl shadow-red-500/30">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                {{ $errors->first() }}
                            </div>
                        </div>
                    @endif

                    <!-- Image Preview -->
                    <div id="image-preview-container" class="hidden mb-2 animate-slide-up">
                        <div class="relative inline-block group">
                            <img id="image-preview" src="#" alt="Preview" class="h-20 w-20 object-cover rounded-2xl border-4 border-emerald-500/30 dark:border-emerald-500/20 shadow-2xl">
                            <button type="button" onclick="resetImage()" class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all transform hover:scale-110 border-2 border-white dark:border-[#0A0A0A]">
                                <i class="bi bi-x text-lg"></i>
                            </button>
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 border-3 border-white dark:border-[#0A0A0A] rounded-full flex items-center justify-center">
                                <i class="bi bi-check text-white text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="relative flex items-end gap-3 bg-white dark:bg-slate-900/95 border-2 border-slate-200/80 dark:border-slate-800/80 rounded-[32px] p-2.5 transition-all focus-within:border-emerald-500/50 focus-within:shadow-2xl focus-within:shadow-emerald-500/10 dark:focus-within:shadow-emerald-500/5 hover:border-emerald-500/30 shadow-2xl shadow-slate-200/20 dark:shadow-slate-900/50 backdrop-blur-sm">
                        <!-- Upload Trigger -->
                        <div class="relative shrink-0">
                            <input type="file" name="image" id="file-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" onchange="handleImageSelect(this)">
                            <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-2xl flex items-center justify-center hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all duration-300 border border-slate-200 dark:border-slate-700/50 group">
                                <i class="bi bi-camera-fill text-xl transition-transform group-hover:scale-110"></i>
                            </div>
                        </div>

                        <!-- Textarea -->
                        <textarea name="message" id="message-input" rows="1" placeholder="{{ __('Describe your problem...') }}" 
                                  class="flex-1 bg-transparent border-0 focus:ring-0 text-[16px] py-3.5 text-black dark:text-white placeholder-slate-400 dark:placeholder-slate-600 resize-none min-h-[50px] max-h-48 leading-relaxed font-bold outline-none"
                                  onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); document.getElementById('submit-btn').click(); }"
                                  oninput="autoResize(this)"></textarea>

                        <!-- Submit Button -->
                        <button type="submit" class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-black rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/30 dark:shadow-blue-500/20 active:scale-90 transition-all transform hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-blue-500/40 disabled:opacity-50 disabled:cursor-not-allowed" id="submit-btn">
                            <i class="bi bi-send-fill text-xl"></i>
                        </button>
                    </div>

                    <div class="flex items-center justify-center gap-6">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-black/50 dark:text-white/40">Reefy Intelligence Engine v5.0</span>
                        <span class="w-1 h-1 bg-slate-300 dark:bg-slate-700 rounded-full"></span>
                        <span class="text-[10px] font-bold text-black/50 dark:text-white/40 flex items-center gap-1">
                            <i class="bi bi-shield-check text-xs"></i> Secure AI
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap');

        :root {
            --emerald-primary: #10B981;
            --emerald-dark: #059669;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            overflow: hidden;
            background: white;
        }

        .dark body {
            background: #0A0A0A;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.2);
            border-radius: 20px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.4);
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(51, 65, 85, 0.5);
        }

        /* Message Typography */
        .prose-custom {
            word-break: break-word;
        }

        .prose-custom strong {
            font-weight: 900;
            color: #059669;
            background: rgba(5, 150, 105, 0.1);
            padding: 1px 4px;
            border-radius: 4px;
        }

        .dark .prose-custom strong {
            color: #34D399;
            background: rgba(52, 211, 153, 0.15);
        }

        .prose-custom ul, .prose-custom ol {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .prose-custom li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.5rem;
            list-style: none;
        }

        .prose-custom li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--emerald-primary);
            font-weight: 900;
            font-size: 1.2rem;
        }

        .prose-custom code {
            background: rgba(16, 185, 129, 0.1);
            padding: 2px 6px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 0.9em;
        }

        /* Animations */
        @keyframes slideInUp {
            from { 
                opacity: 0; 
                transform: translateY(30px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(20px); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float-delayed 7s ease-in-out infinite;
        }

        .animate-in {
            animation: slideInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        .message-enter {
            animation: slideInUp 0.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            opacity: 0;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-up {
            animation: slideUp 0.3s ease-out forwards;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .fixed.top-\[64px\] {
                top: 56px !important;
            }
            
            .px-6 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            .max-w-\[88\%\] {
                max-width: 92% !important;
            }
        }

        /* Loading States */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>

    <script>
        // Auto-resize textarea
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        // Handle image selection
        function handleImageSelect(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    const container = document.getElementById('image-preview-container');
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        // Reset image
        function resetImage() {
            const input = document.getElementById('file-input');
            const container = document.getElementById('image-preview-container');
            input.value = '';
            container.classList.add('hidden');
        }

        // Copy message
        function copyMessage(button) {
            const messageDiv = button.closest('.group').querySelector('.prose-custom');
            const text = messageDiv.innerText;
            
            navigator.clipboard.writeText(text).then(() => {
                const originalIcon = button.innerHTML;
                button.innerHTML = '<i class="bi bi-check text-sm"></i>';
                setTimeout(() => {
                    button.innerHTML = originalIcon;
                }, 2000);
            });
        }

        // Scroll to bottom
        function scrollToBottom() {
            const container = document.getElementById('chat-container');
            container.scrollTo({
                top: container.scrollHeight,
                behavior: 'smooth'
            });
        }

        // Auto-scroll on new messages
        const observer = new MutationObserver(() => {
            scrollToBottom();
        });

        observer.observe(document.getElementById('chat-container'), {
            childList: true,
            subtree: true
        });

        // Typing indicator
        let typingTimeout;
        const messageInput = document.getElementById('message-input');
        
        messageInput.addEventListener('input', function() {
            clearTimeout(typingTimeout);
            // Here you could show typing indicator to other users
        });

        // Submit form with AJAX
        document.getElementById('chat-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = document.getElementById('submit-btn');
            const messageInput = document.getElementById('message-input');
            const fileInput = document.getElementById('file-input');
            const chatContainer = document.querySelector('#chat-container > div');
            const emptyState = document.querySelector('#chat-container > div.flex.flex-col.items-center');
            const typingIndicator = document.getElementById('typing-indicator');
            
            const message = messageInput.value.trim();
            const hasImage = fileInput.files.length > 0;

            if (!message && !hasImage) return;

            // Prepare Data
            const formData = new FormData(form);
            
            // UI State: Disable input
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin text-xl"></i>';
            
            // Append User Message to UI
            if (emptyState) emptyState.remove();
            
            const time = new Date().toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit', hour12: false });
            let userHtml = `
                <div class="flex w-full flex-row-reverse items-start gap-3 sm:gap-4 message-enter">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-md transform hover:scale-110 transition-transform">
                            <i class="bi bi-person-fill text-xl sm:text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex flex-col items-end max-w-[85%] sm:max-w-[75%] space-y-1.5">
                        <div class="relative px-5 py-4 sm:px-7 sm:py-5 rounded-[24px] shadow-sm text-white rounded-tr-none shadow-emerald-500/10" style="background-color: #059669 !important; color: white !important;">
            `;
            
            if (hasImage) {
                const previewImg = document.getElementById('image-preview').src;
                userHtml += `
                    <div class="mb-4 rounded-xl overflow-hidden border border-white/20 shadow-lg">
                        <img src="${previewImg}" class="w-full max-h-[300px] object-cover">
                    </div>
                `;
            }
            
            userHtml += `
                            <div class="text-[15px] sm:text-[16px] leading-[1.7] font-bold prose-custom" style="color: white !important;">
                                ${message.replace(/\n/g, '<br>')}
                            </div>
                            <div class="flex items-center gap-3 mt-4 pt-3 border-t border-white/10">
                                <span class="text-[10px] font-black uppercase tracking-widest text-white/60">${time}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            chatContainer.insertAdjacentHTML('beforeend', userHtml);
            scrollToBottom();
            
            // Reset Input
            messageInput.value = '';
            messageInput.style.height = 'auto';
            resetImage();

            // Show Thinking
            typingIndicator.classList.remove('hidden');
            scrollToBottom();

            try {
                const response = await fetch("{{ route('farmer.ai.chat_api') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();
                typingIndicator.classList.add('hidden');

                if (result.success) {
                    const aiMsg = result.message;
                    let aiHtml = `
                        <div class="flex w-full flex-row items-start gap-3 sm:gap-4 message-enter">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-green-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20 transform hover:scale-110 transition-transform">
                                    <i class="bi bi-robot text-lg sm:text-xl"></i>
                                </div>
                            </div>
                            <div class="flex flex-col items-start max-w-[85%] sm:max-w-[75%] space-y-1.5">
                                <div class="relative px-5 py-4 sm:px-7 sm:py-5 rounded-[24px] shadow-sm bg-white dark:bg-slate-900 text-black dark:text-white rounded-tl-none border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
                                    <div class="text-[15px] sm:text-[16px] leading-[1.7] font-bold prose-custom">
                                        ${aiMsg.content}
                                    </div>
                                    <div class="flex items-center gap-3 mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">${aiMsg.time}</span>
                                        <button onclick="copyMessage(this)" class="text-slate-300 hover:text-emerald-500 transition-colors ml-auto">
                                            <i class="bi bi-copy text-[12px]"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="px-2 text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400 opacity-50">
                                    Expert System Response
                                </div>
                            </div>
                        </div>
                    `;
                    chatContainer.insertAdjacentHTML('beforeend', aiHtml);
                } else {
                    alert(result.error || 'Something went wrong');
                }
            } catch (error) {
                console.error('AI Error:', error);
                typingIndicator.classList.add('hidden');
                alert('Connection error. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-send-fill text-xl"></i>';
                scrollToBottom();
            }
        });

        // Dark mode detection
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Scroll to bottom on load + Hide Loader
        window.addEventListener('load', () => {
            scrollToBottom();
            
            setTimeout(() => {
                const loader = document.getElementById('page-loader');
                loader.classList.add('opacity-0', 'invisible');
                // Remove from DOM after transition
                setTimeout(() => loader.remove(), 700);
            }, 800);
        });

        console.log('Reefy AI Interface v5.1 Loaded');
    </script>
</x-app-layout>