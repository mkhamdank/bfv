@extends('layouts.master')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { background: #f0f2f7 !important; }

    body p, body span:not([class*="fa"]):not([class*="glyphicon"]),
    body div, body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1, body h2, body h3, body h4, body h5, body h6, body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ══════════════════════════════════════
       PAGE HEADER
    ══════════════════════════════════════ */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px;
        margin: 24px 0 24px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        position: relative;
        overflow: hidden;
    }
    .page-header-modern::before {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .page-header-modern::after {
        content: '';
        position: absolute;
        left: 30%; bottom: -60px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.03);
    }
    .header-left .badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 20px;
        margin-bottom: 10px;
    }
    .header-left h1 { color: #fff !important; font-size: 26px !important; font-weight: 700 !important; margin: 0 0 4px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }

    .btn-add-report {
        background: #fff;
        color: #4a4690;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 18px rgba(0,0,0,.18);
        text-decoration: none;
        transition: all .2s;
        z-index: 999;
    }
    .btn-add-report:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,.22);
        color: #605ca8;
        text-decoration: none;
    }

    /* ══════════════════════════════════════
       TABLE CARD
    ══════════════════════════════════════ */
    .table-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.05);
        overflow: hidden;
        margin-bottom: 32px;
    }
    .table-card-header {
        padding: 18px 24px 16px;
        border-bottom: 1px solid #f0f2f7;
        background: #fafbff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .table-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a202c;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .table-card-title .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; }

    /* ── DataTable ── */
    .dataTables_wrapper { padding: 16px 20px 20px !important; }
    .dataTables_length label,
    .dataTables_filter label { font-size: 13px !important; color: #4a5568 !important; font-weight: 500 !important; display: flex !important; align-items: center !important; gap: 8px !important; }
    .dataTables_length select,
    .dataTables_filter input { border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important; padding: 6px 10px !important; font-size: 13px !important; outline: none !important; }
    .dataTables_filter input:focus { border-color: #605ca8 !important; box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important; }

    table.dataTable { border-collapse: collapse !important; width: 100% !important; margin: 8px 0 !important; }
    table.dataTable thead th {
        background: #f7f8fc !important;
        color: #718096 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: .8px !important;
        text-transform: uppercase !important;
        padding: 12px 14px !important;
        border-bottom: 2px solid #edf0f5 !important;
        border-top: none !important;
        white-space: nowrap;
    }
    table.dataTable tbody tr:hover td { background: #f5f3ff !important; }
    table.dataTable tbody td {
        padding: 12px 14px !important;
        font-size: 13px !important;
        color: #2d3748 !important;
        border-bottom: 1px solid #f0f2f7 !important;
        border-top: none !important;
        vertical-align: middle !important;
    }
    table.dataTable tbody tr:last-child td { border-bottom: none !important; }

    .dataTables_info { font-size: 12px !important; color: #718096 !important; padding-top: 0 !important; }
    .dataTables_paginate .paginate_button { border-radius: 7px !important; font-size: 13px !important; font-weight: 600 !important; color: #4a5568 !important; border: 1px solid transparent !important; padding: 5px 10px !important; margin: 0 2px !important; }
    .dataTables_paginate .paginate_button:hover { background: #ede9fe !important; color: #605ca8 !important; }
    .dataTables_paginate .paginate_button.current { background: linear-gradient(135deg, #4a4690, #605ca8) !important; color: #fff !important; }

    /* ── Row number ── */
    .row-num { font-weight: 700; font-size: 12px; color: #a0aec0; }

    /* ── Permission name badge ── */
    .perm-name {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ede9fe;
        color: #5b21b6;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
    }

    /* ── Guard badge ── */
    .guard-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        background: #ebf2ff;
        color: #2d6bc4;
    }

    /* ── Remark badge ── */
    .remark-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .remark-mis    { background: #dcfce7; color: #15803d; }
    .remark-ympi   { background: #fef3c7; color: #b45309; }
    .remark-vendor { background: #fee2e2; color: #dc2626; }
    .remark-other  { background: #f1f5f9; color: #475569; }

    /* ── Action button ── */
    .btn-del {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #fee2e2;
        color: #dc2626;
        border: none;
        border-radius: 7px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .18s;
    }
    .btn-del:hover { background: #fecaca; color: #b91c1c; }

    /* ══════════════════════════════════════
       MODAL OVERLAY
    ══════════════════════════════════════ */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.show { display: flex !important; }

    .modal-box {
        background: #fff;
        border-radius: 18px;
        width: 460px;
        max-width: 95vw;
        box-shadow: 0 20px 60px rgba(0,0,0,.18);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .modal-hdr {
        background: linear-gradient(135deg, #2d2b4e, #605ca8);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-hdr h3 {
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-hdr .close-btn {
        background: rgba(255,255,255,.15);
        border: none;
        color: #fff;
        width: 30px; height: 30px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .18s;
    }
    .modal-hdr .close-btn:hover { background: rgba(255,255,255,.28); }

    .modal-bdy { padding: 24px; }

    .form-lbl {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #4a5568;
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 6px;
    }
    .form-inp {
        width: 100%;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: #2d3748;
        outline: none;
        transition: border-color .18s, box-shadow .18s;
        background: #fafbff;
        margin-bottom: 16px;
        box-sizing: border-box;
    }
    .form-inp:focus {
        border-color: #605ca8;
        box-shadow: 0 0 0 3px rgba(96,92,168,.12);
    }

    .modal-ftr {
        padding: 16px 24px;
        border-top: 1px solid #f0f2f7;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    .btn-cancel {
        background: #f0f2f7;
        color: #718096;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background .18s;
    }
    .btn-cancel:hover { background: #e2e8f0; }
    .btn-save {
        background: linear-gradient(135deg, #4a4690, #605ca8);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 7px;
        transition: opacity .18s;
    }
    .btn-save:hover { opacity: .88; }
</style>
@stop

@section('content')
<div class="container-fluid" style="padding: 0 24px;">

    {{-- PAGE HEADER --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag">
                <i class="fas fa-shield-alt"></i>&nbsp; Admin
            </div>
            <h1>Permission Controller</h1>
            <p>Kelola daftar hak akses dan permission pengguna sistem</p>
        </div>
        <button class="btn-add-report" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add Permission
        </button>
    </div>

    {{-- TABLE CARD --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span>
                Daftar Permission
            </div>
        </div>

        <div class="dataTables_wrapper">
            <table id="table-permission" class="table dataTable" width="100%">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Permission</th>
                        <th>Guard</th>
                        <th>Remark</th>
                        <th style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="body-table-permission">
                    @php $id = 1; @endphp
                    @foreach ($permissions as $permission)
                    <tr>
                        <td><span class="row-num">{{ $id++ }}</span></td>
                        <td><span class="perm-name"><i class="fas fa-key" style="font-size:10px;"></i> {{ $permission->name }}</span></td>
                        <td><span class="guard-badge">{{ $permission->guard_name }}</span></td>
                        <td>
                            @php
                                $rm = strtolower($permission->remark ?? '');
                                $rmClass = in_array($rm, ['mis','ympi','vendor']) ? 'remark-'.$rm : 'remark-other';
                            @endphp
                            <span class="remark-badge {{ $rmClass }}">{{ $permission->remark ?: '—' }}</span>
                        </td>
                        <td>
                            <button class="btn-del" onclick="deletePermission({{ $permission->id }})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ADD PERMISSION MODAL --}}
<div class="modal-overlay" id="modalAddPermission">
    <div class="modal-box">
        <div class="modal-hdr">
            <h3><i class="fas fa-plus-circle"></i> Add Permission</h3>
            <button class="close-btn" onclick="closeAddModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-bdy">
            <label class="form-lbl" for="permission">Permission Name <span style="color:#dc2626;">*</span></label>
            <input type="text" class="form-inp" id="permission" placeholder="e.g. view driver">

            <label class="form-lbl" for="remark">Remark</label>
            <select class="form-inp" id="remark" style="appearance:auto;">
                <option value="">— Pilih Remark —</option>
                <option value="mis">MIS</option>
                <option value="ympi">YMPI</option>
                <option value="vendor">Vendor</option>
            </select>
        </div>
        <div class="modal-ftr">
            <button class="btn-cancel" onclick="closeAddModal()">Batal</button>
            <button class="btn-save" onclick="addPermission()">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#side_user_management').addClass('menu-open');
        $('body').addClass('sidebar-collapse');
        $('#table-permission').DataTable({
            searching: true,
            ordering:  true,
            paging:    true,
            lengthChange: true,
            language: {
                search: '',
                searchPlaceholder: 'Cari permission...',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ permission',
                infoEmpty: 'Tidak ada data',
                paginate: { previous: '‹', next: '›' }
            }
        });
    });

    /* ── Modal ── */
    function openAddModal() {
        $('#permission').val('');
        $('#remark').val('');
        document.getElementById('modalAddPermission').classList.add('show');
        document.body.style.overflow = 'hidden';
        setTimeout(function(){ document.getElementById('permission').focus(); }, 100);
    }

    function closeAddModal() {
        document.getElementById('modalAddPermission').classList.remove('show');
        document.body.style.overflow = '';
    }

    document.getElementById('modalAddPermission').addEventListener('click', function(e) {
        if (e.target === this) closeAddModal();
    });

    /* ── Add Permission ── */
    function addPermission() {
        let permission = $('#permission').val().trim();
        let remark     = $('#remark').val();

        if (!permission) {
            $('#permission').focus();
            return;
        }

        $.post("{{ route('admin.permission.store') }}", {
            name:   permission,
            remark: remark,
            _token: "{{ csrf_token() }}"
        }, function(data) {
            if (data.status === 'success' || data.success) {
                closeAddModal();
                location.reload();
            } else {
                alert(data.message || 'Operasi selesai. Mohon refresh halaman jika data belum berubah.');
                location.reload();
            }
        });
    }

    /* ── Delete Permission ── */
    function deletePermission(id) {
        if (!confirm('Hapus permission ini?')) return;
        $.post("{{ route('admin.permission.destroy', ':id') }}".replace(':id', id), {
            _token:  "{{ csrf_token() }}",
            _method: 'DELETE'
        }, function(data) {
            if (data.status === 'success' || data.success) {
                location.reload();
            } else {
                alert(data.message || 'Operasi selesai. Mohon refresh halaman jika data belum berubah.');
                location.reload();
            }
        });
    }
</script>
@endsection