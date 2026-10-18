@extends('layouts.master')
@section('styles')
<!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="{{ url('css/bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6fb;
        }

        body.sidebar-mini .main-sidebar {
            display: none !important;
        }

        body.sidebar-mini .main-header,
        body.sidebar-mini .content-wrapper {
            margin-left: 0 !important;
        }

        #toggle-sidebar {
            display: none !important;
        }

        .hold-comment-shell {
            min-height: calc(100vh - 57px);
            display: grid;
            place-items: center;
            padding: 32px 20px !important;
            background: #f4f6fb;
        }

        .hold-comment-card {
            width: min(100%, 680px);
            padding: 32px;
            text-align: center;
            background: #fff;
            border: 1px solid #e4e9f2;
            border-radius: 14px;
            box-shadow: 0 12px 32px rgba(33, 51, 86, .08);
        }

        .comment-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin: 0 auto 26px;
        }

        .comment-brand-mark {
            position: relative;
            display: grid;
            place-items: center;
            width: 58px;
            height: 58px;
            flex: 0 0 58px;
            color: #3569e8;
            background: #edf2ff;
            border: 1px solid #d8e3ff;
            border-radius: 17px;
        }

        .comment-brand-mark > .fa-comment-dots {
            font-size: 31px;
        }

        .comment-brand-mark > .fa-hand-paper {
            position: absolute;
            right: -7px;
            bottom: -5px;
            padding: 5px;
            color: #fff;
            font-size: 13px;
            background: #e89b16;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .comment-brand-mark.reject {
            color: #d94b58;
            background: #fff0f1;
            border-color: #ffd9dc;
        }

        .comment-brand-copy {
            text-align: left;
        }

        .comment-eyebrow {
            margin: 0 0 3px;
            color: #71809a;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .comment-title {
            margin: 0;
            color: #2e63dc;
            font-size: 30px;
            font-weight: 700;
        }

        .comment-brand.reject .comment-title {
            color: #d94b58;
        }

        .comment-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin: 22px 0 28px;
            border-top: 1px solid #e8edf5;
            border-bottom: 1px solid #e8edf5;
        }

        .comment-detail {
            min-width: 0;
            padding: 15px 12px;
            text-align: left;
        }

        .comment-detail + .comment-detail {
            border-left: 1px solid #e8edf5;
        }

        .comment-detail-label {
            display: block;
            margin-bottom: 5px;
            color: #71809a;
            font-size: 12px;
            font-weight: 600;
        }

        .comment-detail-value {
            display: block;
            color: #202b3c;
            font-size: 16px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .comment-prompt {
            margin: 0 0 12px;
            color: #53627a;
            font-size: 14px;
        }

        .comment-input-wrap {
            width: min(100%, 500px);
            margin: 0 auto;
        }

        .comment-textarea {
            display: block;
            box-sizing: border-box;
            width: 100%;
            min-height: 140px;
            padding: 13px 14px;
            color: #202b3c;
            font: inherit;
            line-height: 1.5;
            resize: vertical;
            background: #fbfcfe;
            border: 1px solid #d8e0eb;
            border-radius: 9px;
            outline: none;
            transition: border-color .18s, box-shadow .18s, background .18s;
        }

        .comment-textarea:focus {
            background: #fff;
            border-color: #5b7fe5;
            box-shadow: 0 0 0 3px rgba(53, 105, 232, .12);
        }

        .comment-submit {
            min-width: 126px;
            margin-top: 14px;
            padding: 10px 18px;
            border-radius: 8px;
        }

        .comment-submit:disabled {
            cursor: not-allowed;
            opacity: .55;
        }

        @media (max-width: 575px) {
            .hold-comment-shell {
                padding: 20px 12px !important;
            }

            .hold-comment-card {
                padding: 25px 18px;
            }

            .comment-title {
                font-size: 25px;
            }

            .comment-details {
                grid-template-columns: 1fr;
            }

            .comment-detail + .comment-detail {
                border-top: 1px solid #e8edf5;
                border-left: 0;
            }
        }
    </style>
@stop
@section('header')
@stop
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content hold-comment-shell">
        <div id="loading" style="margin: 0; padding: 0; position: fixed; inset: 0; width: 100%; height: 100%; background-color: rgba(25, 35, 55, .55); z-index: 30001; display: none;">
            <p style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-weight: bold; font-size: 20px; white-space: nowrap;">
                <i class="fas fa-circle-notch fa-spin"></i> Loading, Please Wait . . .
            </p>
        </div>
        <div class="hold-comment-card">
        @if($status == 'Already Approved')

            <!-- Judul Konfirmasi -->
            <i class="fas fa-times-circle mb-4" style="font-size: 70px; color: #f26565;"></i>
            <h1 class="text-2xl sm:text-3xl font-extrabold mb-2" style="color: #ef4444;">
                Already Approved
            </h1>
        @elseif(strpos($status, 'approval_') !== false)

            <!-- Judul Konfirmasi -->
            <i class="fas fa-check-circle mb-4" style="font-size: 70px; color: #16a34a;"></i>
            <h1 class="text-2xl sm:text-3xl font-extrabold mb-2" style="color: #15803d;">
                Approval Success!
            </h1>
        @elseif($status == 'hold_view')
            <!-- Judul Konfirmasi -->
             @if($approval->approver_id == 'Already Approve')
            <i class="fas fa-times-circle mb-4" style="font-size: 70px; color: #f26565;"></i>
            <h1 class="text-2xl sm:text-3xl font-extrabold mb-2" style="color: #ef4444;">
                Already Approved
            </h1>
            @else
            <div class="comment-brand">
                <span class="comment-brand-mark" aria-hidden="true">
                    <i class="fas fa-comment-dots"></i>
                    <i class="fas fa-hand-paper"></i>
                </span>
                <span class="comment-brand-copy">
                    <span class="comment-eyebrow">Molding Approval</span>
                    <h1 class="comment-title">Hold &amp; Comment</h1>
                </span>
            </div>
            @endif
        @elseif($status == 'reject_view')
            <!-- Judul Konfirmasi -->
             @if($approval->approver_id == 'Already Approve')
            <i class="fas fa-times-circle mb-4" style="font-size: 70px; color: #f26565;"></i>
            <h1 class="text-2xl sm:text-3xl font-extrabold mb-2" style="color: #ef4444;">
                Already Approved
            </h1>
            @else
            <div class="comment-brand reject">
                <span class="comment-brand-mark reject" aria-hidden="true">
                    <i class="fas fa-comment-dots"></i>
                    <i class="fas fa-hand-paper"></i>
                </span>
                <span class="comment-brand-copy">
                    <span class="comment-eyebrow">Molding Approval</span>
                    <h1 class="comment-title">Reject &amp; Comment</h1>
                </span>
            </div>
            @endif
        @endif
        <!-- Detail Penting -->
        <div class="comment-details">
            <div class="comment-detail">
                <span class="comment-detail-label">Form Number</span>
                <span class="comment-detail-value">{{ $data_form_molding->form_number }}</span>
            </div>
            <div class="comment-detail">
                <span class="comment-detail-label">Molding Name / Tool</span>
                <span class="comment-detail-value">{{ $data_form_molding->fixed_asset_name }}</span>
            </div>
        </div>

        @if($status == 'hold_view')
            @if($approval->approver_id != 'Already Approve')
            <div id="hold_view">
            <!-- Judul Konfirmasi -->
                <p class="comment-prompt">Please write your comment below</p>
                <div class="comment-input-wrap">
                    <textarea name="comment" id="comment_hold" rows="5" class="comment-textarea" placeholder="Write your comment"></textarea>
                    <button type="button" class="btn btn-primary comment-submit" id="submit_hold" disabled><i class="fas fa-paper-plane"></i> Submit</button>
                </div>
            </div>  
            @endif
        @elseif($status == 'reject_view')
            @if($approval->approver_id != 'Already Approve')
            <div id="reject_view">
            <!-- Judul Konfirmasi -->
                <p class="comment-prompt">Please write your comment below</p>
                <div class="comment-input-wrap">
                    <textarea name="comment" id="comment_reject" rows="5" class="comment-textarea" placeholder="Write your comment"></textarea>
                    <button type="button" class="btn btn-primary comment-submit" id="submit_reject" disabled><i class="fas fa-paper-plane"></i> Submit</button>
                </div>
            </div>  
            @endif
        @endif

    </div>
    </section>
@endsection
@section('scripts')
<script src="{{ url('js/sweetalert2.min.js') }}"></script>
<script src="{{ url('js/toastr.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.os-content').hide();
        
        if($('#comment_hold').length > 0) {
            $('#comment_hold').focus();
        }
        
        $('#comment_hold').on('input', function(e) {
            if($(this).val().length > 0) {
                $('#submit_hold').prop('disabled', false);
            } else {
                $('#submit_hold').prop('disabled', true);
            }
        });

        $('#submit_hold').on('click', function() {
            if(confirm('Are you sure want to submit this comment?')) {
                $('#loading').show();
                //ajax get
                $.ajax({
                    url: '{{ url("approval/diagnose_molding/hold_comment/$approval->approver_id/$data_form_molding->form_number") }}',
                    type: 'GET',
                    data: {
                        comment: $('#comment_hold').val(),
                    },
                    success: function(response) {
                        if(response.status) {
                            $('#hold_view').html('<h1 class="text-2xl sm:text-3xl font-extrabold mb-2" style="color: #15803d;"><i class="fas fa-check"></i> Comment Submitted Successfully</h1><br>');
                            toastr.success('Comment submitted successfully');
                            audio_success.play();
                        } else {
                            toastr.error('Failed to submit comment');
                            audio_error.play();
                        }
                        $('#loading').hide();
                    },
                    error: function(response) {
                        toastr.error('Failed to submit comment');
                        audio_error.play();
                        $('#loading').hide();
                    }
                });
            }
        });

        if($('#comment_hold').length > 0) {
            $('#comment_hold').focus();
        }
        
        $('#comment_hold').on('input', function(e) {
            if($(this).val().length > 0) {
                $('#submit_hold').prop('disabled', false);
            } else {
                $('#submit_hold').prop('disabled', true);
            }
        });

        $('#comment_reject').on('input', function() {
            $('#submit_reject').prop('disabled', $(this).val().trim().length === 0);
        });

        $('#submit_reject').on('click', function() {
            if(confirm('Are you sure want to submit this comment?')) {
                $('#loading').show();
                //ajax get
                $.ajax({
                    url: '{{ url("approval/diagnose_molding/reject_comment/$approval->approver_id/$data_form_molding->form_number") }}',
                    type: 'GET',
                    data: {
                        comment: $('#comment_reject').val(),
                    },
                    success: function(response) {
                        if(response.status) {
                            $('#reject_view').html('<h1 class="text-2xl sm:text-3xl font-extrabold mb-2" style="color: #15803d;"><i class="fas fa-check"></i> Comment Submitted Successfully</h1><br>');
                            toastr.success('Comment submitted successfully');
                            audio_success.play();
                        } else {
                            toastr.error('Failed to submit comment');
                            audio_error.play();
                        }
                        $('#loading').hide();
                    },
                    error: function(response) {
                        toastr.error('Failed to submit comment');
                        audio_error.play();
                        $('#loading').hide();
                    }
                });
            }
        });
    });

    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');
    var audio_success = new Audio('{{ url("sounds/success.mp3") }}');
</script>
@endsection
