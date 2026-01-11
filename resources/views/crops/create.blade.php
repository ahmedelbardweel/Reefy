<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            {{ __('Add New Crop') }} (إضافة محصول جديد)
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-seedling"></i> Crop Details Form</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('crops.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Section 1: Basic Information -->
                            <h6 class="text-success fw-bold border-bottom pb-2 mb-3">1. Basic Information (بيانات أساسية)</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">Crop Name (اسم القطعة/المحصول) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="e.g. North Field Wheat (قمح الحقل الشمالي)" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="type" class="form-label fw-bold">Crop Type (نوع النبات) <span class="text-danger">*</span></label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="" selected disabled>Select Type...</option>
                                        <option value="Wheat">Wheat (قمح)</option>
                                        <option value="Corn">Corn (ذرة)</option>
                                        <option value="Rice">Rice (أرز)</option>
                                        <option value="Tomato">Tomato (طماطم)</option>
                                        <option value="Potato">Potato (بطاطس)</option>
                                        <option value="Cotton">Cotton (قطن)</option>
                                        <option value="Other">Other (آخر)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Image Upload Section -->
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <label class="form-label fw-bold text-success"><i class="bi bi-images me-1"></i> {{ __('Crop Images (صور المحصول)') }}</label>
                                    <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(this)">
                                    <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-3"></div>
                                    <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle me-1"></i> يمكنك اختيار عدة صور لمتابعة مراحل نمو المحصول المختلفة.</small>
                                </div>
                            </div>

                            <script>
                                function previewImages(input) {
                                    const preview = document.getElementById('imagePreview');
                                    preview.innerHTML = '';
                                    if (input.files) {
                                        Array.from(input.files).forEach(file => {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                const div = document.createElement('div');
                                                div.className = 'position-relative';
                                                div.innerHTML = `
                                                    <img src="${e.target.result}" class="rounded shadow-sm" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #fff;">
                                                `;
                                                preview.appendChild(div);
                                            }
                                            reader.readAsDataURL(file);
                                        });
                                    }
                                }
                            </script>

                            <!-- Section 2: Farm Details -->
                            <h6 class="text-success fw-bold border-bottom pb-2 mb-3">2. Land & Soil Details (بيانات الأرض والتربة)</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="area" class="form-label fw-bold">Area (المساحة) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control" id="area" name="area" placeholder="e.g. 2.5" required>
                                        <span class="input-group-text">Feddan/Acres</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="soil_type" class="form-label">Soil Type (نوع التربة)</label>
                                    <select class="form-select" id="soil_type" name="soil_type">
                                        <option value="" selected>Unknown</option>
                                        <option value="Clay">Clay (طينية)</option>
                                        <option value="Sandy">Sandy (رملية)</option>
                                        <option value="Loamy">Loamy (طميية)</option>
                                        <option value="Silty">Silty (غرينية)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="irrigation_method" class="form-label">Irrigation Method (طريقة الري)</label>
                                    <select class="form-select" id="irrigation_method" name="irrigation_method">
                                        <option value="" selected>Unknown</option>
                                        <option value="Flood">Flood Irrigation (ري بالغمر)</option>
                                        <option value="Drip">Drip Irrigation (ري بالتنقيط)</option>
                                        <option value="Sprinkler">Sprinkler (ري بالرش)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Section 3: Planting Details -->
                            <h6 class="text-success fw-bold border-bottom pb-2 mb-3">3. Planting & Yield (الزراعة والإنتاج)</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="planting_date" class="form-label fw-bold">Planting Date (تاريخ الزراعة) <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="planting_date" name="planting_date" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="expected_harvest_date" class="form-label fw-bold text-success">Harvest Date (تاريخ الحصاد) <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control border-success" id="expected_harvest_date" name="expected_harvest_date" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="seed_source" class="form-label">Seed Source (مصدر البذور)</label>
                                    <input type="text" class="form-control" id="seed_source" name="seed_source" placeholder="e.g. Ministry of Agriculture">
                                </div>
                                <div class="col-md-3">
                                    <label for="yield_estimate" class="form-label">Est. Yield (الإنتاج المتوقع)</label>
                                    <input type="number" step="0.1" class="form-control" id="yield_estimate" name="yield_estimate" placeholder="Optional">
                                </div>
                            </div>

                             <!-- Section 4: Additional Notes -->
                             <div class="mb-4">
                                <label for="notes" class="form-label fw-bold">Additional Notes (ملاحظات إضافية)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any specific details about this planting cycle..."></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('crops.index') }}" class="btn btn-light px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary px-5 fw-bold">
                                    <i class="bi bi-check-lg"></i> Save & Generate Plan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
