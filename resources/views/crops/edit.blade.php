<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 text-dark mb-0 font-weight-bold">
                <i class="bi bi-gear-wide-connected me-2 text-success"></i>إدارة المحصول: {{ $crop->name }}
            </h2>
            <a href="{{ route('crops.index') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
                <i class="bi bi-arrow-right me-1"></i>العودة للمحاصيل
            </a>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row g-4">
            <!-- Left Column: Details & Edit Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0 text-dark">معلومات المحصول</h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <form action="{{ route('crops.update', $crop) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-muted">اسم المحصول</label>
                                    <input type="text" name="name" class="form-control rounded-pill px-4 @error('name') is-invalid @enderror" value="{{ old('name', $crop->name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">النوع</label>
                                    <input type="text" name="type" class="form-control rounded-pill px-4 @error('type') is-invalid @enderror" value="{{ old('type', $crop->type) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">المساحة (فدان)</label>
                                    <input type="number" step="0.1" name="area" class="form-control rounded-pill px-4 @error('area') is-invalid @enderror" value="{{ old('area', $crop->area) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">تاريخ الزراعة</label>
                                    <input type="date" name="planting_date" class="form-control rounded-pill px-4 @error('planting_date') is-invalid @enderror" value="{{ old('planting_date', $crop->planting_date ? $crop->planting_date->format('Y-m-d') : '') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">موعد الحصاد المتوقع</label>
                                    <input type="date" name="expected_harvest_date" class="form-control rounded-pill px-4 @error('expected_harvest_date') is-invalid @enderror" value="{{ old('expected_harvest_date', $crop->expected_harvest_date ? $crop->expected_harvest_date->format('Y-m-d') : '') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">نوع التربة</label>
                                    <input type="text" name="soil_type" class="form-control rounded-pill px-4" value="{{ old('soil_type', $crop->soil_type) }}" placeholder="طينية، رملية، إلخ">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">طريقة الري</label>
                                    <input type="text" name="irrigation_method" class="form-control rounded-pill px-4" value="{{ old('irrigation_method', $crop->irrigation_method) }}" placeholder="تنقيط، رش، غمر">
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">ملاحظات إضافية</label>
                                    <textarea name="notes" class="form-control rounded-3 px-3 py-2" rows="3">{{ old('notes', $crop->notes) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">إضافة صور للمحصول</label>
                                    <input type="file" name="images[]" class="form-control rounded-pill px-4" multiple accept="image/*">
                                    <small class="text-muted d-block mt-1 px-3">يمكنك اختيار عدة صور في آن واحد</small>
                                </div>

                                <div class="col-12 pt-3">
                                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm">
                                        حفظ التعديلات
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Growth & Tasks -->
            <div class="col-lg-5">
                <!-- Manual Growth Update -->
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="card-header bg-success text-white py-3 px-3 border-0">
                        <h5 class="fw-bold mb-0">تحديث النمو</h5>
                    </div>
                    <div class="card-body p-3 text-center">
                        <div class="growth-display mb-3">
                            <h2 class="fw-bold text-success mb-0">{{ $crop->growth_percentage }}%</h2>
                            <p class="text-muted small">الحالة: {{ $crop->growth_stage_label }}</p>
                        </div>
                        
                        <form action="{{ route('crops.updateGrowth', $crop) }}" method="POST">
                            @csrf
                            <input type="range" name="growth_percentage" class="form-range" min="0" max="100" value="{{ $crop->growth_percentage }}" id="growthRange">
                            <div class="d-flex justify-content-between small text-muted mb-4">
                                <span>0%</span>
                                <span>50%</span>
                                <span>100% (حصاد)</span>
                            </div>
                            <button type="submit" class="btn btn-outline-success rounded-pill px-4 fw-bold">
                                تحديث نسبة النمو
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Add New Task Quick Access -->
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-dark text-white py-3 px-3 border-0">
                        <h5 class="fw-bold mb-0">إضافة مهمة</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">وصف المهمة</label>
                                    <input type="text" name="title" class="form-control rounded-pill px-4" placeholder="مثال: ري القطاع الغربي" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">نوع المهمة</label>
                                    <select name="type" class="form-select rounded-pill px-4 shadow-none">
                                        <option value="irrigation">ري 💧</option>
                                        <option value="fertilizer">تسميد 🌱</option>
                                        <option value="pest">مكافحة 🐛</option>
                                        <option value="harvest">حصاد 🧺</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">التاريخ والوقت</label>
                                    <input type="datetime-local" name="due_date" class="form-control rounded-pill px-4" required>
                                </div>
                                <div class="col-12 pt-2 text-center">
                                    <button type="submit" class="btn btn-dark w-100 rounded-pill py-2 fw-bold">إضافة المهمة</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
