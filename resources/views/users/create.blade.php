@extends('layouts.master')

@section('title', 'Add User')

@section('styles')
<style>
    /* ── PAGE HEADER ── */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px;
        margin: 24px 24px 24px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 16px;
        position: relative; overflow: hidden;
    }
    .page-header-modern::before {
        content: ''; position: absolute; right: -50px; top: -50px;
        width: 220px; height: 220px; border-radius: 50%;
        background: rgba(255,255,255,.05); pointer-events: none;
    }
    .header-left .badge-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0; font-size: 11px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; padding: 5px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .header-left h1 { color: #fff; font-size: 26px; font-weight: 700; margin: 0 0 4px; }
    .header-left p  { color: rgba(255,255,255,.55); font-size: 13px; margin: 0; }

    .btn-back-user {
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
    .btn-back-user:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,.22);
        color: #605ca8;
        text-decoration: none;
    }
    /* ── FORM CARD ── */
    .form-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 2px 16px rgba(96,92,168,.08);
        border: 1px solid rgba(96,92,168,.07);
        margin: 0 24px 32px;
        overflow: hidden;
    }
    .form-card-header {
        padding: 18px 26px 16px;
        border-bottom: 1px solid #f0eef9;
        background: #faf9ff;
        display: flex; align-items: center; gap: 10px;
    }
    .form-card-title {
        font-size: 14px; font-weight: 700; color: #1e1b3a;
        display: flex; align-items: center; gap: 10px;
    }
    .form-card-title .dot {
        width: 9px; height: 9px;
        background: linear-gradient(135deg, #605ca8, #8b87d4);
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(96,92,168,.15);
    }
    .form-card-body { padding: 28px 30px; }

    /* ── FORM SECTIONS ── */
    .form-section-label {
        font-size: 11px; font-weight: 700; color: #9b97c5;
        text-transform: uppercase; letter-spacing: .8px;
        margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .form-section-label::after {
        content: ''; flex: 1; height: 1px; background: #f0eef9;
    }

    /* ── INPUT FIELDS ── */
    .field-group { margin-bottom: 18px; }
    .field-group label {
        font-size: 12px; font-weight: 700; color: #4a4690;
        margin-bottom: 6px; display: flex; align-items: center; gap: 7px;
    }
    .field-group label i { color: #9b97c5; font-size: 12px; }
    .field-group .form-control {
        border: 1.5px solid #e5e2f5 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13px !important;
        color: #2d2b4e !important;
        background: #faf9ff !important;
        transition: border-color .2s, box-shadow .2s !important;
    }
    .field-group .form-control:focus {
        border-color: #605ca8 !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.12) !important;
        background: #fff !important;
        outline: none !important;
    }
    .field-group .form-control::placeholder { color: #c0bdd8 !important; }

    /* ── PASSWORD WRAPPER ── */
    .input-password-wrap { position: relative; }
    .input-password-wrap .form-control { padding-right: 42px !important; }
    .toggle-password {
        position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: #9b97c5; cursor: pointer; padding: 0;
        font-size: 13px; transition: color .15s;
    }
    .toggle-password:hover { color: #605ca8; }

    /* ── PERMISSION CARD ── */
    .permission-box {
        background: #faf9ff;
        border: 1.5px solid #e5e2f5;
        border-radius: 14px;
        overflow: hidden;
    }
    .permission-box-header {
        padding: 12px 16px;
        background: #f0eef9;
        border-bottom: 1px solid #e5e2f5;
        display: flex; align-items: center; justify-content: space-between;
    }
    .permission-box-title { font-size: 12px; font-weight: 700; color: #4a4690; }
    .select-all-wrap {
        display: flex; align-items: center; gap: 7px;
        font-size: 12px; font-weight: 600; color: #605ca8; cursor: pointer;
    }
    .select-all-wrap input { cursor: pointer; accent-color: #605ca8; }
    .permission-list {
        padding: 12px 16px;
        max-height: 220px;
        overflow-y: auto;
        display: flex; flex-direction: column; gap: 4px;
    }
    .permission-list::-webkit-scrollbar { width: 5px; }
    .permission-list::-webkit-scrollbar-track { background: transparent; }
    .permission-list::-webkit-scrollbar-thumb { background: #d5d0f5; border-radius: 10px; }
    .perm-item {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 10px; border-radius: 8px;
        cursor: pointer; transition: background .15s;
    }
    .perm-item:hover { background: #ede9fb; }
    .perm-item input[type="checkbox"] { accent-color: #605ca8; width: 15px; height: 15px; cursor: pointer; }
    .perm-item label { font-size: 13px; color: #2d2b4e; font-weight: 500; cursor: pointer; margin: 0; }
    .perm-loading { padding: 20px; text-align: center; color: #9b97c5; font-size: 13px; }

    /* ── FOOTER ACTIONS ── */
    .form-footer {
        padding: 20px 30px;
        border-top: 1px solid #f0eef9;
        background: #faf9ff;
        display: flex; justify-content: flex-end; gap: 12px;
    }
    .btn-cancel-form {
        background: #f0eef9; color: #7874b0; border: none;
        border-radius: 10px; padding: 11px 22px;
        font-size: 13px; font-weight: 600; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
        transition: background .15s;
    }
    .btn-cancel-form:hover { background: #e5e2f5; color: #605ca8; text-decoration: none; }
    .btn-save-form {
        background: linear-gradient(135deg, #605ca8, #4a4690);
        color: #fff; border: none; border-radius: 10px;
        padding: 11px 28px; font-size: 13px; font-weight: 700;
        cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 14px rgba(96,92,168,.35);
        transition: all .2s;
    }
    .btn-save-form:hover { box-shadow: 0 6px 20px rgba(96,92,168,.45); transform: translateY(-1px); }
    .btn-save-form:disabled { opacity: .6; cursor: not-allowed; transform: none; }
</style>
@stop

@section('content')
<div style="padding-bottom: 10px;">

    <!-- PAGE HEADER -->
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-users-cog"></i> Admin Panel</div>
            <h1>Tambah User Baru</h1>
            <p>Isi informasi akun dan atur hak akses pengguna</p>
        </div>
        <a href="{{ route('admin.user.index') }}" class="btn-back-user">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- FORM CARD -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-title">
                <div class="dot"></div>
                Informasi Pengguna
            </div>
        </div>

        <div class="form-card-body">
            <div class="row">

                <!-- LEFT: Fields -->
                <div class="col-md-6" style="padding-right: 32px; border-right: 1px solid #f0eef9;">

                    <div class="form-section-label"><i class="fas fa-id-card"></i> Data Akun</div>

                    <div class="field-group">
                        <label><i class="fas fa-user"></i> Username</label>
                        <input type="text" id="username" class="form-control" placeholder="Masukkan username">
                    </div>

                    <div class="field-group">
                        <label><i class="fas fa-address-card"></i> Nama Lengkap</label>
                        <input type="text" id="name" class="form-control" placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="field-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Masukkan email">
                    </div>

                    <div class="field-group">
                        <label><i class="fas fa-key"></i> Password</label>
                        <div class="input-password-wrap">
                            <input type="password" id="password" class="form-control" placeholder="Masukkan password">
                            <button type="button" class="toggle-password" onclick="togglePass()">
                                <i class="fas fa-eye" id="passIcon"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- RIGHT: Permissions -->
                <div class="col-md-6" style="padding-left: 32px;">

                    <div class="form-section-label"><i class="fas fa-shield-alt"></i> Hak Akses</div>

                    <div class="permission-box">
                        <div class="permission-box-header">
                            <span class="permission-box-title"><i class="fas fa-list-check"></i> Pilih Permission</span>
                            <label class="select-all-wrap">
                                <input type="checkbox" id="selectAllCheck"> Pilih Semua
                            </label>
                        </div>
                        <div class="permission-list" id="permission">
                            <div class="perm-loading"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('admin.user.index') }}" class="btn-cancel-form">
                <i class="fas fa-times"></i> Batal
            </a>
            <button class="btn-save-form" id="btn-save-user">
                <i class="fas fa-save"></i> Simpan User
            </button>
        </div>
    </div>

</div>
@stop

@section('scripts')
<script>
    $(document).ready(function () {
        getPermission();
        $('body').addClass('sidebar-collapse');
        $('#side_user_management').addClass('menu-open');

        $('#selectAllCheck').click(function () {
            $('#permission input[type="checkbox"]').prop('checked', this.checked);
        });

        $('#btn-save-user').click(function () {
            var username   = $('#username').val().trim();
            var name       = $('#name').val().trim();
            var email      = $('#email').val().trim();
            var password   = $('#password').val();
            var permission = [];

            $('#permission input:checked').each(function () {
                permission.push($(this).val());
            });

            if (!username || !name || !email || !password || permission.length === 0) {
                alert('Harap lengkapi semua field dan pilih minimal satu permission.');
                return false;
            }

            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            $.post("{{ route('admin.user.store') }}", {
                _token:     "{{ csrf_token() }}",
                username:   username,
                name:       name,
                email:      email,
                password:   password,
                permission: permission
            }, 
            // ubah agar tidak menggunakan toastr, tapi menggunakan alert biasa
            function (data) {
                if (data.status === 'success') {
                    alert('User berhasil ditambahkan.');
                    setTimeout(function(){ window.location.href = "{{ route('admin.user.index') }}"; }, 800);
                } else {
                    alert(data.message || 'Gagal menyimpan user.');
                    btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan User');
                }
            }).fail(function () {
                alert('Terjadi kesalahan. Coba lagi.');
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan User');
            });
        });
    });

    function getPermission() {
        $.get("{{ route('admin.user.getPermissions') }}", function (data) {
            var html = '';
            $.each(data.permissions, function (key, value) {
                html += '<div class="perm-item">';
                html += '<input type="checkbox" value="' + value.id + '" id="perm_' + value.id + '">';
                html += '<label for="perm_' + value.id + '">' + value.name + '</label>';
                html += '</div>';
            });
            $('#permission').html(html || '<div class="perm-loading">Tidak ada permission tersedia.</div>');
        }).fail(function () {
            $('#permission').html('<div class="perm-loading" style="color:#c0392b;"><i class="fas fa-exclamation-circle"></i> Gagal memuat permission.</div>');
        });
    }

    function togglePass() {
        var input = $('#password');
        var icon  = $('#passIcon');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    }
</script>
@stop