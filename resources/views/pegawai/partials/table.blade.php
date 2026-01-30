<style>
.modern-table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.modern-table thead {
    background: #157be1;
    border-bottom: 3px solid #130080;
}

.modern-table thead th {
    background: #c7cfd7;
    color: #070128;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.8px;
    padding: 18px 14px;
    border: none;
    text-align: center;
    vertical-align: middle;
}

.modern-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f0f0f0;
}

.modern-table tbody tr:hover {
    background: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.modern-table tbody tr:last-child {
    border-bottom: none;
}

.modern-table tbody td {
    padding: 16px 12px;
    vertical-align: middle;
    border: none;
}

.pegawai-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 15px;
}

.pegawai-nip {
    font-size: 12px;
    color: #7f8c8d;
    margin-top: 2px;
}

.badge-asn {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.badge-pns {
    background: #d4edda;
    color: #155724;
}

.badge-pppk {
    background: #d1ecf1;
    color: #0c5460;
}

.action-buttons {
    display: flex;
    gap: 6px;
    justify-content: center;
}

.btn-action {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.no-cell {
    font-weight: 600;
    color: #800000;
}
</style>

<div class="modern-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th style="width:50px;">No</th>
                <th>Nama Pegawai</th>
                <th>Pangkat/Golongan</th>
                <th>Jabatan</th>
                <th>Unit Kerja</th>
                <th style="width:100px;">Jenis ASN</th>
                <th style="width:140px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawai as $p)
            <tr>
                <td class="text-center no-cell">{{ $loop->iteration }}</td>
                <td>
                    <div class="pegawai-name">{{ $p->nama }}</div>
                    <div class="pegawai-nip">{{ $p->nip }}</div>
                </td>
                <td class="text-center">{{ $p->pangkat_golongan ?? '-' }}</td>
                <td class="text-center">{{ $p->jabatan_saat_ini ?? '-' }}</td>
                <td class="text-center">{{ $p->unit_kerja ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge-asn {{ $p->jenis_asn == 'PNS' ? 'badge-pns' : 'badge-pppk' }}">
                        {{ $p->jenis_asn ?? '-' }}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('pegawai.show', $p->id) }}" 
                           class="btn btn-info btn-action"
                           title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('pegawai.edit', $p->id) }}" 
                           class="btn btn-warning btn-action"
                           title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('pegawai.destroy', $p->id) }}"
                              method="POST" 
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-action"
                                    title="Hapus"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">
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
