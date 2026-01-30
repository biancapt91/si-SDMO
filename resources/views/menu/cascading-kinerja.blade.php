@extends('layouts.app')

@section('sidebar')

<style>
    .sidebar-card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.sidebar-title {
    background: #7a0c0c; /* maroon */
    color: #fff;
    border-radius: 12px 12px 0 0;
    padding: 10px 15px;
}

.sidebar-menu {
    list-style: none;
    padding-left: 0;
    margin-bottom: 0;
}

.sidebar-menu li {
    margin-bottom: 4px;
}

.sidebar-menu a {
    display: block;
    padding: 6px 10px;
    color: #333;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
}

.sidebar-menu a:hover {
    background-color: #f2f2f2;
}
.sidebar-menu a.active {
    background-color: #f2f2f2;
    font-weight: 600;
}

.sidebar-menu ul {
    list-style: none;
    padding-left: 15px;
}

.sidebar-menu ul {
    list-style: none;
    padding-left: 16px;
    display: none;
}



.sidebar-menu li > a {
    display: block;
    padding: 6px 10px;
    border-radius: 6px;
    color: #222;
    text-decoration: none;
}

.sidebar-menu li > a:hover {
    background: rgba(122,12,12,0.08);
}

.sidebar-menu li.open > ul {
    display: block;
}

.sidebar-menu li > a::before {
    content: "▸";
    display: inline-block;
    width: 12px;
    margin-right: 6px;
    transition: .2s;
}

.sidebar-menu li.open > a::before {
    transform: rotate(90deg);
}


/* Cascading Kinerja modern table styles */
.ck-toolbar { display:flex; justify-content:space-between; gap:8px; align-items:center; margin-bottom:12px; }
.ck-toolbar .btn { min-width: 38px; }
#ck-table { border-collapse: separate; border-spacing: 0; overflow: hidden; border-radius: 8px; width:100%; }
#ck-table thead th { background: linear-gradient(180deg,#ffffff,#f8f9fb); border-bottom:1px solid #e9ecef; padding:12px 14px; font-weight:600;}
#ck-table tbody td { padding:12px 14px; vertical-align:middle; background:#fff; }
#ck-table tbody tr:hover td { background:#f8f9fb; }
#ck-table td[contenteditable] { outline:none; cursor:text; }
#ck-table td:focus { box-shadow: inset 0 0 0 2px rgba(0,123,255,0.08); }
.ck-empty { color:#6c757d; padding:28px; background:#fff; border-radius:6px; text-align:center; }
.ck-toolbar .btn i { font-size:14px; }

/* Modern colorful enhancements */
.ck-table-card { background: #f5ccbcff; border-radius:10px; padding: 8px; box-shadow: 0 6px 20px rgba(16,24,40,0.06); border: 1px solid rgba(16,24,40,0.04); }
/* Soft maroon header */
.ck-table thead th { background:  #b11532ff; border-bottom: 1px solid rgba(255, 255, 255, 0.06); color:#ffffff; padding:16px 18px; font-size:15px; }
.ck-table tbody td { padding:14px 18px; color:#1f2937; }
.ck-table tbody tr:nth-child(odd) td { background: linear-gradient(90deg,#ffffff,#fbfbff); }
.ck-table tbody tr:hover td { background: linear-gradient(90deg,#f8fbff,#ffffff); transform: translateY(-1px); transition: all 120ms ease; }

/* Toolbar colored buttons */
.btn-ck-add { background: linear-gradient(90deg,#2563eb,#3b82f6); color:#fff; border:none; box-shadow: 0 3px 8px rgba(59,130,246,0.18); }
.btn-ck-add:hover { filter: brightness(0.95); transform: translateY(-2px); }
.btn-ck-col { background: linear-gradient(90deg,#7c3aed,#6d28d9); color:#fff; border:none; box-shadow: 0 3px 8px rgba(124,58,237,0.14); }
.btn-ck-save { background: linear-gradient(90deg,#059669,#10b981); color:#fff; border:none; box-shadow: 0 3px 8px rgba(16,185,129,0.12); }
.btn-ck-reset { background: linear-gradient(90deg,#475569,#64748b); color:#fff; border:none; }

.btn-ck-add i, .btn-ck-col i, .btn-ck-save i, .btn-ck-reset i { font-size:16px; }
.ck-toolbar .text-muted.small { color:#374151; font-weight:600; }

/* row accent and misc */
.ck-table tbody td:first-child { border-left: 4px solid rgba(122,12,12,0.06); padding-left: 12px; }
.ck-table thead th { letter-spacing: 0.2px; }
.ck-remove-row { min-width: 72px; }
.ck-empty:before { content: "\1F4C4"; display:block; font-size:28px; margin-bottom:8px; }

.ck-cell-selected {
    background: rgba(59,130,246,0.18) !important;
    outline: 2px solid rgba(59,130,246,0.6);
}

.mention-box{
    position:absolute;
    background:#fff;
    border-radius:8px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
    border:1px solid #ddd;
    width:320px;
    max-height:240px;
    overflow:auto;
    z-index:9999;
}

.mention-item{
    padding:10px 14px;
    cursor:pointer;
    border-bottom:1px solid #eee;
}

.mention-item:hover{
    background:#f3f4f6;
}


@media (max-width: 768px) { .sidebar-left { display:none; } .content-wrapper { margin-left:0; padding:20px; } }
</style> 

    <div class="card sidebar-card">
    <div class="card-header sidebar-title">
        <strong>Cascading Kinerja</strong>
    </div>

    <div class="card-body p-2">
        <ul class="sidebar-menu">

            {{-- Biro SDM dan Organisasi --}}
            <li>
                <a href="#" class="ck-open" data-section="biro-sdm-organisasi">Biro SDM dan Organisasi</a>
                <ul>
                    <li><a href="#" class="ck-open" data-section="bagian-sdm">Bagian Sumber Daya Manusia</a>
                        <ul>
                            <li><a href="#" class="ck-open" data-section="sub-bagian-adm-hakim-pegawai">Sub Bagian Administrasi Hakim dan Pegawai</a></li>
                            <li><a href="#" class="ck-open" data-section="sub-bagian-pembinaan-pengembangan-non-pns">Sub Bagian Pembinaan & Pengembangan Pegawai Non PNS</a></li>
                            <li><a href="#" class="ck-open" data-section="sub-bagian-pengembangan-sdm">Sub Bagian Pengembangan SDM</a></li>
                        </ul>
                    </li>

                      {{-- Bagian Ortala dan Fasilitasi RB --}}
                    <li>
                        <a href="#" class="ck-open" data-section="bagian-ortala">Bagian Ortala dan Fasilitasi RB</a>
                        <ul>
                            <li><a href="#" class="ck-open" data-section="sub-bagian-organisasi-tata-laksana">Sub Bagian Organisasi dan Tata Laksana</a></li>
                            <li><a href="#" class="ck-open" data-section="sub-bagian-fasilitasi-rb">Sub Bagian Fasilitasi Reformasi Birokrasi</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>

{{-- ================= CARD REKAP TIM KERJA ================= --}}
<div class="sidebar-card mt-3">
    <div class="sidebar-title">
        Rekap Tim Kerja
    </div>

    <div class="p-2">
        @forelse($rekapTim as $item)
            <div class="d-flex justify-content-between small mb-1">
                <span>{{ $item['nama'] }}</span>
                <span class="badge bg-primary">{{ $item['jumlah'] }}</span>
            </div>
        @empty
            <div class="text-muted small text-center">
                Belum ada data tim kerja
            </div>
        @endforelse
    </div>
</div>


@endsection

@section('content')
    <div class="container">
        <h2 class="fw-bold mb-3">Cascading Kinerja</h2>

        @php $isAdmin = auth()->check() && auth()->user()->isAdmin(); @endphp

        {{-- Render a section for each configured key --}}
        @foreach($sections as $key => $label)
            @php $data = $items[$key]->data ?? ['headers'=>['Sasaran','Indikator','Target'],'rows'=>[]]; @endphp
            <div id="ck-main-{{ $key }}" class="ck-main-section" style="display:none" data-key="{{ $key }}">
                <div id="ck-table-container-{{ $key }}">
                    <div class="ck-toolbar">
                        <div class="text-muted small">{{ $label }}</div>
                        @if($isAdmin)
                            <div class="btn-group" role="group" aria-label="table controls">
                                <button class="btn btn-sm ck-add-row btn-ck-add" title="Add row"><i class="bi bi-plus" aria-hidden="true"></i></button> 
                                <button class="btn btn-sm ck-add-col btn-ck-col" title="Add column"><i class="bi bi-columns-gap" aria-hidden="true"></i></button> 
                                <button class="btn btn-sm ck-save btn-ck-save" title="Save"><i class="bi bi-save" aria-hidden="true"></i></button> 
                                <button class="btn btn-secondary btn-sm ck-reset btn-ck-reset" title="Reset"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i></button> 
                                <button class="btn btn-sm btn-warning ck-merge" title="Merge cells"><i class="bi bi-intersect"></i></button>
                                <button class="btn btn-sm btn-outline-warning ck-unmerge" title="Unmerge cells"><i class="bi bi-layout-sidebar-inset"></i></button>
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <div class="ck-table-card p-3 mb-3">
                        <table class="ck-table table table-sm table-border shadow-sm mb-0">
                            <thead class="bg-white">
                            <tr>
                                @foreach($data['headers'] as $h)
                                @php $isTimKerja = strtolower(trim($h)) === 'tim kerja'; @endphp
                                <th
                                   @if($isAdmin) class="ck-header" title="Click to rename" @endif
                                   @if($isTimKerja) data-col="tim-kerja" @endif
                                >
                                {{ $h }}
                                </th>
                                @endforeach

                                @if($isAdmin)<th style="width:60px">&nbsp;</th>@endif
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($data['rows'])===0)
                                <tr><td colspan="{{ count($data['headers']) + ($isAdmin ? 1:0) }}"><div class="ck-empty">No data yet. Admin can add rows or columns to begin.</div></td></tr>
                            @else
                                @foreach($data['rows'] as $row)
                                    <tr>
                                        @foreach($row as $cell)
                                            <td @if($isAdmin) contenteditable="true" class="mention-cell ck-cell" @endif>{{ $cell }}</td>
                                        @endforeach
                                        @if($isAdmin)
                                            <td class="align-middle"><button class="btn btn-sm btn-danger ck-remove-row">Delete</button></td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach

    </div>
    <div id="mentionBox" class="mention-box d-none"></div>

@endsection

@push('scripts')
<script>
(function(){
    // show/hide per-section when sidebar item clicked
    const sections = document.querySelectorAll('.ck-main-section');

    function hideAllSections(){
        sections.forEach(s => s.style.display = 'none');
    }

    document.querySelectorAll('.ck-open').forEach(el => {
        el.addEventListener('click', function(e){
            e.preventDefault();
            const key = this.dataset.section;
            if (!key) return;
            const target = document.querySelector('.ck-main-section[data-key="' + key + '"]');
            if (target){
                hideAllSections();
                target.style.display = 'block';
                target.scrollIntoView({behavior:'smooth'});

                // mark active in sidebar
                document.querySelectorAll('.ck-open').forEach(a => a.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Initialize a single section's behavior
    function initSection(sectionEl){
        const table = sectionEl.querySelector('.ck-table');
        if (!table) return;
        const isAdmin = !!sectionEl.querySelector('.ck-add-row');

        function attachRowButtons(){
            sectionEl.querySelectorAll('.ck-remove-row').forEach(btn => {
                btn.onclick = function(){ if (!confirm('Delete this row?')) return; this.closest('tr').remove(); }
            });

            // right-click context menu on rows
            sectionEl.querySelectorAll('tbody tr').forEach(tr => {
                tr.addEventListener('contextmenu', function(e){
                    e.preventDefault();
                    const menu = document.createElement('div');
                    menu.style.cssText = 'position:fixed;top:' + e.clientY + 'px;left:' + e.clientX + 'px;background:#fff;border:1px solid #ddd;border-radius:4px;box-shadow:0 2px 8px rgba(0,0,0,0.15);z-index:9999;padding:4px 0;';
                    const insertBefore = document.createElement('a');
                    insertBefore.textContent = 'Insert row before';
                    insertBefore.href = '#';
                    insertBefore.style.cssText = 'display:block;padding:8px 16px;text-decoration:none;color:#333;font-size:14px;';
                    insertBefore.addEventListener('click', function(ev){ ev.preventDefault(); insertRowBefore(tr); document.body.removeChild(menu); });
                    insertBefore.addEventListener('mouseenter', function(){ insertBefore.style.background = '#f2f2f2'; });
                    insertBefore.addEventListener('mouseleave', function(){ insertBefore.style.background = ''; });
                    menu.appendChild(insertBefore);

                    const insertAfter = document.createElement('a');
                    insertAfter.textContent = 'Insert row after';
                    insertAfter.href = '#';
                    insertAfter.style.cssText = 'display:block;padding:8px 16px;text-decoration:none;color:#333;font-size:14px;border-top:1px solid #eee;';
                    insertAfter.addEventListener('click', function(ev){ ev.preventDefault(); insertRowBefore(tr.nextElementSibling); document.body.removeChild(menu); });
                    insertAfter.addEventListener('mouseenter', function(){ insertAfter.style.background = '#f2f2f2'; });
                    insertAfter.addEventListener('mouseleave', function(){ insertAfter.style.background = ''; });
                    menu.appendChild(insertAfter);

                    document.body.appendChild(menu);
                    document.addEventListener('click', function(){ if (document.body.contains(menu)) document.body.removeChild(menu); }, {once: true});
                });
            });

            if (isAdmin) {
                sectionEl.querySelectorAll('.ck-header').forEach(th => {
                    th.style.cursor = 'pointer';
                    th.onclick = function(){
                        const newName = prompt('Rename column', th.innerText.trim());
                        if (newName !== null) th.innerText = newName;
                    }
                });

                // right-click context menu on columns
                sectionEl.querySelectorAll('thead th:not(:last-child)').forEach(th => {
                    th.addEventListener('contextmenu', function(e){
                        e.preventDefault();
                        const menu = document.createElement('div');
                        menu.style.cssText = 'position:fixed;top:' + e.clientY + 'px;left:' + e.clientX + 'px;background:#fff;border:1px solid #ddd;border-radius:4px;box-shadow:0 2px 8px rgba(0,0,0,0.15);z-index:9999;padding:4px 0;';
                        const insertBefore = document.createElement('a');
                        insertBefore.textContent = 'Insert column before';
                        insertBefore.href = '#';
                        insertBefore.style.cssText = 'display:block;padding:8px 16px;text-decoration:none;color:#333;font-size:14px;';
                        insertBefore.addEventListener('click', function(ev){ ev.preventDefault(); insertColBefore(th); document.body.removeChild(menu); });
                        insertBefore.addEventListener('mouseenter', function(){ insertBefore.style.background = '#f2f2f2'; });
                        insertBefore.addEventListener('mouseleave', function(){ insertBefore.style.background = ''; });
                        menu.appendChild(insertBefore);

                        const insertAfter = document.createElement('a');
                        insertAfter.textContent = 'Insert column after';
                        insertAfter.href = '#';
                        insertAfter.style.cssText = 'display:block;padding:8px 16px;text-decoration:none;color:#333;font-size:14px;border-top:1px solid #eee;';
                        insertAfter.addEventListener('click', function(ev){ ev.preventDefault(); insertColBefore(th.nextElementSibling); document.body.removeChild(menu); });
                        insertAfter.addEventListener('mouseenter', function(){ insertAfter.style.background = '#f2f2f2'; });
                        insertAfter.addEventListener('mouseleave', function(){ insertAfter.style.background = ''; });
                        menu.appendChild(insertAfter);

                        document.body.appendChild(menu);
                        document.addEventListener('click', function(){ if (document.body.contains(menu)) document.body.removeChild(menu); }, {once: true});
                    });
                });
            }
        }

        // =====================
        // MERGE / UNMERGE CELLS
        // =====================
        let selectedCells = [];

        function clearSelection() {
        selectedCells.forEach(td => td.classList.remove('ck-cell-selected'));
        selectedCells = [];
        }

        table.addEventListener('click', function(e){
        if (!isAdmin) return;const td = e.target.closest('td');if (!td || td.closest('thead')) return;
        if (e.ctrlKey || e.metaKey) 
        { td.classList.toggle('ck-cell-selected');
        if (selectedCells.includes(td)) {selectedCells = selectedCells.filter(c => c !== td);} else {selectedCells.push(td);}
        } else {
        clearSelection();
        td.classList.add('ck-cell-selected');
        selectedCells = [td];
        }
        });

    // MERGE
    sectionEl.querySelector('.ck-merge')?.addEventListener('click', function(){
    if (selectedCells.length < 2) {
        alert('Select at least 2 cells (Ctrl + Click)');
        return;
    }

    const rows = selectedCells.map(td => td.parentElement.rowIndex);
    const cols = selectedCells.map(td => td.cellIndex);

    const minRow = Math.min(...rows);
    const maxRow = Math.max(...rows);
    const minCol = Math.min(...cols);
    const maxCol = Math.max(...cols);

    const master = table.rows[minRow].cells[minCol];
    master.rowSpan = maxRow - minRow + 1;
    master.colSpan = maxCol - minCol + 1;

    selectedCells.forEach(td => {
        if (td !== master) {
            td.dataset.hiddenByMerge = '1';
            td.style.display = 'none';
        }
    });

    clearSelection();
});

// UNMERGE
sectionEl.querySelector('.ck-unmerge')?.addEventListener('click', function(){
    selectedCells.forEach(td => {
        td.rowSpan = 1;
        td.colSpan = 1;
    });

    table.querySelectorAll('td[data-hidden-by-merge]').forEach(td => {
        td.style.display = '';
        delete td.dataset.hiddenByMerge;
    });

    clearSelection();
});

        function addRow(){
            const tbody = table.querySelector('tbody');
            const empty = tbody.querySelector('.ck-empty');
            if (empty) tbody.innerHTML = '';

            const cols = table.querySelectorAll('thead th').length - (isAdmin && table.querySelector('thead th:last-child').innerText.trim() === '' ? 1 : 0);
            const tr = document.createElement('tr');
            for (let i=0;i<cols;i++){
                const td = document.createElement('td');
                if (isAdmin) td.contentEditable = true;
                td.innerText = '';
                tr.appendChild(td);
            }
            if (isAdmin){
                const td = document.createElement('td');
                td.className = 'align-middle';
                td.innerHTML = '<button class="btn btn-sm btn-danger ck-remove-row">Delete</button>';
                tr.appendChild(td);
            }
            table.querySelector('tbody').appendChild(tr);
            attachRowButtons();
        }

        function insertRowBefore(targetTr){
            const tbody = table.querySelector('tbody');
            const cols = table.querySelectorAll('thead th').length - (isAdmin && table.querySelector('thead th:last-child').innerText.trim() === '' ? 1 : 0);
            const tr = document.createElement('tr');
            for (let i=0;i<cols;i++){
                const td = document.createElement('td');
                if (isAdmin) td.contentEditable = true;
                td.innerText = '';
                tr.appendChild(td);
            }
            if (isAdmin){
                const td = document.createElement('td');
                td.className = 'align-middle';
                td.innerHTML = '<button class="btn btn-sm btn-danger ck-remove-row">Delete</button>';
                tr.appendChild(td);
            }
            tbody.insertBefore(tr, targetTr);
            attachRowButtons();
        }

        function addCol(){
            const name = prompt('Nama kolom baru:'); if (!name) return;
            const thead = table.querySelector('thead tr');
            const th = document.createElement('th'); th.innerText = name; if (isAdmin){ th.className='ck-header'; th.title='Click to rename'; }
            const insertBefore = (isAdmin && thead.lastElementChild && thead.lastElementChild.innerText.trim() === '') ? thead.lastElementChild : null;
            thead.insertBefore(th, insertBefore);
            table.querySelectorAll('tbody tr').forEach(tr => {
                const td = document.createElement('td'); if (isAdmin) td.contentEditable = true; td.innerText = '';
                if (insertBefore) tr.insertBefore(td, tr.lastElementChild); else tr.appendChild(td);
            });
        }

        function insertColBefore(targetTh){
            const name = prompt('Nama kolom baru:'); if (!name) return;
            const thead = table.querySelector('thead tr');
            const th = document.createElement('th'); th.innerText = name; if (isAdmin){ th.className='ck-header'; th.title='Click to rename'; }
            thead.insertBefore(th, targetTh);
            const colIndex = Array.from(thead.querySelectorAll('th')).indexOf(th);
            table.querySelectorAll('tbody tr').forEach(tr => {
                const td = document.createElement('td'); if (isAdmin) td.contentEditable = true; td.innerText = '';
                tr.insertBefore(td, tr.children[colIndex + 1]);
            });
        }

        function collect(){
            let rawHeaders = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText.trim());
            if (rawHeaders.length>0 && rawHeaders[rawHeaders.length-1] === '') rawHeaders.pop();
            const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr => Array.from(tr.querySelectorAll('td')).slice(0, rawHeaders.length).map(td => td.innerText.trim()));
            return { headers: rawHeaders, rows };
        }

        sectionEl.querySelector('.ck-add-row')?.addEventListener('click', function(e){ e.preventDefault(); addRow(); });
        sectionEl.querySelector('.ck-add-col')?.addEventListener('click', function(e){ e.preventDefault(); addCol(); });
        sectionEl.querySelector('.ck-reset')?.addEventListener('click', function(e){ e.preventDefault(); if (confirm('Reset to last saved?')) location.reload(); });

        sectionEl.querySelector('.ck-save')?.addEventListener('click', function(e){
            e.preventDefault();
            const payload = collect();
            if (!payload.headers || payload.headers.length === 0) { alert('Please add at least one column.'); return; }

            payload.key = sectionEl.dataset.key;

            const btn = this;
            btn.disabled = true;
            const oldHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';

            fetch('{{ route('cascading.kinerja.save') }}', {
                method: 'POST',
                headers: {
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            }).then(r => r.json()).then(js => {
                if (js.status === 'ok') { alert('Saved'); location.reload(); } else { alert('Save failed'); btn.disabled = false; btn.innerHTML = oldHtml; }
            }).catch(()=>{ alert('Save failed'); btn.disabled = false; btn.innerHTML = oldHtml; });
        });

        attachRowButtons();

        // --- Inline editor with Pegawai autocomplete & auto-numbering ---
        function attachCellEditors(){
            sectionEl.querySelectorAll('tbody tr').forEach(tr => {
                const cells = Array.from(tr.querySelectorAll('td'));
                // Exclude last cell if it is the action column (delete button)
                const headerCount = sectionEl.querySelectorAll('thead th').length - (isAdmin ? 1 : 0);
                cells.forEach((td, idx) => {
                    if (idx >= headerCount) return; // skip action cell
                    if (!isAdmin) return;
                    if (td.dataset.editorBound) return;
                    td.dataset.editorBound = '1';
                    td.addEventListener('dblclick', function(e){
                        openInlineEditor(td);
                    });
                });
            });
        }

        function openInlineEditor(td){
            // prevent multiple editors
            if (td.querySelector('input')) return;
            const orig = td.innerText.trim();
            td.innerText = '';
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control form-control-sm';
            input.value = orig;
            td.appendChild(input);
            input.focus();
            input.select();

            // suggestion box
            let box = document.createElement('div');
            box.className = 'ck-suggest-list card shadow-sm';
            box.style.position = 'absolute';
            box.style.zIndex = 9999;
            box.style.minWidth = '220px';
            box.style.display = 'none';
            document.body.appendChild(box);

            function positionBox(){
                const rect = input.getBoundingClientRect();
                box.style.left = (rect.left + window.scrollX) + 'px';
                box.style.top = (rect.bottom + window.scrollY + 6) + 'px';
            }
            positionBox();
            window.addEventListener('resize', positionBox);

            let suggestions = [];
            let selIndex = -1;
            let fetchTimer = null;

            input.addEventListener('input', function(){
                const q = this.value.trim();
                if (fetchTimer) clearTimeout(fetchTimer);
                if (q.length < 2) { box.style.display = 'none'; return; }
                fetchTimer = setTimeout(() => {
                    fetch('{{ route('pegawai.suggest') }}?q=' + encodeURIComponent(q)).then(r => r.json()).then(js => {
                        suggestions = js;
                        if (!Array.isArray(suggestions) || suggestions.length === 0) { box.style.display = 'none'; return; }
                        box.innerHTML = '';
                        suggestions.forEach((s, i) => {
                            const item = document.createElement('div');
                            item.className = 'list-group-item list-group-item-action';
                            item.style.cursor = 'pointer';
                            item.innerHTML = '<strong>' + s.nama.replace(new RegExp(q, 'ig'), (m)=> '<u>' + m + '</u>') + '</strong><br/><small class="text-muted">' + s.nip + '</small>';
                            item.addEventListener('click', function(){
                                input.value = s.nama; commit();
                            });
                            box.appendChild(item);
                        });
                        selIndex = -1;
                        box.style.display = 'block';
                        positionBox();
                    }).catch(()=>{ box.style.display = 'none'; });
                }, 180);
            });

            input.addEventListener('keydown', function(e){
                if (e.key === 'ArrowDown') { e.preventDefault(); selIndex = Math.min(selIndex + 1, box.children.length - 1); updateSel(); }
                else if (e.key === 'ArrowUp') { e.preventDefault(); selIndex = Math.max(selIndex - 1, 0); updateSel(); }
                else if (e.key === 'Enter') { e.preventDefault(); if (selIndex >= 0) { box.children[selIndex].click(); } else { commit(); } }
                else if (e.key === 'Escape') { e.preventDefault(); cancel(); }
            });

            function updateSel(){
                Array.from(box.children).forEach((c, i) => c.classList.toggle('active', i === selIndex));
            }
            
            function commit(){
                const val = input.value.trim();
                cleanup();
                td.innerText = val;

                // auto-number logic
                if (/^\d+$/.test(val)){
                    const num = parseInt(val, 10);
                    const tr = td.closest('tr');
                    const colIndex = Array.from(tr.querySelectorAll('td')).indexOf(td);
                    const headerCount = sectionEl.querySelectorAll('thead th').length - (isAdmin ? 1 : 0);

                    function focusNext(nextTd, nextVal){
                        // put incremented value and open editor on next cell
                        if (!nextTd) return;
                        if (nextTd.innerText.trim() === '') nextTd.innerText = String(nextVal);
                        // open editor on next cell to allow pressing Enter again
                        setTimeout(() => openInlineEditor(nextTd), 50);
                    }

                    // try next rows until we find or create one
                    let nextTr = tr.nextElementSibling;
                    if (!nextTr){
                        // add row
                        sectionEl.querySelector('.ck-add-row')?.click();
                        nextTr = table.querySelector('tbody tr:last-child');
                    }
                    if (nextTr){
                        const nextTds = nextTr.querySelectorAll('td');
                        if (colIndex < headerCount){
                            const nextTd = nextTds[colIndex];
                            focusNext(nextTd, num + 1);
                        }
                    }
                }
            }

            function cancel(){ cleanup(); td.innerText = orig; }

            function cleanup(){
                if (box && box.parentNode) box.parentNode.removeChild(box);
                window.removeEventListener('resize', positionBox);
                const inp = td.querySelector('input'); if (inp) inp.remove();
            }

            input.addEventListener('blur', function(){ setTimeout(() => { if (document.activeElement && document.activeElement.closest && document.activeElement.closest('.ck-suggest-list')) return; commit(); }, 160); });
        }

        // attach editors initially and after row/col changes
        attachCellEditors();
        const addRowBtn = sectionEl.querySelector('.ck-add-row');
        const addColBtn = sectionEl.querySelector('.ck-add-col');
        addRowBtn?.addEventListener('click', function(){ setTimeout(() => attachCellEditors(), 120); });
        addColBtn?.addEventListener('click', function(){ setTimeout(() => attachCellEditors(), 120); });
    }

    // initialize all sections
    const sectionList = document.querySelectorAll('.ck-main-section');
    sectionList.forEach(s => initSection(s));

    // If no section is visible, show the first one as a sensible default
    const anyVisible = Array.from(sectionList).some(s => s.style.display !== 'none');
    if (!anyVisible && sectionList.length > 0) {
        sectionList[0].style.display = 'block';
    }

    // auto open if hash matches a key
    if (location.hash) {
        const h = location.hash.replace('#','');
        const target = document.querySelector('.ck-main-section[data-key="' + h + '"]');
        if (target){ hideAllSections(); target.style.display = 'block'; }
    }

let activeCell = null;

document.querySelectorAll('.mention-cell').forEach(cell => {
    cell.addEventListener('keyup', function(){
        activeCell = this;

        let text = this.innerText;
        let last = text.split(',').pop().trim();

        if(last.length < 1){
            hideMention();
            return;
        }

        fetch(`/api/pegawai-search?q=${last}`)
        .then(r=>r.json())
        .then(list=>{
            if(!list.length){ hideMention(); return; }

            let box = document.getElementById('mentionBox');
            box.innerHTML = '';

            list.forEach(p=>{
                box.innerHTML += `
                    <div class="mention-item" data-nama="${p.nama}">
                        ${p.nama} <small class="text-muted">(${p.nip})</small>
                    </div>`;
            });

            let r = activeCell.getBoundingClientRect();
            box.style.left = r.left + 'px';
            box.style.top  = (r.bottom + 5) + 'px';
            box.classList.remove('d-none');
        });
    });
});

document.addEventListener('click', function(e){
    if(e.target.classList.contains('mention-item')){
        let nama = e.target.dataset.nama;

        let parts = activeCell.innerText.split(',');
        parts.pop();
        parts.push(' ' + nama);

        activeCell.innerText = parts.join(',').replace(/^,/, '') + ', ';
        hideMention();
    } else {
        hideMention();
    }
});

function hideMention(){
    document.getElementById('mentionBox').classList.add('d-none');
}

})();

document.querySelectorAll('.sidebar-menu li > a').forEach(link => {
    link.addEventListener('click', function(e){
        e.preventDefault();

        let li = this.parentElement;

        // toggle dropdown
        if(li.querySelector('ul')){
            li.classList.toggle('open');
        }

        // buka cascading
        let key = this.dataset.section;
        if(key){
            document.querySelectorAll('.ck-main-section').forEach(s=>s.style.display='none');
            document.getElementById('ck-main-'+key).style.display='block';

            document.querySelectorAll('.sidebar-menu a').forEach(a=>a.classList.remove('active'));
            this.classList.add('active');
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {

    const table = document.querySelector('.ck-table');
    const body  = document.getElementById('rekap-tim-body');
    const badge = document.getElementById('rekap-count');

    if (!table || !body) return;

    const headers = Array.from(table.querySelectorAll('thead th'));
    const timIndex = headers.findIndex(th =>
        th.dataset.col === 'tim-kerja'
    );

    if (timIndex === -1) {
        body.innerHTML = '<div class="text-danger small">Kolom Tim Kerja tidak ditemukan</div>';
        badge.innerText = 0;
        return;
    }

    const counter = {};

    table.querySelectorAll('tbody tr').forEach(tr => {
        const td = tr.children[timIndex];
        if (!td) return;

        td.innerText
            .split(',')
            .map(n => n.trim())
            .filter(Boolean)
            .forEach(nama => {
                counter[nama] = (counter[nama] || 0) + 1;
            });
    });

    body.innerHTML = '';
    const entries = Object.entries(counter);

    if (entries.length === 0) {
        body.innerHTML = '<div class="text-muted small text-center">Belum ada data tim kerja</div>';
        badge.innerText = 0;
        return;
    }

    badge.innerText = entries.length;

    entries.forEach(([nama, jumlah]) => {
        const row = document.createElement('div');
        row.className = 'd-flex justify-content-between align-items-center mb-1';
        row.innerHTML = `
            <span class="small fw-semibold">${nama}</span>
            <span class="badge bg-primary">${jumlah}</span>
        `;
        body.appendChild(row);
    });

});

</script>
@endpush


