@if(session('success') && str_contains(session('success'), $data['data']['name'] ?? ''))
    <div class="mt-3 p-4 text-center rounded-3 shadow-sm" style="background-color: #f8fff9; border: 1px solid #c3e6cb;">
        <div class="mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#28a745" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
        </div>
        <h6 class="fw-bold" style="color: #155724;">تمت إضافة المحصول بنجاح!</h6>
        <p class="small mb-0 text-secondary">لقد قمنا بإدراج <strong>{{ $data['data']['name'] }}</strong> في قائمة محاصيلك بنجاح.</p>

        <div class="mt-2 py-1 px-3 d-inline-block rounded-pill" style="background-color: #e6f4ea; color: #1e7e34; font-size: 0.8rem;">
            <i class="fas fa-database me-1"></i> محدث في قاعدة البيانات
        </div>
    </div>
@else
    <div class="mt-3 p-3 border-0 rounded-3 shadow-sm bg-white" style="border-right: 4px solid #28a745 !important;">
        <div class="d-flex align-items-center mb-3">
            <div class="p-2 bg-success bg-opacity-10 rounded-circle me-2">
                <i class="fas fa-magic text-success"></i>
            </div>
            <h6 class="mb-0 fw-bold">إجراء زراعي مقترح</h6>
        </div>

        <form action="{{ route('farmer.ai.execute') }}" method="POST">
            @csrf
            <input type="hidden" name="action_type" value="{{ $data['action'] }}">

            <div class="row g-2">
                @foreach($data['data'] as $key => $value)
                    <div class="col-6">
                        <div class="form-floating mb-2">
                            <input type="text" name="data[{{ $key }}]" value="{{ $value }}" class="form-control form-control-sm border-light bg-light" id="floatingInput{{ $loop->index }}">
                            <label for="floatingInput{{ $loop->index }}" class="small text-muted">{{ $key }}</label>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-success w-100 mt-2 py-2 fw-bold shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-check me-1"></i> تأكيد وتنفيذ العملية
            </button>
        </form>
    </div>
@endif
