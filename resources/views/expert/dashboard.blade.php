<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold mb-1 text-gray-900 dark:text-white tracking-tight">
                    مركز قيادة الخبير
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">مرحباً بك مجدداً يا د. {{ Auth::user()->name }}، إليك ملخص نشاطك اليوم.</p>
            </div>
            <div class="hidden lg:flex px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-none items-center gap-2 bg-white dark:bg-gray-800">
                <i class="bi bi-calendar3 text-green-600"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ now()->translatedFormat('l, d M Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8" x-data="{ activeEditModal: null, showAddModal: false }">
        <!-- KPI Pillars -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 text-start">
            <!-- Pending Consultations -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold text-gray-500">استشارات معلقة</span>
                    <i class="bi bi-patch-question text-yellow-500 opacity-50 text-xl"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-yellow-500">{{ $pendingCount }}</h3>
                <div class="text-[10px] text-yellow-500 font-bold">تحتاج إلى رد عاجل</div>
            </div>

            <!-- Answered -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold text-gray-500">تقديم نصائح</span>
                    <i class="bi bi-chat-heart text-green-600 opacity-50 text-xl"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-green-600">{{ $answeredCount }}</h3>
                <div class="text-[10px] text-green-600 font-bold">إجمالي المساهمات</div>
            </div>

            <!-- Rating -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold text-gray-500">تقييم الأداء</span>
                    <i class="bi bi-star-fill text-blue-600 opacity-50 text-xl"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-blue-600">4.9</h3>
                <div class="text-[10px] text-blue-600 font-bold">بناءً على رأي المزارعين</div>
            </div>

            <!-- Beneficiaries -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold text-gray-500">مزارعين مستفيدين</span>
                    <i class="bi bi-people text-gray-500 opacity-50 text-xl"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-gray-700 dark:text-gray-300">85</h3>
                <div class="text-[10px] text-gray-500 font-bold">خلال الشهر الأخير</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <!-- Consultations List -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-4 flex justify-between items-center border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div>
                        <h5 class="font-bold text-sm text-gray-900 dark:text-white">أحدث طلبات الاستشارة مجهولة الرد</h5>
                        <p class="text-xs text-gray-500">قم بمساعدة المزارعين من خلال خبراتك الميدانية</p>
                    </div>
                    <a href="{{ route('expert.consultations.index') }}" class="text-xs border border-green-600 text-green-600 hover:bg-green-600 hover:text-white px-3 py-1 transition">عرض الكل</a>
                </div>
                <div class="p-4">
                    @forelse($recentConsultations as $consultation)
                        <div class="p-4 mb-3 border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-md transition group">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <i class="bi bi-chat-dots text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm text-gray-900 dark:text-white">{{ $consultation->subject }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $consultation->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-[10px] border border-gray-200 dark:border-gray-600">{{ $consultation->category }}</span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-3 leading-relaxed">{{ Str::limit($consultation->question, 140) }}</p>
                            <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-50 dark:border-gray-700">
                                <div class="text-[10px] font-bold text-gray-500">
                                    <i class="bi bi-person me-1"></i> مزارع ريفي
                                </div>
                                <a href="{{ route('consultations.show', $consultation) }}" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition">
                                    <i class="bi bi-reply-all ml-1"></i> تقديم نصيحة
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="inline-flex p-4 bg-gray-50 dark:bg-gray-700 mb-3 rounded-full">
                                <i class="bi bi-check2-all text-green-600 text-4xl"></i>
                            </div>
                            <h6 class="font-bold text-gray-900 dark:text-white">عمل رائع! لا توجد طلبات معلقة</h6>
                            <p class="text-xs text-gray-500">قمت بالرد على كافة الاستشارات المتاحة حتى الآن.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Expert Tips Management -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm mt-4">
                <div class="p-4 flex justify-between items-center border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h5 class="font-bold text-sm text-gray-900 dark:text-white">إدارة النصائح العامة</h5>
                        <p class="text-xs text-gray-500">انشر نصائح تظهر لجميع المزارعين في لوحة تحكمهم</p>
                    </div>
                    <button @click="showAddModal = true" class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition">
                        <i class="bi bi-plus-lg ml-1"></i> إضافة نصيحة
                    </button>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($myTips as $tip)
                            <div class="p-4 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-md transition relative group">
                                <div class="flex justify-between items-start mb-2">
                                    <h6 class="font-bold text-sm text-gray-900 dark:text-white">{{ $tip->title }}</h6>
                                    
                                    <!-- Dropdown using Alpine -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-600">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <div x-show="open" class="absolute left-0 mt-2 w-32 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 shadow-lg z-10" x-cloak>
                                            <button @click="activeEditModal = {{ $tip->id }}; open = false" class="w-full text-start px-4 py-2 text-xs hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center gap-2 text-gray-700 dark:text-gray-200">
                                                <i class="bi bi-pencil text-blue-500"></i> تعديل
                                            </button>
                                            <form action="{{ route('expert.tips.destroy', $tip) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه النصيحة؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-start px-4 py-2 text-xs hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center gap-2 text-red-500">
                                                    <i class="bi bi-trash"></i> حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-0 leading-relaxed line-clamp-2">{{ $tip->content }}</p>
                                <div class="text-[10px] text-gray-400 mt-2 border-t border-gray-50 dark:border-gray-700 pt-2">
                                    <i class="bi bi-clock ml-1"></i> {{ $tip->created_at->format('Y-m-d') }}
                                </div>
                            </div>

                            <!-- Edit Modal (One per item vs Dynamic) -->
                            <!-- Simulating dynamic modal by checking activeEditModal -->
                            <div x-show="activeEditModal === {{ $tip->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                                <div class="bg-white dark:bg-gray-800 w-full max-w-md mx-4 shadow-lg" @click.away="activeEditModal = null">
                                    <div class="flex justify-between items-center p-4 border-b border-gray-100 dark:border-gray-700">
                                        <h5 class="font-bold text-gray-900 dark:text-white">تعديل النصيحة</h5>
                                        <button @click="activeEditModal = null" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <form action="{{ route('expert.tips.update', $tip) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="p-4 space-y-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 mb-1">عنوان النصيحة</label>
                                                <input type="text" name="title" class="w-full border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm p-2" value="{{ $tip->title }}" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 mb-1">محتوى النصيحة</label>
                                                <textarea name="content" rows="4" class="w-full border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm p-2" required>{{ $tip->content }}</textarea>
                                            </div>
                                        </div>
                                        <div class="p-4 pt-0">
                                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 font-bold text-sm transition">حفظ التعديلات</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-4">
                                <p class="text-gray-400 text-xs">لم تقم بإضافة أي نصائح عامة بعد.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Tip Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
            <div class="bg-white dark:bg-gray-800 w-full max-w-md mx-4 shadow-lg" @click.away="showAddModal = false">
                <div class="flex justify-between items-center p-4 border-b border-gray-100 dark:border-gray-700">
                    <h5 class="font-bold text-gray-900 dark:text-white">إضافة نصيحة جديدة</h5>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                </div>
                <form action="{{ route('expert.tips.store') }}" method="POST">
                    @csrf
                    <div class="p-4 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">عنوان النصيحة</label>
                            <input type="text" name="title" class="w-full border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm p-2" placeholder="مثلاً: أفضل وقت للري في الصيف" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">محتوى النصيحة</label>
                            <textarea name="content" rows="4" class="w-full border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm p-2" placeholder="اكتب نصيحتك العلمية هنا..." required></textarea>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 font-bold text-sm transition">نشر النصيحة</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
