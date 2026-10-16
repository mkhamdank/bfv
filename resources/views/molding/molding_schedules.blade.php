@extends('layouts.master')

@section('styles')
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .schedule-page { font-family: 'Plus Jakarta Sans', sans-serif; padding: 20px 0 35px; color: #25324a; }
    .schedule-header { background: linear-gradient(135deg,#2d2b4e,#605ca8); border-radius: 18px; padding: 26px 30px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:15px; color:#fff; margin-bottom:22px; }
    .schedule-header h1 { font-size:22px; font-weight:800; margin:0 0 7px; color:#fff; }
    .schedule-header p { margin:0; color:#e3e0ff; font-size:13px; }
    .schedule-actions { display:flex; gap:9px; flex-wrap:wrap; }
    .schedule-btn { border:0; border-radius:9px; padding:10px 14px; font-weight:700; font-size:12px; display:inline-flex; align-items:center; gap:8px; cursor:pointer; text-decoration:none !important; }
    .schedule-btn-light { background:#fff; color:#514b97 !important; }
    .schedule-btn-primary { background:#4169b2; color:#fff !important; }
    .schedule-btn-purple { background:#605ca8; color:#fff !important; }
    .schedule-btn-danger { background:#fff0f1; color:#d73448 !important; }
    .schedule-btn-outline { background:#f4f2ff; color:#605ca8 !important; }
    .schedule-list { display:grid; gap:15px; }
    .schedule-card { background:#fff; border:1px solid #e3e8f2; border-radius:14px; box-shadow:0 5px 20px rgba(31,42,73,.05); overflow:hidden; }
    .schedule-card-head { padding:17px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; background:#fafaff; border-bottom:1px solid #e9ecf5; }
    .schedule-period { color:#342f66; font-weight:800; font-size:16px; }
    .schedule-count { background:#ebe8ff; color:#504994; border-radius:30px; font-size:11px; padding:5px 9px; margin-left:9px; font-weight:700; }
    .schedule-card-body { padding:15px 20px; display:grid; grid-template-columns:minmax(0,1fr); gap:10px; }
    .schedule-molding { background:#f8fafc; border:1px solid #e1e6ef; border-radius:9px; padding:11px 13px; display:grid; grid-template-columns:minmax(0,1fr) auto auto; align-items:center; gap:12px; width:100%; min-width:0; }
    .schedule-molding-name { min-width:0; font-size:13px; line-height:1.5; color:#25324a; white-space:normal; overflow-wrap:anywhere; }
    .schedule-molding-name small { display:block; margin-top:3px; color:#59677e; font-size:11px; white-space:normal; overflow-wrap:anywhere; }
    @media (max-width:580px) { .schedule-molding { grid-template-columns:minmax(0,1fr) auto; } .schedule-molding .schedule-status { grid-column:1; justify-self:start; } .schedule-icon-delete { grid-column:2; grid-row:1 / span 2; } }
    .schedule-status { font-size:11px; font-weight:800; border-radius:20px; padding:4px 8px; background:#e6efff; color:#315ca2; white-space:nowrap; }
    .schedule-status.status-close { background:#dff8e9; color:#16834b; }
    .schedule-status.status-temporary-close { background:#fff3d9; color:#a86b00; }
    .schedule-status.status-in-progress { background:#eae7fb; color:#605ca8; }
    .schedule-icon-delete { background:transparent; border:0; color:#d73448; padding:3px; cursor:pointer; }
    .schedule-empty { padding:42px; text-align:center; background:#fff; border-radius:14px; color:#758098; border:1px dashed #c8d0df; }
    .schedule-modal .modal-dialog { max-width:650px; }
    .schedule-modal.fade { transition:opacity .28s ease-in-out; }
    .schedule-modal.fade .modal-dialog {
        opacity:0;
        transform:translateY(-18px) scale(.97);
        transition:transform .28s ease-in-out, opacity .28s ease-in-out;
    }
    .schedule-modal.show .modal-dialog,
    .schedule-modal.in .modal-dialog {
        opacity:1;
        transform:translateY(0) scale(1);
    }
    .schedule-modal .modal-content { border-radius:14px; border:0; overflow:hidden; }
    .schedule-modal .modal-header { padding:18px 22px; background:#fafaff; border-bottom:1px solid #e9ecf5; }
    .schedule-modal .modal-title { font-weight:800; color:#342f66; }
    .schedule-modal .modal-body { padding:22px; }
    .schedule-modal label { display:block; font-weight:700; margin-bottom:8px; }
    .schedule-modal input[type=text], .schedule-modal input[type=month], .schedule-modal select { display:block; width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:9px 11px; background:#fff; color:#25324a; cursor:pointer; }
    .schedule-modal .select2-container { width:100% !important; }
    .schedule-modal .select2-selection--multiple { min-height:43px; border:1px solid #cbd5e1; border-radius:8px; }
    #scheduleModal .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background:#e9e7f8 !important;
        border:1px solid #b9b4df !important;
        border-radius:6px !important;
        color:#302c63 !important;
        font-weight:700;
        padding:3px 8px;
    }
    #scheduleModal .select2-selection__choice,
    #scheduleModal .select2-selection__choice__display,
    #scheduleModal .select2-selection__choice span {
        color:#302c63 !important;
        opacity:1 !important;
    }
    #scheduleModal .select2-selection__choice__remove {
        color:#514b97 !important;
        border-right-color:#c8c3e8 !important;
        opacity:1 !important;
    }
    #scheduleModal .select2-selection__choice__remove:hover {
        color:#bf3046 !important;
        background:#f9e5ea !important;
    }
    #scheduleModal .select2-search-choice {
        background:#e9e7f8 !important;
        border-color:#b9b4df !important;
        color:#302c63 !important;
    }
    #scheduleModal .select2-search-choice div { color:#302c63 !important; }
    .schedule-modal .modal-footer { padding:14px 22px; }
    .datepicker-dropdown { z-index:30000 !important; }
    .datepicker table tr td span.active, .datepicker table tr td span.active:hover { background:#605ca8 !important; }
    #scheduleLoading { display:none; position:fixed; inset:0; background:rgba(27,29,55,.38); z-index:30001; align-items:center; justify-content:center; }
    #scheduleLoading.active { display:flex; }
    .schedule-loading-box { background:#fff; color:#423d77; padding:20px 28px; border-radius:12px; box-shadow:0 12px 30px #0002; font-weight:700; }
</style>
@endsection

@section('header')@stop

@section('content')
<div class="container-fluid schedule-page">
    <div class="schedule-header">
        <div>
            <h1><i class="fas fa-calendar-alt"></i> Daftar Schedule Maintenance Molding</h1>
            <p>Kelola periode dan molding yang dijadwalkan.</p>
        </div>
        <div class="schedule-actions">
            <a href="{{ url('index/diagnose_molding/molding_monitoring_new') }}" class="schedule-btn schedule-btn-light"><i class="fas fa-arrow-left"></i> Monitoring</a>
            <button type="button" class="schedule-btn schedule-btn-primary" onclick="openScheduleCreate()"><i class="fas fa-plus"></i> Tambah Schedule</button>
        </div>
    </div>

    @if($schedules->isEmpty())
        <div class="schedule-empty">Belum ada schedule. Klik <strong>Tambah Schedule</strong> untuk membuat jadwal pertama.</div>
    @else
        <div class="schedule-list">
            @foreach($schedules as $schedule)
                <div class="schedule-card">
                    <div class="schedule-card-head">
                        <div>
                            <span class="schedule-period">{{ \Carbon\Carbon::parse($schedule->period)->locale('id')->translatedFormat('F Y') }}</span>
                            <span class="schedule-count">{{ $schedule->items->count() }} molding</span>
                        </div>
                        <div class="schedule-actions">
                            <button type="button" class="schedule-btn schedule-btn-outline" onclick="openScheduleEdit({{ $schedule->id }})"><i class="fas fa-edit"></i> Edit Jadwal</button>
                            <button type="button" class="schedule-btn schedule-btn-danger" onclick="deleteSchedule({{ $schedule->id }})"><i class="fas fa-trash"></i> Hapus Jadwal</button>
                        </div>
                    </div>
                    <div class="schedule-card-body">
                        @foreach($schedule->items as $item)
                            <div class="schedule-molding">
                                <div class="schedule-molding-name">
                                    {{ $item->molding_name }}
                                    <small>
                                        Fixed Asset Number: {{ $item->molding_id }}
                                    </small>
                                </div>
                                @php $statusClass = strtolower(str_replace(' ', '-', (string) $item->status)); @endphp
                                <span class="schedule-status status-{{ $statusClass }}">{{ $item->status ?: 'Belum ada status' }}</span>
                                <button type="button" class="schedule-icon-delete" title="Hapus molding dari jadwal" onclick="deleteMolding({{ $schedule->id }}, {{ $item->id }})"><i class="fas fa-times"></i></button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div id="scheduleLoading"><div class="schedule-loading-box"><i class="fas fa-spinner fa-spin"></i> Memproses schedule...</div></div>

<div class="modal fade schedule-modal" id="scheduleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="scheduleForm">
                <div class="modal-header">
                    <h4 class="modal-title" id="scheduleModalTitle">Tambah Schedule</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="schedulePeriod">Periode</label>
                        <input type="text" id="schedulePeriod" name="period" placeholder="YYYY-MM" autocomplete="off" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="scheduleMoldings">Pilih Molding</label>
                        <select id="scheduleMoldings" name="molding_ids[]" multiple required>
                            @foreach($moldings as $molding)
                                <option value="{{ $molding->fixed_asset_number }}">{{ $molding->molding_name }} #{{ $molding->fixed_asset_number }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Bisa memilih lebih dari satu molding.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="schedule-btn schedule-btn-outline" data-dismiss="modal">Batal</button>
                    <button type="submit" class="schedule-btn schedule-btn-purple"><i class="fas fa-save"></i> Simpan Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script>
    var scheduleBaseUrl = @json(url('workshop/check_molding_vendor/schedules'));
    var scheduleRows = @json($schedules);
    var editingScheduleId = null;

    $(function () {
        if ($.fn.datepicker) {
            $('#schedulePeriod').datepicker({
                format: 'yyyy-mm',
                minViewMode: 1,
                autoclose: true,
                clearBtn: true,
                orientation: 'bottom auto'
            });
        } else {
            // Tetap sediakan pemilih bulan bawaan browser jika plugin belum dimuat.
            $('#schedulePeriod').attr('type', 'month').prop('readOnly', false);
        }
        if ($.fn.select2) {
            $('#scheduleMoldings').select2({
                dropdownParent: $('#scheduleModal'),
                width: '100%',
                placeholder: 'Pilih molding'
            });
        }
        $('#scheduleForm').on('submit', function (event) {
            event.preventDefault();
            saveSchedule();
        });
    });

    function openScheduleCreate() {
        editingScheduleId = null;
        $('#scheduleModalTitle').text('Tambah Schedule');
        $('#scheduleForm')[0].reset();
        $('#scheduleMoldings').val([]).trigger('change');
        $('#scheduleModal').modal('show');
    }

    function openScheduleEdit(id) {
        var schedule = scheduleRows.find(function (row) { return Number(row.id) === Number(id); });
        if (!schedule) return;
        editingScheduleId = id;
        $('#scheduleModalTitle').text('Edit Schedule');
        $('#schedulePeriod').val(schedule.period_month);
        $('#scheduleMoldings').val(schedule.items.map(function (item) { return String(item.molding_id); })).trigger('change');
        $('#scheduleModal').modal('show');
    }

    function saveSchedule() {
        var period = $('#schedulePeriod').val();
        var moldings = $('#scheduleMoldings').val() || [];
        if (!period || !moldings.length) {
            notifySchedule(false, 'Isi periode dan pilih minimal satu molding.');
            return;
        }
        var url = editingScheduleId ? scheduleBaseUrl + '/' + editingScheduleId : scheduleBaseUrl;
        var payload = { period: period, molding_ids: moldings };
        if (editingScheduleId) payload._method = 'PUT';
        scheduleRequest(url, 'POST', payload);
    }

    function deleteSchedule(id) {
        if (!confirm('Hapus jadwal ini beserta seluruh molding di dalamnya?')) return;
        scheduleRequest(scheduleBaseUrl + '/' + id, 'POST', { _method: 'DELETE' });
    }

    function deleteMolding(scheduleId, itemId) {
        if (!confirm('Hapus molding ini dari schedule? Jika ini molding terakhir, jadwal juga akan dihapus.')) return;
        scheduleRequest(scheduleBaseUrl + '/' + scheduleId + '/moldings/' + itemId, 'POST', { _method: 'DELETE' });
    }

    function scheduleRequest(url, method, payload) {
        $('#scheduleLoading').addClass('active');
        $.ajax({
            url: url,
            method: method,
            data: payload,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                notifySchedule(true, response.message || 'Berhasil disimpan.');
                window.location.reload();
            },
            error: function (xhr) {
                var errors = xhr.responseJSON && xhr.responseJSON.errors;
                var firstKey = errors && Object.keys(errors)[0];
                var message = firstKey ? errors[firstKey][0] :
                    (xhr.responseJSON && xhr.responseJSON.message) || 'Proses gagal. Coba lagi.';
                notifySchedule(false, message);
            },
            complete: function () { $('#scheduleLoading').removeClass('active'); }
        });
    }

    function notifySchedule(success, message) {
        if (success && typeof openSuccessGritter === 'function') openSuccessGritter('Sukses', message);
        else if (!success && typeof openErrorGritter === 'function') openErrorGritter('Error', message);
        else alert(message);
    }
</script>
@endsection
