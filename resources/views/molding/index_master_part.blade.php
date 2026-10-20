@extends('layouts.master')

@section('title', 'Master Part Molding')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { background: #f0f2f7 !important; }
    .master-part-page { padding: 0 24px; font-family: 'Plus Jakarta Sans', sans-serif; }
    .master-part-header {
        margin: 24px 0; padding: 24px 30px; border-radius: 12px;
        background: linear-gradient(120deg, #263c47, #39766e); color: #fff;
    }
    .master-part-header h1 { margin: 0 0 5px; font-size: 24px; font-weight: 700; }
    .master-part-header p { margin: 0; color: rgba(255,255,255,.76); font-size: 13px; }
    .master-part-section {
        margin-bottom: 24px; background: #fff; border: 1px solid #e5e9ed;
        border-radius: 8px; overflow: hidden;
    }
    .master-part-section h2 {
        margin: 0; padding: 16px 20px; border-bottom: 1px solid #e9edef;
        background: #f8faf9; color: #263c47; font-size: 15px; font-weight: 700;
    }
    .master-part-section h2.master-part-list-heading {
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
    }
    .master-part-body { padding: 20px; }
    .master-part-form { display: grid; grid-template-columns: 2fr repeat(3, 1fr) auto; gap: 12px; align-items: end; }
    .master-part-form label { display: block; margin-bottom: 6px; color: #52616a; font-size: 12px; font-weight: 700; }
    .master-part-form input:not([type="hidden"]), .master-part-table input:not([type="hidden"]),
    .master-part-form textarea, .master-part-table textarea {
        width: 100%; min-width: 0; padding: 9px 10px; border: 1px solid #ccd5d8;
        border-radius: 4px; color: #25343a; font-size: 13px; line-height: 1.4;
    }
    .master-part-form input:focus, .master-part-table input:focus,
    .master-part-form textarea:focus, .master-part-table textarea:focus { outline: 2px solid #b6d9d0; border-color: #39766e; }
    .master-part-item-textarea { min-height: 42px; overflow-y: hidden; resize: vertical; }
    .master-part-tag-editor {
        display: flex; flex-wrap: wrap; align-items: center; gap: 5px;
        min-height: 42px; padding: 5px 7px; border: 1px solid #ccd5d8;
        border-radius: 4px; background: #fff;
    }
    .master-part-tag-editor:focus-within { outline: 2px solid #b6d9d0; border-color: #39766e; }
    .master-part-tags { display: contents; }
    .master-part-tag {
        display: inline-flex; align-items: center; gap: 5px; max-width: 100%;
        padding: 4px 6px; border: 1px solid #d4e6df; border-radius: 4px;
        background: #edf5f2; color: #2d615a; font-size: 12px; line-height: 1.25;
    }
    .master-part-tag > span { overflow-wrap: anywhere; }
    .master-part-tag-remove {
        display: inline-flex; align-items: center; justify-content: center;
        width: 18px; height: 18px; padding: 0; border: 0; background: transparent;
        color: #52766c; cursor: pointer; flex-shrink: 0;
    }
    .master-part-tag-remove:hover { color: #a33e3e; }
    .master-part-tag-entry {
        flex: 1 1 90px; width: auto !important; min-width: 90px;
        padding: 5px 2px !important; border: 0 !important; outline: 0 !important;
        background: transparent; font-size: 12px !important;
    }
    .master-part-grouping + .select2-container { width: 100% !important; }
    .master-part-grouping + .select2-container .select2-selection--single {
        height: 38px; border-color: #ccd5d8; border-radius: 4px;
    }
    .master-part-grouping + .select2-container .select2-selection__rendered {
        line-height: 36px; color: #25343a;
    }
    .master-part-grouping + .select2-container .select2-selection__arrow { height: 36px; }
    .master-part-button {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        min-height: 38px; padding: 8px 13px; border: 0; border-radius: 4px;
        background: #39766e; color: #fff; font-size: 13px; font-weight: 700; cursor: pointer;
    }
    .master-part-button:hover { background: #2d615a; color: #fff; }
    .master-part-button.delete { background: #a33e3e; }
    .master-part-button.delete:hover { background: #842f2f; }
    .master-part-table { width: 100%; border-collapse: collapse; }
    .master-part-table th, .master-part-table td { padding: 10px 12px; border-bottom: 1px solid #e9edef; text-align: left; }
    .master-part-table th { background: #f8faf9; color: #52616a; font-size: 11px; text-transform: uppercase; }
    .master-part-table td { vertical-align: middle; }
    .master-part-actions { display: flex; gap: 7px; white-space: nowrap; }
    .master-part-alert { margin-bottom: 16px; padding: 11px 14px; border-radius: 4px; }
    .master-part-alert.success { background: #e7f5ee; color: #246344; }
    .master-part-alert.error { background: #fdecec; color: #922f2f; }
    .master-part-empty { color: #718096; text-align: center !important; padding: 28px !important; }
    @media (max-width: 767px) {
        .master-part-page { padding: 0 12px; }
        .master-part-form { grid-template-columns: 1fr; }
        .master-part-table-wrap { overflow-x: auto; }
        .master-part-table { min-width: 700px; }
        .master-part-section h2.master-part-list-heading { flex-wrap: wrap; }
    }
</style>
@endsection

@section('content')
<div class="master-part-page">
    <header class="master-part-header">
        <h1>Master Part Molding</h1>
        <p>Kelola item diagnosa, area pemeriksaan, jenis NG, dan grouping molding.</p>
    </header>

    @if (session('success'))
        <div class="master-part-alert success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="master-part-alert error">{{ $errors->first() }}</div>
    @endif

    <section class="master-part-section">
        <h2>Tambah Master Part</h2>
        <div class="master-part-body">
            <form action="{{ route('user.molding_master_part.store') }}" method="POST" class="master-part-form">
                @csrf
                <div>
                    <label for="item_diagnosa">Item Diagnosa</label>
                    <textarea id="item_diagnosa" name="item_diagnosa" class="master-part-item-textarea" rows="2" maxlength="255" required>{{ old('item_diagnosa') }}</textarea>
                </div>
                <div>
                    <label for="area-entry">Area</label>
                    <div class="master-part-tag-editor" data-tag-editor>
                        <input type="hidden" name="area" value="{{ old('area') }}" data-tag-value>
                        <div class="master-part-tags" data-tag-list></div>
                        <input id="area-entry" type="text" class="master-part-tag-entry" data-tag-entry autocomplete="off" placeholder="Tambah area">
                    </div>
                </div>
                <div>
                    <label for="jenis-ng-entry">Jenis NG</label>
                    <div class="master-part-tag-editor" data-tag-editor>
                        <input type="hidden" name="jenis_ng" value="{{ old('jenis_ng') }}" data-tag-value>
                        <div class="master-part-tags" data-tag-list></div>
                        <input id="jenis-ng-entry" type="text" class="master-part-tag-entry" data-tag-entry autocomplete="off" placeholder="Tambah jenis NG">
                    </div>
                </div>
                <div>
                    <label for="grouping-create">Grouping</label>
                    <select id="grouping-create" name="grouping" class="form-control master-part-grouping" data-placeholder="Pilih atau ketik grouping">
                        <option value=""></option>
                        @foreach ($groupings as $grouping)
                            <option value="{{ $grouping }}" {{ old('grouping') === $grouping ? 'selected' : '' }}>{{ $grouping }}</option>
                        @endforeach
                        @if (old('grouping') && !$groupings->contains(old('grouping')))
                            <option value="{{ old('grouping') }}" selected>{{ old('grouping') }}</option>
                        @endif
                    </select>
                </div>
                <button type="submit" class="master-part-button"><i class="fas fa-plus" aria-hidden="true"></i> Tambah</button>
            </form>
        </div>
    </section>

    <section class="master-part-section">
        <h2 class="master-part-list-heading">
            <span>Daftar Master Part</span>
            @if ($masterParts->isNotEmpty())
                <button type="submit" form="update-all-master-parts" class="master-part-button master-part-save-all">
                    <i class="fas fa-save" aria-hidden="true"></i> Simpan Semua
                </button>
            @endif
        </h2>
        <form id="update-all-master-parts" action="{{ route('user.molding_master_part.update_all') }}" method="POST" hidden>
            @csrf
            @method('PUT')
        </form>
        <div class="master-part-table-wrap">
            <table class="master-part-table">
                <thead>
                    <tr>
                        <th style="width: 55px">No.</th>
                        <th>Item Diagnosa</th>
                        <th>Area</th>
                        <th>Jenis NG</th>
                        <th>Grouping</th>
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($masterParts as $part)
                        @php
                            $partGrouping = old('parts.' . $part->id . '.grouping', $part->grouping);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><textarea form="update-all-master-parts" name="parts[{{ $part->id }}][item_diagnosa]" class="master-part-item-textarea" rows="2" maxlength="255" required aria-label="Item diagnosa">{{ old('parts.' . $part->id . '.item_diagnosa', $part->item_diagnosa) }}</textarea></td>
                            <td>
                                <div class="master-part-tag-editor" data-tag-editor>
                                    <input type="hidden" form="update-all-master-parts" name="parts[{{ $part->id }}][area]" value="{{ old('parts.' . $part->id . '.area', $part->area) }}" data-tag-value>
                                    <div class="master-part-tags" data-tag-list></div>
                                    <input type="text" class="master-part-tag-entry" data-tag-entry autocomplete="off" aria-label="Tambah area">
                                </div>
                            </td>
                            <td>
                                <div class="master-part-tag-editor" data-tag-editor>
                                    <input type="hidden" form="update-all-master-parts" name="parts[{{ $part->id }}][jenis_ng]" value="{{ old('parts.' . $part->id . '.jenis_ng', $part->jenis_ng) }}" data-tag-value>
                                    <div class="master-part-tags" data-tag-list></div>
                                    <input type="text" class="master-part-tag-entry" data-tag-entry autocomplete="off" aria-label="Tambah jenis NG">
                                </div>
                            </td>
                            <td>
                                <select form="update-all-master-parts" name="parts[{{ $part->id }}][grouping]" class="form-control master-part-grouping" aria-label="Grouping" data-placeholder="Pilih atau ketik grouping">
                                    <option value=""></option>
                                    @foreach ($groupings as $grouping)
                                        <option value="{{ $grouping }}" {{ $partGrouping === $grouping ? 'selected' : '' }}>{{ $grouping }}</option>
                                    @endforeach
                                    @if ($partGrouping && !$groupings->contains($partGrouping))
                                        <option value="{{ $partGrouping }}" selected>{{ $partGrouping }}</option>
                                    @endif
                                </select>
                            </td>
                            <td>
                                <div class="master-part-actions">
                                    <form action="{{ route('user.molding_master_part.delete', $part->id) }}" method="POST" onsubmit="return confirm('Hapus master part ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="master-part-button delete" aria-label="Hapus master part"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="master-part-empty">Belum ada master part molding.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="{{ url('js/toastr.min.js') }}"></script>
<script>
    $(function () {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            timeOut: 4000
        };

        @if (session('success'))
            toastr.success(@json(session('success')), 'Berhasil');
        @endif
        @if ($errors->any())
            toastr.error(@json($errors->first()), 'Gagal');
        @endif

        $('.master-part-grouping').select2({
            width: '100%',
            tags: true,
            allowClear: true,
            placeholder: 'Pilih atau ketik grouping',
            createTag: function (params) {
                var term = $.trim(params.term);
                if (!term) {
                    return null;
                }

                return { id: term, text: term };
            }
        });

        $('#update-all-master-parts').on('submit', function () {
            var button = document.querySelector('.master-part-save-all');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Menyimpan...';
            }
        });
    });

    document.querySelectorAll('.master-part-item-textarea').forEach(function (textarea) {
        function resizeTextarea() {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        textarea.addEventListener('input', resizeTextarea);
        resizeTextarea();
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-tag-editor]').forEach(function (editor) {
            var hidden = editor.querySelector('[data-tag-value]');
            var list = editor.querySelector('[data-tag-list]');
            var entry = editor.querySelector('[data-tag-entry]');
            var items = (hidden.value || '').split(',').map(function (item) {
                return item.trim();
            }).filter(Boolean);

            function render() {
                list.replaceChildren();
                items.forEach(function (item, index) {
                    var tag = document.createElement('span');
                    var text = document.createElement('span');
                    var remove = document.createElement('button');
                    var icon = document.createElement('i');

                    tag.className = 'master-part-tag';
                    text.textContent = item;
                    remove.type = 'button';
                    remove.className = 'master-part-tag-remove';
                    remove.setAttribute('aria-label', 'Hapus ' + item);
                    icon.className = 'fas fa-times';
                    icon.setAttribute('aria-hidden', 'true');
                    remove.appendChild(icon);
                    remove.addEventListener('click', function () {
                        items.splice(index, 1);
                        render();
                    });
                    tag.append(text, remove);
                    list.appendChild(tag);
                });

                hidden.value = items.join(', ');
            }

            function addItem(value) {
                var item = value.trim();
                if (item && !items.includes(item)) {
                    items.push(item);
                    render();
                }
            }

            entry.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ',') {
                    event.preventDefault();
                    addItem(entry.value);
                    entry.value = '';
                } else if (event.key === 'Backspace' && !entry.value && items.length) {
                    items.pop();
                    render();
                }
            });

            entry.addEventListener('input', function () {
                var parts = entry.value.split(',');
                if (parts.length > 1) {
                    parts.slice(0, -1).forEach(addItem);
                    entry.value = parts[parts.length - 1];
                }
            });

            if (hidden.form) {
                hidden.form.addEventListener('submit', function () {
                    addItem(entry.value);
                    entry.value = '';
                });
            }

            render();
        });
    });
</script>
@endsection