@extends('layouts.app')
@section('content')
<style>
/* Cascading Kinerja modern table styles */
.ck-toolbar { display:flex; justify-content:space-between; gap:8px; align-items:center; margin-bottom:12px; }
.ck-toolbar .btn { min-width: 38px; }
#iku-table { border-collapse: separate; border-spacing: 0; overflow: hidden; border-radius: 8px; width:100%; }
#iku-table thead th { background:  #b11532ff; border-bottom: 1px solid rgba(255, 255, 255, 0.06); color:#ffffff; padding:16px 18px; font-size:15px; }
#iku-table tbody td { padding:12px 14px; vertical-align:middle; background:#fff; }
#iku-table tbody tr:hover td { background:#f8f9fb; }
#iku-table td[contenteditable] { outline:none; cursor:text; }
#iku-table td:focus { box-shadow: inset 0 0 0 2px rgba(0,123,255,0.08); }
.ck-empty { color:#6c757d; padding:28px; background:#fff; border-radius:6px; text-align:center; }
.ck-toolbar .btn i { font-size:14px; }
.ck-table-card { background: #f5ccbcff; border-radius:10px; padding: 8px; box-shadow: 0 6px 20px rgba(16,24,40,0.06); border: 1px solid rgba(16,24,40,0.04); }
.ck-table thead th { background:  #b11532ff; border-bottom: 1px solid rgba(255, 255, 255, 0.06); color:#ffffff; padding:16px 18px; font-size:15px; }
.ck-table tbody td { padding:14px 18px; color:#1f2937; }
.ck-table tbody tr:nth-child(odd) td { background: linear-gradient(90deg,#ffffff,#fbfbff); }
.ck-table tbody tr:hover td { background: linear-gradient(90deg,#f8fbff,#ffffff); transform: translateY(-1px); transition: all 120ms ease; }
.btn-ck-add { background: linear-gradient(90deg,#2563eb,#3b82f6); color:#fff; border:none; box-shadow: 0 3px 8px rgba(59,130,246,0.18); }
.btn-ck-add:hover { filter: brightness(0.95); transform: translateY(-2px); }
.btn-ck-col { background: linear-gradient(90deg,#7c3aed,#6d28d9); color:#fff; border:none; box-shadow: 0 3px 8px rgba(124,58,237,0.14); }
.btn-ck-save { background: linear-gradient(90deg,#059669,#10b981); color:#fff; border:none; box-shadow: 0 3px 8px rgba(16,185,129,0.12); }
.btn-ck-reset { background: linear-gradient(90deg,#475569,#64748b); color:#fff; border:none; }
.btn-ck-add i, .btn-ck-col i, .btn-ck-save i, .btn-ck-reset i { font-size:16px; }
.ck-toolbar .text-muted.small { color:#374151; font-weight:600; }
.ck-table tbody td:first-child { border-left: 4px solid rgba(122,12,12,0.06); padding-left: 12px; }
.ck-table thead th { letter-spacing: 0.2px; }
.ck-remove-row { min-width: 72px; }
.ck-empty:before { content: "\1F4C4"; display:block; font-size:28px; margin-bottom:8px; }
.ck-cell-selected { background: rgba(59,130,246,0.18) !important; outline: 2px solid rgba(59,130,246,0.6); }
@media (max-width: 768px) { .sidebar-left { display:none; } .content-wrapper { margin-left:0; padding:20px; } }
</style>
<div class="container">
    <h2 class="fw-bold mb-3">IKU SDMO</h2>
    @php $isAdmin = auth()->check() && auth()->user()->isAdmin(); @endphp
    <div id="iku-table-container">
        <div class="ck-toolbar">
            <div class="text-muted small">IKU SDMO Table</div>
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
            <table class="ck-table table table-sm table-border shadow-sm mb-0" id="iku-table">
                <thead class="bg-white">
                <tr>
                    <th @if($isAdmin) class="ck-header" title="Click to rename" @endif>Sasaran</th>
                    <th @if($isAdmin) class="ck-header" title="Click to rename" @endif>Indikator</th>
                    <th @if($isAdmin) class="ck-header" title="Click to rename" @endif>Target</th>
                    @if($isAdmin)<th style="width:60px">&nbsp;</th>@endif
                </tr>
                </thead>
                <tbody>
                    @if(count($rows ?? []) === 0)
                        <tr><td colspan="{{ 3 + ($isAdmin ? 1:0) }}"><div class="ck-empty">No data yet. Admin can add rows or columns to begin.</div></td></tr>
                    @else
                        @foreach($rows as $row)
                            <tr>
                                @foreach($row as $cell)
                                    @if(is_array($cell) || is_object($cell))
                                        @php
                                            $text = data_get($cell, 'text', '');
                                            $rowspan = data_get($cell, 'rowspan', 1);
                                            $colspan = data_get($cell, 'colspan', 1);
                                            $hidden = data_get($cell, 'hidden', false);
                                        @endphp
                                        <td @if($isAdmin) contenteditable="true" class="mention-cell ck-cell" @endif @if($rowspan>1) rowspan="{{ $rowspan }}" @endif @if($colspan>1) colspan="{{ $colspan }}" @endif @if($hidden) style="display:none" @endif>{{ $text }}</td>
                                    @else
                                        <td @if($isAdmin) contenteditable="true" class="mention-cell ck-cell" @endif>{{ $cell }}</td>
                                    @endif
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
@endsection
@push('scripts')
<script>
(function(){
    const sectionEl = document.getElementById('iku-table-container');
    if (!sectionEl) return;
    const table = sectionEl.querySelector('.ck-table');
    const isAdmin = !!sectionEl.querySelector('.ck-add-row');

    // --- JS logic from cascading-kinerja, adapted for single table ---
    function attachRowButtons(){
        sectionEl.querySelectorAll('.ck-remove-row').forEach(btn => {
            btn.onclick = function(){ if (!confirm('Delete this row?')) return; this.closest('tr').remove(); }
        });
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

    function saveTable(){
        const data = [];

        table.querySelectorAll('tbody tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('td').forEach((td, idx) => {
                if (isAdmin && idx === tr.children.length - 1) return; // skip delete column
                row.push({
                    text: td.innerText.trim(),
                    rowspan: td.rowSpan || 1,
                    colspan: td.colSpan || 1,
                    hidden: td.style.display === 'none'
                });
            });
            data.push(row);
        });

        fetch('/iku-sdmo/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ table: data })
        }).then(r => r.json())
          .then(js => {
              alert('IKU SDMO berhasil disimpan');
          }).catch(err => {
              alert('Gagal menyimpan');
          });
    }
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
            const val = this.value;
            const match = val.match(/@([\w\d]*)$/);
            if (fetchTimer) clearTimeout(fetchTimer);
            if (match && match[1].length >= 2) {
                fetchTimer = setTimeout(() => {
                    fetch('/pegawai/suggest?q=' + encodeURIComponent(match[1])).then(r => r.json()).then(js => {
                        suggestions = js;
                        if (!Array.isArray(suggestions) || suggestions.length === 0) { box.style.display = 'none'; return; }
                        box.innerHTML = '';
                        suggestions.forEach((s, i) => {
                            const item = document.createElement('div');
                            item.className = 'list-group-item list-group-item-action';
                            item.style.cursor = 'pointer';
                            item.innerHTML = '<strong>' + s.nama.replace(new RegExp(match[1], 'ig'), (m)=> '<u>' + m + '</u>') + '</strong><br/><small class="text-muted">' + s.nip + '</small>';
                            item.addEventListener('click', function(){
                                // Replace @mention with selected name
                                input.value = val.replace(/@([\w\d]*)$/, s.nama);
                                commit();
                            });
                            box.appendChild(item);
                        });
                        selIndex = -1;
                        box.style.display = 'block';
                        positionBox();
                    }).catch(()=>{ box.style.display = 'none'; });
                }, 180);
            } else {
                box.style.display = 'none';
            }
        });
        input.addEventListener('keydown', function(e){
            if (box.style.display === 'block') {
                if (e.key === 'ArrowDown') { e.preventDefault(); selIndex = Math.min(selIndex + 1, box.children.length - 1); updateSel(); }
                else if (e.key === 'ArrowUp') { e.preventDefault(); selIndex = Math.max(selIndex - 1, 0); updateSel(); }
                else if (e.key === 'Enter') { e.preventDefault(); if (selIndex >= 0) { box.children[selIndex].click(); } else { commit(); } }
                else if (e.key === 'Escape') { e.preventDefault(); cancel(); }
            }
        });
        function updateSel(){
            Array.from(box.children).forEach((c, i) => c.classList.toggle('active', i === selIndex));
        }
        function commit(){
            const val = input.value.trim();
            cleanup();
            td.innerText = val;
        }
        function cancel(){ cleanup(); td.innerText = orig; }
        function cleanup(){
            if (box && box.parentNode) box.parentNode.removeChild(box);
            window.removeEventListener('resize', positionBox);
            const inp = td.querySelector('input'); if (inp) inp.remove();
        }
        input.addEventListener('blur', function(){ setTimeout(() => { if (document.activeElement && document.activeElement.closest && document.activeElement.closest('.ck-suggest-list')) return; commit(); }, 160); });
    }
    // attach editors and row buttons initially and after row/col changes
    attachCellEditors();
    attachRowButtons();
    // === BIND TOOLBAR BUTTONS ===
    const addRowBtn = sectionEl.querySelector('.ck-add-row');
    const addColBtn = sectionEl.querySelector('.ck-add-col');
    const saveBtn   = sectionEl.querySelector('.ck-save');
    const resetBtn  = sectionEl.querySelector('.ck-reset');

    addRowBtn?.addEventListener('click', function(){
        addRow();
        setTimeout(() => { attachCellEditors(); attachRowButtons(); }, 50);
    });

    addColBtn?.addEventListener('click', function(){
        addCol();
        setTimeout(() => { attachCellEditors(); attachRowButtons(); }, 50);
    });

    resetBtn?.addEventListener('click', function(){
        if (!confirm('Reset table to last saved state?')) return;
        location.reload();
    });

    saveBtn?.addEventListener('click', function(){
        saveTable();
    });
})();
</script>
@endpush
