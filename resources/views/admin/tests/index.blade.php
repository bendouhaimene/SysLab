@extends('layouts.app')
@section('title', 'Lab Tests')
@section('page-title', 'Lab Tests Management')

@section('content')

{{-- ── Stats ── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px;">
    @php
        $categories = $tests->groupBy('category');
    @endphp
    <div style="background:var(--surface);border:1px solid var(--border);
                border-radius:12px;padding:14px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(79,142,247,.1);
                    display:flex;align-items:center;justify-content:center;color:var(--blue);font-size:18px;">
            <i class="bi bi-eyedropper"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--text);">{{ $tests->count() }}</div>
            <div style="font-size:11px;color:var(--text2);">Active Tests</div>
        </div>
    </div>
    <div style="background:var(--surface);border:1px solid var(--border);
                border-radius:12px;padding:14px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(16,185,129,.1);
                    display:flex;align-items:center;justify-content:center;color:var(--green);font-size:18px;">
            <i class="bi bi-grid"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--text);">{{ $categories->count() }}</div>
            <div style="font-size:11px;color:var(--text2);">Categories</div>
        </div>
    </div>
    <div style="background:var(--surface);border:1px solid var(--border);
                border-radius:12px;padding:14px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,.1);
                    display:flex;align-items:center;justify-content:center;color:var(--amber);font-size:18px;">
            <i class="bi bi-cash"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--text);">
                {{ number_format($tests->avg('price')) }}
            </div>
            <div style="font-size:11px;color:var(--text2);">Avg Price (DZD)</div>
        </div>
    </div>
    <div style="background:var(--surface);border:1px solid var(--border);
                border-radius:12px;padding:14px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(226,75,74,.1);
                    display:flex;align-items:center;justify-content:center;color:var(--red);font-size:18px;">
            <i class="bi bi-archive"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--text);">{{ $archived->count() }}</div>
            <div style="font-size:11px;color:var(--text2);">Archived</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:18px;align-items:start;">

    {{-- ── Tests Table ── --}}
    <div>
        <div class="section-card">
            <div class="section-head">
                <span class="section-title">
                    <i class="bi bi-eyedropper me-2" style="color:var(--blue);"></i>
                    Active Tests Catalogue
                </span>
                <div style="display:flex;gap:8px;align-items:center;">
                    {{-- Category filter --}}
                    <select onchange="filterCategory(this.value)"
                            style="background:var(--surface2);border:1px solid var(--border);
                                   border-radius:8px;padding:5px 10px;font-size:12px;
                                   color:var(--text2);font-family:'Inter',sans-serif;outline:none;">
                        <option value="">All Categories</option>
                        @foreach($categories->keys() as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    {{-- Search --}}
                    <div style="position:relative;">
                        <i class="bi bi-search"
                           style="position:absolute;left:10px;top:50%;transform:translateY(-50%);
                                  color:var(--text3);font-size:12px;"></i>
                        <input type="text" id="testSearch" oninput="filterTests()"
                               placeholder="Search..."
                               style="background:var(--surface2);border:1px solid var(--border);
                                      border-radius:8px;padding:6px 10px 6px 28px;
                                      font-size:12px;color:var(--text);font-family:'Inter',sans-serif;
                                      outline:none;width:160px;">
                    </div>
                </div>
            </div>
            <table class="sl-table" id="testsTable">
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Category</th>
                        <th>Price (DZD)</th>
                        <th>Normal Range</th>
                        <th>Unit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tests as $test)
                    <tr data-category="{{ $test->category }}">
                        <td style="color:var(--text);font-weight:500;">{{ $test->name }}</td>
                        <td>
                            @php
                                $catColors = [
                                    'Hematology'   => 'badge-blue',
                                    'Biochemistry' => 'badge-green',
                                    'Immunology'   => 'badge-amber',
                                    'Microbiology' => 'badge-red',
                                ];
                                $cc = $catColors[$test->category] ?? 'badge-gray';
                            @endphp
                            <span class="badge-sl {{ $cc }}">{{ $test->category }}</span>
                        </td>
                        <td>
                            <span style="font-weight:600;color:var(--text);">
                                {{ number_format($test->price) }}
                            </span>
                        </td>
                        <td style="font-size:12px;color:var(--text2);">
                            @if($test->normal_min !== null && $test->normal_max !== null)
                                <span style="color:var(--green);">
                                    {{ $test->normal_min }} – {{ $test->normal_max }}
                                </span>
                            @elseif($test->normal_label)
                                {{ $test->normal_label }}
                            @else
                                <span style="color:var(--text3);">—</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--text2);">
                            {{ $test->unit ?? '—' }}
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <button onclick="openEdit({{ $test->id }},
                                    '{{ addslashes($test->name) }}',
                                    '{{ addslashes($test->category) }}',
                                    '{{ $test->price }}',
                                    '{{ $test->unit }}',
                                    '{{ $test->normal_min }}',
                                    '{{ $test->normal_max }}',
                                    '{{ addslashes($test->normal_label ?? '') }}')"
                                    class="btn-sl btn-sm-sl">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.tests.archive', $test) }}"
                                      method="POST"
                                      onsubmit="return confirm('Archive this test?')">
                                    @csrf
                                    <button type="submit" class="btn-sl btn-sm-sl"
                                            style="color:var(--amber);border-color:rgba(245,158,11,.2);">
                                        <i class="bi bi-archive"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:var(--text3);">
                            <i class="bi bi-eyedropper" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                            No tests yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Archived Tests ── --}}
        @if($archived->count() > 0)
        <div class="section-card">
            <div class="section-head">
                <span class="section-title">
                    <i class="bi bi-archive me-2" style="color:var(--red);"></i>
                    Archived Tests
                </span>
                <span class="badge-sl badge-red">{{ $archived->count() }}</span>
            </div>
            <table class="sl-table">
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($archived as $test)
                    <tr style="opacity:.6;">
                        <td style="color:var(--text);">{{ $test->name }}</td>
                        <td><span class="badge-sl badge-gray">{{ $test->category }}</span></td>
                        <td>{{ number_format($test->price) }} DZD</td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <form action="{{ route('admin.tests.restore', $test) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-sl btn-sm-sl"
                                            style="color:var(--green);border-color:rgba(16,185,129,.2);">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                                <form action="{{ route('admin.tests.destroy', $test) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete permanently?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sl btn-sm-sl btn-danger-sl">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- ── Add / Edit Form ── --}}
    <div>
        <div class="section-card" id="test-form-card">
            <div class="section-head">
                <span class="section-title" id="form-title">
                    <i class="bi bi-plus-circle me-2" style="color:var(--green);"></i>
                    Add New Test
                </span>
            </div>
            <div style="padding:20px;">
                <form id="test-form"
                      action="{{ route('admin.tests.store') }}"
                      method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="test_id" id="form-test-id">

                    <div style="margin-bottom:14px;">
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:5px;">
                            Test Name <span style="color:var(--red)">*</span>
                        </label>
                        <input type="text" name="name" id="f-name"
                               class="form-control-sl" placeholder="e.g. NFS (CBC)" required>
                    </div>

                    <div style="margin-bottom:14px;">
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:5px;">
                            Category <span style="color:var(--red)">*</span>
                        </label>
                        <select name="category" id="f-category" class="form-select-sl" required>
                            <option value="">— Select —</option>
                            <option value="Hematology">Hematology</option>
                            <option value="Biochemistry">Biochemistry</option>
                            <option value="Immunology">Immunology</option>
                            <option value="Microbiology">Microbiology</option>
                            <option value="Serology">Serology</option>
                            <option value="Parasitology">Parasitology</option>
                            <option value="Hormones">Hormones</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div style="margin-bottom:14px;">
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:5px;">
                            Price (DZD) <span style="color:var(--red)">*</span>
                        </label>
                        <input type="number" name="price" id="f-price"
                               class="form-control-sl" placeholder="e.g. 850"
                               min="0" step="50" required>
                    </div>

                    <div style="margin-bottom:14px;">
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:5px;">Unit</label>
                        <input type="text" name="unit" id="f-unit"
                               class="form-control-sl" placeholder="e.g. mmol/L">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                        <div>
                            <label style="font-size:12px;font-weight:500;color:var(--text2);
                                          display:block;margin-bottom:5px;">Normal Min</label>
                            <input type="number" name="normal_min" id="f-min"
                                   class="form-control-sl" placeholder="e.g. 3.9" step="0.01">
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:500;color:var(--text2);
                                          display:block;margin-bottom:5px;">Normal Max</label>
                            <input type="number" name="normal_max" id="f-max"
                                   class="form-control-sl" placeholder="e.g. 5.5" step="0.01">
                        </div>
                    </div>

                    <div style="margin-bottom:20px;">
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:5px;">
                            Normal Label
                            <span style="color:var(--text3);font-weight:400;">
                                (if no numeric range)
                            </span>
                        </label>
                        <input type="text" name="normal_label" id="f-label"
                               class="form-control-sl" placeholder="e.g. Negative">
                    </div>

                    <div style="display:flex;gap:8px;">
                        <button type="submit" class="btn-sl btn-primary-sl" style="flex:1;">
                            <i class="bi bi-check-lg" id="form-btn-icon"></i>
                            <span id="form-btn-text">Add Test</span>
                        </button>
                        <button type="button" onclick="resetForm()" class="btn-sl">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Filter by search
function filterTests() {
    const q = document.getElementById('testSearch').value.toLowerCase();
    document.querySelectorAll('#testsTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

// Filter by category
function filterCategory(cat) {
    document.querySelectorAll('#testsTable tbody tr').forEach(row => {
        row.style.display = (!cat || row.dataset.category === cat) ? '' : 'none';
    });
}

// Open edit form
function openEdit(id, name, category, price, unit, min, max, label) {
    document.getElementById('form-title').innerHTML =
        '<i class="bi bi-pencil me-2" style="color:var(--amber);"></i>Edit Test';
    document.getElementById('form-method').value   = 'PUT';
    document.getElementById('test-form').action    =
        '/admin/tests/' + id;
    document.getElementById('f-name').value     = name;
    document.getElementById('f-category').value = category;
    document.getElementById('f-price').value    = price;
    document.getElementById('f-unit').value     = unit || '';
    document.getElementById('f-min').value      = min  || '';
    document.getElementById('f-max').value      = max  || '';
    document.getElementById('f-label').value    = label || '';
    document.getElementById('form-btn-icon').className = 'bi bi-check-lg';
    document.getElementById('form-btn-text').textContent = 'Save Changes';
    document.getElementById('test-form-card').scrollIntoView({ behavior: 'smooth' });
}

// Reset form
function resetForm() {
    document.getElementById('form-title').innerHTML =
        '<i class="bi bi-plus-circle me-2" style="color:var(--green);"></i>Add New Test';
    document.getElementById('form-method').value = 'POST';
    document.getElementById('test-form').action  = '{{ route("admin.tests.store") }}';
    document.getElementById('test-form').reset();
    document.getElementById('form-btn-text').textContent = 'Add Test';
}
</script>
@endpush