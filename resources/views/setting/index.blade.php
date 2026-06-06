@extends('layouts.master')

@section('title', 'Setting Whatsapp')

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

    /* ── Page header ── */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px; margin: 24px 0 24px;
        border-radius: 18px; display: flex; align-items: center;
        gap: 16px; position: relative; overflow: hidden;
    }
    .page-header-modern::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.04); pointer-events: none;
    }
    .ph-icon {
        width: 52px; height: 52px; border-radius: 14px;
        background: rgba(255,255,255,.15);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: #fff; flex-shrink: 0;
    }
    .ph-text h1 { color: #fff !important; font-size: 22px !important; font-weight: 700 !important; margin: 0 0 3px !important; line-height: 1.2 !important; }
    .ph-text p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }

    /* ── Table card ── */
    .table-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 32px;
    }
    .table-card-header {
        padding: 18px 24px 16px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; gap: 10px;
    }
    .table-card-title { font-size: 14px; font-weight: 700; color: #1a202c; display: flex; align-items: center; gap: 10px; }
    .table-card-title .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; }

    /* ── Table ── */
    .wa-table { width: 100%; border-collapse: collapse; }
    .wa-table thead th {
        background: #f7f8fc; color: #718096;
        font-size: 11px; font-weight: 700; letter-spacing: .7px; text-transform: uppercase;
        padding: 13px 20px; border-bottom: 2px solid #edf0f5;
        white-space: nowrap; vertical-align: middle;
    }
    .wa-table thead th:nth-child(1) { width: 30%; }
    .wa-table thead th:nth-child(2) { width: 35%; }
    .wa-table thead th:nth-child(3) { width: 15%; text-align: center; }
    .wa-table thead th:nth-child(4) { width: 20%; text-align: center; }

    .wa-table tbody tr { transition: background .15s; }
    .wa-table tbody tr:hover td { background: #f5f8ff; }
    .wa-table tbody tr:last-child td { border-bottom: none; }
    .wa-table tbody td {
        padding: 16px 20px; font-size: 13.5px; color: #2d3748;
        border-bottom: 1px solid #f0f2f7; vertical-align: middle;
    }
    .wa-table tbody td:nth-child(3),
    .wa-table tbody td:nth-child(4) { text-align: center; }

    /* WA number cell */
    .wa-num {
        display: inline-flex; align-items: center; gap: 9px;
    }
    .wa-num-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: #b0ffc8; color: #15ff00;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px; flex-shrink: 0;
    }
    .wa-num-text { font-weight: 700; color: #1a202c; font-size: 14px; }

    /* Info cell */
    .wa-info { font-size: 13px; color: #4a5568; line-height: 1.5; }

    /* Status badges */
    .badge-active {
        display: inline-flex; align-items: center; gap: 5px;
        background: #dcfce7; color: #15803d;
        padding: 5px 14px; border-radius: 20px;
        font-size: 12px; font-weight: 700;
    }
    .badge-active::before { content: ''; width: 6px; height: 6px; background: #22c55e; border-radius: 50%; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.4)} }

    .badge-inactive {
        display: inline-flex; align-items: center; gap: 5px;
        background: #f3f4f6; color: #6b7280;
        padding: 5px 14px; border-radius: 20px;
        font-size: 12px; font-weight: 700;
    }
    .badge-inactive::before { content: ''; width: 6px; height: 6px; background: #9ca3af; border-radius: 50%; }

    /* Action buttons */
    .btn-deactivate {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 18px; border: none; border-radius: 9px;
        background: #fee2e2; color: #991b1b;
        font-size: 12.5px; font-weight: 700; cursor: pointer;
        transition: all .18s;
    }
    .btn-deactivate:hover { background: #fecaca; transform: translateY(-1px); }

    .btn-activate {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 18px; border: none; border-radius: 9px;
        background: #dcfce7; color: #15803d;
        font-size: 12.5px; font-weight: 700; cursor: pointer;
        transition: all .18s;
    }
    .btn-activate:hover { background: #bbf7d0; transform: translateY(-1px); }

    /* Empty state */
    .empty-state {
        text-align: center; padding: 48px 24px;
        color: #a0aec0;
    }
    .empty-state i { font-size: 36px; margin-bottom: 12px; display: block; }
    .empty-state p { font-size: 14px; margin: 0; }
</style>
@endsection

@section('content')
<div class="content-header" style="padding: 0 20px;">

    {{-- ── PAGE HEADER ── --}}
    <div class="page-header-modern">
        <div class="ph-icon"><i class="fab fa-whatsapp"></i></div>
        <div class="ph-text">
            <h1>Setting Whatsapp</h1>
            <p>Kelola nomor WhatsApp aktif untuk notifikasi sistem</p>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span> Daftar Nomor Whatsapp
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table class="wa-table">
                <thead>
                    <tr>
                        <th>Whatsapp No</th>
                        <th>Info</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($whatsapp as $wa)
                    <tr>
                        <td>
                            <div class="wa-num">
                                <div class="wa-num-icon"><i class="fab fa-whatsapp"></i></div>
                                <span class="wa-num-text">{{ $wa->values }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="wa-info">{{ $wa->information }}</span>
                        </td>
                        <td>
                            @if ($wa->remark == 1)
                                <span class="badge-active"><i class="fas fa-check-circle" style="font-size:11px;"></i> Active</span>
                            @else
                                <span class="badge-inactive"><i class="fas fa-minus-circle" style="font-size:11px;"></i> Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if ($wa->remark == 1)
                                <button onclick="changeWhatsapp({{ $wa->id }}, 0)" class="btn-deactivate">
                                    <i class="fas fa-times"></i> Deactivate
                                </button>
                            @else
                                <button onclick="changeWhatsapp({{ $wa->id }}, 1)" class="btn-activate">
                                    <i class="fas fa-check"></i> Activate
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fab fa-whatsapp"></i>
                                <p>Belum ada nomor WhatsApp yang terdaftar.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#side_user_management').addClass('menu-open');
        $('body').addClass('sidebar-collapse');
    });

    function changeWhatsapp(id, remark) {
        var msg = remark == 1 ? 'Aktifkan nomor WhatsApp ini?' : 'Nonaktifkan nomor WhatsApp ini?';

        Swal.fire({
            title: 'Konfirmasi',
            text: msg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: remark == 1 ? '#15803d' : '#dc2626',
            cancelButtonColor: '#718096',
            confirmButtonText: remark == 1 ? '<i class="fas fa-check"></i> Ya, Aktifkan' : '<i class="fas fa-times"></i> Ya, Nonaktifkan',
            cancelButtonText: 'Batal',
        }).then(function (result) {
            if (result.isConfirmed) {
                $('#loading').show();
                $.post('{{ route("admin.settings.changeWhatsapp") }}', {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    remark: remark
                }, function (result) {
                    $('#loading').hide();
                    if (result.status) {
                        location.reload();
                    } else {
                        openErrorGritter('Error!', result.message);
                    }
                });
            }
        });
    }
</script>
@endsection