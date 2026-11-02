{{-- Generated from rpt molding 1.xlsx --}}
@extends('layouts.app')

@section('content')
  <style>
    :root {
      --green: #1f6b4f;
      --green2: #dfeee7;
      --line: #253238;
      --soft: #f4f7f6;
      --yellow: #fff3b0;
      --red: #f8d7da
    }

    * {
      box-sizing: border-box
    }

    .molding-page {
      padding: 18px;
      background: #e9eef0;
      min-height: 100vh;
      font-family: Arial, 'Noto Sans JP', sans-serif;
      color: #172026
    }

    .report-sheet {
      min-width: 1450px;
      max-width: 1900px;
      margin: auto;
      background: white;
      padding: 18px;
      box-shadow: 0 4px 20px #0002
    }

    .toolbar {
      display: flex;
      gap: 8px;
      justify-content: flex-end;
      margin-bottom: 12px;
      position: sticky;
      left: 0
    }

    .btn-report {
      border: 0;
      border-radius: 6px;
      padding: 9px 14px;
      font-weight: 700;
      cursor: pointer
    }

    .btn-green {
      background: var(--green);
      color: white
    }

    .btn-gray {
      background: #dde3e5
    }

    .title {
      text-align: center;
      font-size: 22px;
      font-weight: 800;
      margin: 2px 0 14px
    }

    .section-title {
      font-weight: 800;
      font-size: 15px;
      margin: 12px 0 5px;
      color: #183f32
    }

    .grid-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed
    }

    .grid-table th,
    .grid-table td {
      border: 1px solid var(--line);
      padding: 5px;
      vertical-align: middle;
      white-space: normal;
      overflow-wrap: anywhere
    }

    .grid-table th {
      background: var(--green);
      color: white;
      text-align: center;
      font-size: 12px
    }

    .grid-table td {
      font-size: 12px
    }

    .grid-table input,
    .grid-table textarea,
    .grid-table select {
      width: 100%;
      border: 0;
      background: transparent;
      font: inherit;
      padding: 4px;
      outline: none
    }

    .grid-table textarea {
      resize: vertical;
      min-height: 46px
    }

    .field-label {
      background: var(--green2);
      font-weight: 700
    }

    .center {
      text-align: center
    }

    .status-open {
      background: var(--red) !important;
      color: #8d1820;
      font-weight: 800
    }

    .status-in-progress {
      background: var(--yellow) !important;
      color: #8d6e20;
      font-weight: 800
    }

    .status-temporary-close {
      background: var(--blue) !important;
      color: #184d8d;
      font-weight: 800
    }

    .status-close {
      background: var(--green) !important;
      color: #1b5e20;
      font-weight: 800
    }

    .ok-cell {
      background: #d9ead3 !important;
      color: #1b5e20;
      font-size: 22px !important;
      font-weight: 900
    }

    .ng-cell {
      background: #f4cccc !important;
      color: #8d1820;
      font-size: 22px !important;
      font-weight: 900
    }

    .photo-cell {
      height: 108px;
      background: #fafafa
    }

    .photo-preview {
      width: 100%;
      height: 88px;
      object-fit: contain;
      display: block
    }

    .photo-input {
      font-size: 10px !important
    }

    .detail-wrap {
      overflow: auto;
      border: 1px solid #aab4b7
    }

    .detail-table {
      min-width: 1750px
    }

    .detail-table th {
      position: sticky;
      top: 0;
      z-index: 2;
      height: 68px
    }

    .detail-table tbody tr:nth-child(even) {
      background: #f8faf9
    }

    .detail-table tbody tr:hover {
      background: #fff8db
    }

    .note {
      font-size: 11px;
      font-weight: 400
    }

    .w-date {
      width: 95px
    }

    .w-no {
      width: 80px
    }

    .w-name {
      width: 180px
    }

    .w-point {
      width: 160px
    }

    .w-text {
      width: 190px
    }

    .w-photo {
      width: 160px
    }

    .w-act {
      width: 170px
    }

    .w-pic {
      width: 120px
    }

    .w-judge {
      width: 90px
    }

    @media(max-width:800px) {
      .molding-page {
        padding: 4px
      }

      .report-sheet {
        padding: 8px
      }

      .title {
        font-size: 16px
      }
    }

    @media print {
      body {
        background: #fff
      }

      .molding-page {
        padding: 0;
        background: #fff
      }

      .report-sheet {
        box-shadow: none;
        padding: 0;
        min-width: 0
      }

      .toolbar,
      .photo-input,
      .add-row,
      .delete-row {
        display: none !important
      }

      .detail-wrap {
        overflow: visible;
        border: 0
      }

      .detail-table {
        min-width: 0;
        font-size: 7px
      }

      .grid-table th,
      .grid-table td {
        padding: 2px;
        font-size: 7px
      }

      .photo-cell {
        height: 55px
      }

      .photo-preview {
        height: 50px
      }

      @page {
        size: A3 landscape;
        margin: 7mm
      }
    }

    /* Approval */
    .approval-wrapper {
        display: flex;
        justify-content: flex-end;
        margin: 0 0 14px 0;
    }

    .approval-table {
        width: 480px;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .approval-table th,
    .approval-table td {
        border: 1px solid var(--line);
        text-align: center;
        vertical-align: middle;
        padding: 5px 8px;
        font-size: 11px;
    }

    .approval-table th {
        background: var(--green);
        color: #fff;
        font-weight: 700;
    }

    .approval-name {
        font-weight: 700;
        min-height: 25px;
    }

    .approval-date {
        font-size: 10px !important;
        color: #555;
        white-space: nowrap;
    }
  </style>

  <div class="molding-page">
    <form method="POST" action="{{ $action ?? '#' }}" enctype="multipart/form-data" id="moldingReportForm">
      @csrf
      <div class="report-sheet">
        <div class="title">
            Report Maintenance Periodik Molding di PT. Arisa / 定期メンテナンスレポート（PT. ARISA）
        </div>

        {{-- APPROVAL --}}
        <div class="approval-wrapper">
            <table class="approval-table">
                <thead>
                    <tr>
                        <th>Dibuat Oleh</th>
                        <th>Approval 1</th>
                        <th>Approval 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="approval-name">
                            {{ $approver['created_by_name'] ?? '-' }}
                        </td>

                        <td class="approval-name">
                            {{ $approver['approval_1_name'] ?? '-' }}
                        </td>

                        <td class="approval-name">
                            {{ $approver['approval_2_name'] ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="approval-date">
                            {{ $approver['created_at']
                                ? date('d M Y', strtotime($approver['created_at']))
                                : '-' }}
                        </td>

                        <td class="approval-date">
                            {{ $approver['approval_1_date']
                                ? date('d M Y', strtotime($approver['approval_1_date']))
                                : '-' }}
                        </td>

                        <td class="approval-date">
                            {{ $approver['approval_2_date']
                                ? date('d M Y', strtotime($approver['approval_2_date']))
                                : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section-title">
            ■ Outline Pengecekan 検査概要
        </div>
        <table class="grid-table">
          <colgroup>
            <col style="width:20%">
            <col style="width:30%">
            <col style="width:20%">
            <col style="width:30%">
          </colgroup>
          <tr>
            <td class="field-label">Periode </td>
            <td>{{ old('tanggal_finish', $period ?? '') }}
            </td>
            <td class="field-label">Tipe 機種</td>
            <td><input name="tipe" value="{{ old('tipe', $data_molding->molding_type ?? '') }}"></td>
          </tr>
          <tr>
            <td class="field-label">Periode </td>
            <td>{{ old('tanggal_finish', $period ?? '') }}</td>
            <td class="field-label">Mold 金型</td>
            <td><input name="mold"
                value="{{ old('mold', $data_molding->molding_category ?? $data_molding->molding_name ?? '') }}"></td>
          </tr>
          <tr>
            <td class="field-label">PIC 検査者</td>
            <td><textarea name="pic_outline">{{ old('pic_outline', $report->pic_outline ?? "Samsul Hadi
    Abdul Hakim") }}</textarea></td>
            <td class="field-label">Seri 番号</td>
            <td><input name="seri" value="{{ old('seri', '#' . $data_molding->mold_number ?? '#1') }}"></td>
          </tr>
        </table>

        <div class="section-title">■ Kesimpulan 結論</div>
        <table class="grid-table">
          <tr>
            <td class="field-label" style="width:40%">Kelayakan Molding 金型使用可否</td>
            <td><select name="kelayakan">
                <option value="Bisa" selected>Bisa (使用可)</option>
                <option value="Tidak Bisa">Tidak Bisa (使用不可)</option>
                <option value="Perbaikan">Perlu Perbaikan (要修理)</option>
              </select></td>
          </tr>
        </table>

        <div class="section-title">■ Permasalahan &amp; Penanganan 問題および対策</div>
        <table class="grid-table" id="problemTable">
          <thead>
            <tr>
              <th style="width:6%">No</th>
              <th style="width:40%">Permasalahan<br>問題</th>
              <th style="width:40%">Penanganan<br>対策</th>
              <th style="width:14%">Status<br>状態</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data_finding as $find)
              <tr>
                <td class="center">{{ $loop->iteration }}</td>
                <td>{!! $find->problem ?? '' !!}</td>
                <td>{!! $find->note_problem ?? '' !!}</td>
                <td class="{{ strtolower(str_replace(' ', '-', $find->status ?? '')) }}">{{ $find->status ?? '' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="section-title">■ Detail Pengecekan 検査詳細内容 <span class="note">※ Urutan yang dicocokkan dengan Parts
            List 並び順は部品表に合わせる</span></div>
        <div class="detail-wrap">
          <table class="grid-table detail-table" id="detailTable">
            <thead>
              <tr>
                <th class="w-date">Tanggal Cek<br>検査日</th>
                <th class="w-name">Nama<br>部品名</th>
                <th class="w-point">Point Cek<br>検査項目</th>
                <th class="w-text">Standar<br>検査基準</th>
                <th class="w-text">Cara pengecekan<br>検査方法</th>
                <th class="w-text">Cara penanganan<br>対応方法</th>
                <th class="w-photo">Foto Before<br>検査前写真</th>
                <th class="w-photo">Foto After<br>検査後写真</th>
                <th class="w-act">Aktivitas Pekerjaan<br>作業内容</th>
                <th class="w-pic">PIC<br>担当者</th>
                <th class="w-judge">Judgement<br>判断（〇/X）</th>
              </tr>
            </thead>
            <tbody id="detailBody">
              @foreach ($data_check as $check)
                <tr data-index="{{ $loop->index }}">
                  <td>{{ $check->check_date }}</td>
                  <td>{{ $check->part_name ?? '' }}</td>
                  <td>{{ $check->point_check ?? '' }}</td>
                  <td>{{ $check->standard ?? '' }}</td>
                  <td>{{ $check->how_check ?? '' }}</td>
                  <td>{{ $check->handle ?? '' }}</td>
                  <td class="photo-cell">
                    <img class="photo-preview" alt="Preview foto"
                      src="{{ url('workshop/Audit_Molding/Check_Molding/check_att/' . $check->photo_before1 ?? '') }}">
                    <img class="photo-preview" alt="Preview foto"
                      src="{{ url('workshop/Audit_Molding/Check_Molding/check_att/' . $check->photo_before2 ?? '') }}">
                  </td>
                  <td class="photo-cell">
                    <img class="photo-preview" alt="Preview foto"
                      src="{{ url('workshop/Audit_Molding/Check_Molding/check_att/' . $check->photo_after1 ?? '') }}">
                    <img class="photo-preview" alt="Preview foto"
                      src="{{ url('workshop/Audit_Molding/Check_Molding/check_att/' . $check->photo_after2 ?? '') }}">
                  </td>
                  <td class="photo-cell">
                    <img class="photo-preview" alt="Preview foto"
                      src="{{ url('workshop/Audit_Molding/Check_Molding/check_att/' . $check->photo_activity1 ?? '') }}">
                    <img class="photo-preview" alt="Preview foto"
                      src="{{ url('workshop/Audit_Molding/Check_Molding/check_att/' . $check->photo_activity2 ?? '') }}">
                  </td>
                  <td>{{ explode(' - ', $check->pic ?? '')[1] ?? '' }}</td>
                  <td class="center ok-cell {{ ($check->judgement ?? '') == 'NG' ? 'ng-cell' : '' }}">{{ ($check->judgement ?? '') == 'OK' ? '〇' : (($check->judgement ?? '') == 'NG' ? 'X' : '') }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </form>
  </div>
  <script>
    (function () {
      const body = document.getElementById('detailBody');
      function renumber() { [...body.rows].forEach((row, i) => { row.dataset.index = i; row.querySelectorAll('[name]').forEach(el => { el.name = el.name.replace(/details\[\d+\]/, 'details[' + i + ']') }) }) }
      window.previewPhoto = function (input) { const img = input.parentElement.querySelector('.photo-preview'); if (input.files && input.files[0]) { img.src = URL.createObjectURL(input.files[0]); img.hidden = false } }
      window.removeRow = function (btn) { if (body.rows.length > 1) { btn.closest('tr').remove(); renumber() } }
      document.getElementById('addRow').addEventListener('click', function () { const i = body.rows.length; const tr = document.createElement('tr'); tr.dataset.index = i; tr.innerHTML = `<td><input type="date" name="details[${i}][tanggal]"></td><td><input name="details[${i}][no_part]"></td><td><textarea name="details[${i}][nama]"></textarea></td><td><textarea name="details[${i}][point_cek]"></textarea></td><td><textarea name="details[${i}][standar]"></textarea></td><td><textarea name="details[${i}][cara_cek]"></textarea></td><td><textarea name="details[${i}][penanganan]"></textarea></td><td class="photo-cell"><img class="photo-preview" hidden alt="Preview foto"><input class="photo-input" type="file" accept="image/*" name="details[${i}][foto_before]" onchange="previewPhoto(this)"></td><td class="photo-cell"><img class="photo-preview" hidden alt="Preview foto"><input class="photo-input" type="file" accept="image/*" name="details[${i}][foto_after]" onchange="previewPhoto(this)"></td><td><textarea name="details[${i}][aktivitas]"></textarea></td><td><input name="details[${i}][pic]"></td><td><select name="details[${i}][judgement]"><option></option><option>〇</option><option>×</option></select></td><td class="center delete-row"><button type="button" class="btn-report btn-gray" onclick="removeRow(this)">×</button></td>`; body.appendChild(tr); tr.scrollIntoView({ behavior: 'smooth', block: 'center' }) })
      document.addEventListener('change', function (e) { if (e.target.matches('select[name$="[judgement]"]')) { e.target.closest('td').classList.toggle('ok-cell', e.target.value === '〇') } })
    })();
  </script>
@endsection