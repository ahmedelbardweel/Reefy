<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            طلب استشارة جديدة (New Consultation)
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <form action="{{ route('consultations.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">الموضوع (Subject)</label>
                            <input type="text" name="subject" class="form-control" placeholder="مثال: ذبول أوراق الطماطم" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">الفئة (Category)</label>
                                <select name="category" class="form-select" required>
                                    <option value="">اختر فئة...</option>
                                    <option value="الري">الري (Irrigation)</option>
                                    <option value="الآفات والأمراض">الآفات والأمراض (Pests & Diseases)</option>
                                    <option value="التسميد">التسميد (Fertilizer)</option>
                                    <option value="التربة">التربة (Soil)</option>
                                    <option value="أخرى">أخرى (Other)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">المحصول المرتبط (اختياري)</label>
                                <select name="crop_id" class="form-select">
                                    <option value="">لا يوجد محصول محدد</option>
                                    @foreach($crops as $crop)
                                        <option value="{{ $crop->id }}">{{ $crop->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">سؤالك بالتفصيل (Your Question)</label>
                            <textarea name="question" class="form-control" rows="6" placeholder="اوصف المشكلة بوضوح، مثلاً: متى تظهر، لون الأوراق، نوع السماد المستخدم حالياً..." required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold">
                                إرسال الطلب للخبير
                            </button>
                            <a href="{{ route('consultations.index') }}" class="btn btn-link text-decoration-none text-muted">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
