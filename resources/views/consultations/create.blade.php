<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0" style="color: var(--heading-color);">
            طلب استشارة جديدة (New Consultation)
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-0 p-4" style="background-color: var(--bg-secondary);">
                    <form action="{{ route('consultations.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: var(--heading-color);">الموضوع (Subject)</label>
                            <input type="text" name="subject" class="form-control" placeholder="مثال: ذبول أوراق الطماطم" style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color);" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--heading-color);">الفئة (Category)</label>
                                <select name="category" class="form-select" style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color);" required>
                                    <option value="">اختر فئة...</option>
                                    <option value="الري">الري (Irrigation)</option>
                                    <option value="الآفات والأمراض">الآفات والأمراض (Pests & Diseases)</option>
                                    <option value="التسميد">التسميد (Fertilizer)</option>
                                    <option value="التربة">التربة (Soil)</option>
                                    <option value="أخرى">أخرى (Other)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--heading-color);">المحصول المرتبط (اختياري)</label>
                                <select name="crop_id" class="form-select" style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color);">
                                    <option value="">لا يوجد محصول محدد</option>
                                    @foreach($crops as $crop)
                                        <option value="{{ $crop->id }}">{{ $crop->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: var(--heading-color);">سؤالك بالتفصيل (Your Question)</label>
                            <textarea name="question" class="form-control" rows="6" placeholder="اوصف المشكلة بوضوح، مثلاً: متى تظهر، لون الأوراق، نوع السماد المستخدم حالياً..." style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color);" required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg rounded-0 fw-bold">
                                إرسال الطلب للخبير
                            </button>
                            <a href="{{ route('consultations.index') }}" class="btn btn-link text-decoration-none" style="color: var(--text-secondary);">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
