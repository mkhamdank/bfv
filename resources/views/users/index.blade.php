@extends('layouts.master')

@section('title', 'Users')

@section('styles')
<style>
    /* ── PAGE HEADER ── */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px;
        margin: 24px 24px 24px;
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
        position: absolute; right: -50px; top: -50px;
        width: 220px; height: 220px; border-radius: 50%;
        background: rgba(255,255,255,.05);
        pointer-events: none;
    }
    .page-header-modern::after {
        content: '';
        position: absolute; left: -30px; bottom: -70px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(255,255,255,.04);
        pointer-events: none;
    }
    .header-left .badge-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0;
        font-size: 11px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 5px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .header-left h1 {
        color: #fff; font-size: 26px; font-weight: 700; margin: 0 0 4px; line-height: 1.2;
    }
    .header-left p {
        color: rgba(255,255,255,.55); font-size: 13px; margin: 0;
    }
    .btn-add-user {
        background: #fff; color: #4a4690;
        border: none; border-radius: 12px;
        padding: 12px 24px; font-size: 13px; font-weight: 700;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 18px rgba(0,0,0,.18);
        text-decoration: none;
        transition: all .2s;
        position: relative; z-index: 10;
    }
    .btn-add-user:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,.22);
        color: #605ca8;
        text-decoration: none;
    }

    /* ── STAT CARDS ── */
    .dashboard-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin: 0 24px 24px;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 2px 12px rgba(96,92,168,.08);
        border: 1px solid rgba(96,92,168,.08);
        display: flex; align-items: center; gap: 16px;
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(96,92,168,.14);
    }
    .stat-icon {
        width: 50px; height: 50px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .stat-icon.purple { background: #ede9fb; color: #605ca8; }
    .stat-icon.green  { background: #dcfce7; color: #16a34a; }
    .stat-icon.amber  { background: #fef3c7; color: #d97706; }
    .stat-label { font-size: 11px; font-weight: 700; color: #a0aec0; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 2px; }
    .stat-value { font-size: 22px; font-weight: 800; color: #1e1b3a; line-height: 1.2; }
    .stat-sub   { font-size: 11px; color: #b0aece; margin-top: 2px; }
    @media(max-width: 768px) { .dashboard-row { grid-template-columns: 1fr; } }

    /* ── TABLE CARD ── */
    .table-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 2px 16px rgba(96,92,168,.08);
        border: 1px solid rgba(96,92,168,.07);
        overflow: hidden;
        margin: 0 24px 32px;
    }
    .table-card-header {
        padding: 20px 26px 16px;
        border-bottom: 1px solid #f0eef9;
        background: #faf9ff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-card-title {
        font-size: 14px; font-weight: 700; color: #1e1b3a;
        display: flex; align-items: center; gap: 10px;
    }
    .table-card-title .dot {
        width: 9px; height: 9px;
        background: linear-gradient(135deg, #605ca8, #8b87d4);
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(96,92,168,.15);
    }

    /* ── DATATABLE OVERRIDES ── */
    .dataTables_wrapper { padding: 18px 24px 22px !important; }
    .dataTables_length label,
    .dataTables_filter label {
        font-size: 13px !important; color: #4a5568 !important;
        font-weight: 500 !important;
        display: flex !important; align-items: center !important; gap: 8px !important;
    }
    .dataTables_length select,
    .dataTables_filter input {
        border: 1.5px solid #e5e2f5 !important;
        border-radius: 9px !important;
        padding: 7px 12px !important;
        font-size: 13px !important;
        outline: none !important;
        transition: border-color .2s !important;
    }
    .dataTables_filter input:focus {
        border-color: #605ca8 !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    table.dataTable { border-collapse: collapse !important; width: 100% !important; margin: 8px 0 !important; }
    table.dataTable thead th {
        background: #f7f5ff !important;
        color: #8b87b5 !important;
        font-size: 11px !important; font-weight: 700 !important;
        letter-spacing: .8px !important; text-transform: uppercase !important;
        padding: 13px 16px !important;
        border-bottom: 2px solid #ede9fb !important;
        border-top: none !important;
        white-space: nowrap;
    }
    table.dataTable tbody tr { transition: background .15s; }
    table.dataTable tbody tr:hover td { background: #faf8ff !important; }
    table.dataTable tbody td {
        padding: 14px 16px !important;
        font-size: 13px !important;
        color: #2d2b4e !important;
        border-bottom: 1px solid #f3f1fc !important;
        border-top: none !important;
        vertical-align: middle !important;
    }
    table.dataTable tbody tr:last-child td { border-bottom: none !important; }
    .dt-top-bar    { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px 14px; gap: 12px; }
    .dt-bottom-bar { display: flex; align-items: center; justify-content: space-between; padding: 10px 24px 6px; flex-wrap: wrap; gap: 8px; }
    .dataTables_info { font-size: 12px !important; color: #9b97c5 !important; padding-top: 0 !important; }
    .dataTables_paginate .paginate_button {
        border-radius: 8px !important; font-size: 13px !important;
        font-weight: 600 !important; color: #605ca8 !important;
        border: 1px solid transparent !important;
        padding: 6px 11px !important; margin: 0 2px !important;
        transition: all .15s !important;
    }
    .dataTables_paginate .paginate_button:hover {
        background: #ede9fb !important; color: #4a4690 !important; border-color: transparent !important;
    }
    .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #605ca8, #4a4690) !important;
        color: #fff !important; border-color: transparent !important;
        box-shadow: 0 3px 10px rgba(96,92,168,.35) !important;
    }

    /* ── AVATAR ── */
    .user-avatar {
        width: 30px; height: 30px; border-radius: 8px;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; color: #fff;
        margin-right: 10px; flex-shrink: 0; vertical-align: middle;
    }
    .user-name-cell { display: inline-flex; align-items: center; }
    .user-username  { font-weight: 600; color: #1e1b3a; }

    /* ── PERMISSION BADGE ── */
    .badge-perm {
        display: inline-flex; align-items: center;
        background: #ede9fb; color: #605ca8;
        border: 1px solid rgba(96,92,168,.18);
        border-radius: 6px; font-size: 10.5px; font-weight: 700;
        padding: 3px 9px; margin: 2px;
        letter-spacing: .04em; text-transform: uppercase;
    }

    /* ── ACTION BUTTONS ── */
    .btn-tbl {
        border-radius: 9px !important; font-size: 12px !important;
        padding: 6px 13px !important; font-weight: 600 !important;
        border: none !important;
        display: inline-flex !important; align-items: center !important; gap: 5px !important;
        margin: 2px !important; cursor: pointer; transition: all .18s !important;
        text-decoration: none !important; white-space: nowrap;
    }
    .btn-tbl.edit   { background: #ede9fb; color: #605ca8; }
    .btn-tbl.edit:hover   { background: #ddd8f8; transform: translateY(-1px); }
    .btn-tbl.delete { background: #fef2f2; color: #c0392b; }
    .btn-tbl.delete:hover { background: #fde8e8; transform: translateY(-1px); }

    /* ── MODAL ── */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(20,18,46,.55);
        backdrop-filter: blur(3px);
        z-index: 9999; align-items: center; justify-content: center;
    }
    .modal-overlay.show { display: flex !important; }
    .modal-box {
        background: #fff; border-radius: 20px; width: 420px; max-width: 95vw;
        box-shadow: 0 24px 64px rgba(30,27,74,.2);
        position: relative; overflow: hidden;
        display: flex; flex-direction: column;
        animation: modalIn .25s ease;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(.95) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-header-del {
        background: linear-gradient(135deg, #7f1d1d, #c0392b);
        padding: 20px 26px; flex-shrink: 0;
        display: flex; align-items: center; gap: 10px;
    }
    .modal-header-del .modal-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: rgba(255,255,255,.15);
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; color: #fff;
    }
    .modal-header-del h3 { color: #fff; font-size: 15px; font-weight: 700; margin: 0; }
    .btn-modal-close {
        position: absolute; top: 14px; right: 16px;
        background: rgba(255,255,255,.18); border: 1.5px solid rgba(255,255,255,.3);
        color: #fff; width: 30px; height: 30px; border-radius: 50%;
        font-size: 18px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
    }
    .modal-body-pad { padding: 24px 26px 10px; }
    .modal-body-pad p { font-size: 14px; color: #2d2b4e; margin-bottom: 8px; }
    .modal-body-pad .warn { font-size: 12px; color: #9b97c5; }
    .modal-footer-pad { padding: 16px 26px; display: flex; gap: 10px; justify-content: flex-end; }
    .btn-modal-cancel {
        background: #f0eef9; color: #7874b0; border: none;
        border-radius: 10px; padding: 10px 20px;
        font-size: 13px; font-weight: 600; cursor: pointer;
        transition: background .15s;
    }
    .btn-modal-cancel:hover { background: #e5e2f5; }
    .btn-modal-del {
        background: linear-gradient(135deg, #7f1d1d, #c0392b);
        color: #fff; border: none; border-radius: 10px;
        padding: 10px 24px; font-size: 13px; font-weight: 700;
        cursor: pointer; display: flex; align-items: center; gap: 7px;
        box-shadow: 0 4px 14px rgba(192,57,43,.35);
        transition: all .2s;
    }
    .btn-modal-del:hover   { box-shadow: 0 6px 20px rgba(192,57,43,.45); transform: translateY(-1px); }
    .btn-modal-del:disabled { opacity: .6; cursor: not-allowed; transform: none; }
</style>
@stop

@section('content')
<div style="padding-bottom: 10px;">

    <!-- PAGE HEADER -->
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag">
                <i class="fas fa-users-cog"></i> Admin Panel
            </div>
            <h1>Users Controller</h1>
            <p>Kelola akun pengguna dan hak akses sistem</p>
        </div>
        <a href="{{ route('admin.user.create') }}" class="btn-add-user">
            <i class="fas fa-user-plus"></i> Tambah User
        </a>
    </div>

    <!-- STAT CARDS -->
    <div class="dashboard-row">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value" id="statTotal">—</div>
                <div class="stat-sub">terdaftar di sistem</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-shield-alt"></i></div>
            <div>
                <div class="stat-label">Dengan Permission</div>
                <div class="stat-value" id="statWithPerm">—</div>
                <div class="stat-sub">memiliki hak akses</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="fas fa-user-clock"></i></div>
            <div>
                <div class="stat-label">Tanpa Permission</div>
                <div class="stat-value" id="statNoPerm">—</div>
                <div class="stat-sub">belum dikonfigurasi</div>
            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <div class="dot"></div>
                Daftar Pengguna
            </div>
        </div>
        <table id="usersTable" class="table" style="width:100%">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Permission</th>
                    <th width="14%" style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

</div>

<!-- MODAL HAPUS -->
<div class="modal-overlay" id="modalDelete">
    <div class="modal-box">
        <button class="btn-modal-close" onclick="closeDeleteModal()">&times;</button>
        <div class="modal-header-del">
            <div class="modal-icon"><i class="fas fa-trash-alt"></i></div>
            <h3>Hapus User</h3>
        </div>
        <div class="modal-body-pad">
            <p>Yakin ingin menghapus user <b id="deleteUsername" style="color:#605ca8;"></b>?</p>
            <span class="warn"><i class="fas fa-exclamation-circle"></i> Data yang dihapus tidak dapat dikembalikan.</span>
        </div>
        <div class="modal-footer-pad">
            <button class="btn-modal-cancel" onclick="closeDeleteModal()">Batal</button>
            <button class="btn-modal-del" id="btnDoDelete" onclick="doDelete()">
                <i class="fas fa-trash-alt"></i> Hapus
            </button>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
function getInitials(name) {
    if (!name) return '?';
    return name.trim().split(/\s+/).map(function(w){ return w[0]; }).join('').substring(0, 2).toUpperCase();
}

$(document).ready(function () {
    $('#side_user_management').addClass('menu-open');
    $('body').addClass('sidebar-collapse');
    

    var dtUsers = $('#usersTable').DataTable({
        processing: true,
        serverSide: false,
        lengthChange: false,
        sorting: false,
        searching: true,
        ajax: {
            url: "{{ route('admin.user.getUsers') }}",
            dataSrc: function(json) {
                var data = json.users || [];
                $('#statTotal').text(data.length);
                $('#statWithPerm').text(data.filter(function(u){ return u.permissions && u.permissions.length > 0; }).length);
                $('#statNoPerm').text(data.filter(function(u){ return !u.permissions || u.permissions.length === 0; }).length);
                return data;
            }
        },
        language: {
            processing:  '<i class="fas fa-spinner fa-spin" style="color:#605ca8;font-size:16px;"></i>',
            search:      '',
            searchPlaceholder: 'Cari user...',
            lengthMenu:  'Tampilkan _MENU_ data',
            info:        'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty:   'Tidak ada data',
            zeroRecords: 'Tidak ada data ditemukan',
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next:     '<i class="fas fa-chevron-right"></i>'
            }
        },
        columns: [
            {
                data: null, orderable: false, searchable: false,
                render: function(d, t, r, m) {
                    return '<span style="color:#9b97c5;font-weight:600;">' + (m.row + m.settings._iDisplayStart + 1) + '</span>';
                }
            },
            {
                data: 'username',
                render: function(d, t, row) {
                    var initials = getInitials(row.name || d);
                    return '<div class="user-name-cell">'
                         + '<div class="user-avatar">' + initials + '</div>'
                         + '<span class="user-username">' + (d || '—') + '</span>'
                         + '</div>';
                }
            },
            {
                data: 'name',
                render: function(d) {
                    return d ? d : '<span style="color:#c0bdd8;">—</span>';
                }
            },
            {
                data: 'email',
                render: function(d) {
                    return d ? '<span style="color:#6b68a0;">' + d + '</span>'
                             : '<span style="color:#c0bdd8;">—</span>';
                }
            },
            {
                data: 'permissions', orderable: false,
                render: function(d) {
                    if (!d || d.length === 0)
                        return '<span style="color:#c0bdd8;font-size:12px;font-style:italic;">Tidak ada</span>';
                    return d.map(function(p){
                        return '<span class="badge-perm">' + p.name + '</span>';
                    }).join('');
                }
            },
            {
                data: null, orderable: false, searchable: false, className: 'text-center',
                render: function(d, t, row) {
                    var editUrl = "{{ route('admin.user.edit', '') }}/" + row.username;
                    return '<a href="' + editUrl + '" class="btn-tbl edit">'
                         + '<i class="fas fa-pen"></i> Edit</a>'
                         + '<button class="btn-tbl delete" onclick="openDeleteModal(' + row.id + ', \'' + row.username + '\')">'
                         + '<i class="fas fa-trash-alt"></i> Hapus</button>';
                }
            }
        ],
        dom: '<"dt-top-bar"lf>rt<"dt-bottom-bar"ip>',
        pageLength: 10,
        order: [[0, 'asc']]
    });

    // ── Modal Delete ──
    var deleteId = null;

    window.openDeleteModal = function(id, username) {
        deleteId = id;
        $('#deleteUsername').text(username);
        $('#btnDoDelete').prop('disabled', false).html('<i class="fas fa-trash-alt"></i> Hapus');
        document.getElementById('modalDelete').classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    window.closeDeleteModal = function() {
        document.getElementById('modalDelete').classList.remove('show');
        document.body.style.overflow = '';
    };

    document.getElementById('modalDelete').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    window.doDelete = function() {
        if (!deleteId) return;
        var btn = $('#btnDoDelete');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menghapus...');

        $.ajax({
            url: "{{ route('admin.user.delete', '') }}/" + deleteId,
            type: "DELETE",
            data: { _token: "{{ csrf_token() }}", id: deleteId },
            success: function(data) {
                closeDeleteModal();
                // ubah agar tidak menggunakan toastr, tapi menggunakan alert biasa
                if (data.success) {
                    alert('User berhasil dihapus.');
                    dtUsers.ajax.reload(null, false);
                } else {
                    alert(data.message || 'Gagal menghapus user.');
                }
            },
            error: function() {
                closeDeleteModal();
                alert('Terjadi kesalahan. Coba lagi.');
            }
        });
    };
});
</script>
@stop