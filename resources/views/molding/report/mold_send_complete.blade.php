@extends('layouts.master')
@section('stylesheets')
<!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="{{ url('css/bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fb;
        }
    </style>
@stop
@section('header')
@stop
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content" style="padding: 10px">
        <div id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none;">
            <p style="position: absolute; top: 50%; left: 60%; transform: translate(-50%, -50%); color: white; font-weight: bold; font-size: 30px;">
                <i class="fas fa-circle-notch fa-spin"></i> Loading, Please Wait . . .
            </p>
        </div>
        <div class="max-w-md w-full bg-white shadow-2xl rounded-xl p-8 sm:p-10 text-center">
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
        @elseif($status == 'reject_view')
            <!-- Judul Konfirmasi -->
             @if($approval->approver_id == 'Already Approve')
            <i class="fas fa-times-circle mb-4" style="font-size: 70px; color: #f26565;"></i>
            <h1 class="text-2xl sm:text-3xl font-extrabold mb-2" style="color: #ef4444;">
                Already Approved
            </h1>
            @else
            <h1 class="text-2xl sm:text-3xl font-extrabold mb-2" style="color: #ef4444;">
                <i class="fas fa-hand-paper"></i><i class="fas fa-comment-dots"></i> Reject & Comment
            </h1>
            @endif
        @endif
        <!-- Detail Penting -->
        <div style="padding-left: 6px; padding-right: 6px;">
            
            <!-- Data Item: Nama Molding -->
            <div>
                <p class="text-base font-medium text-gray-500">Molding Name / Tool</p>
                <p class="text-lg font-bold text-gray-800" style="font-weight: bold;">{{ $data_form_molding->fixed_asset_name }}</p> <br>
            </div>

        </div>

        @if($status == 'hold_view')
            @if($approval->approver_id != 'Already Approve')
            <div id="hold_view">
            <!-- Judul Konfirmasi -->
                <p class="text-base font-medium text-gray-500">Please write your comment below :</p>
                <textarea name="comment" id="comment_hold" cols="30" rows="10" style="width: 40%; height: 100px; padding: 10px;" class="border border-gray-700 rounded" placeholder="Write your comment"></textarea> <br>
                <button type="button" class="btn btn-primary" id="submit_hold"><i class="fas fa-paper-plane"></i> Submit</button>
            </div>  
            @endif
        @elseif($status == 'reject_view')
            @if($approval->approver_id != 'Already Approve')
            <div id="reject_view">
            <!-- Judul Konfirmasi -->
                <p class="text-base font-medium text-gray-500">Please write your comment below :</p>
                <textarea name="comment" id="comment_reject" cols="30" rows="10" style="width: 40%; height: 100px; padding: 10px;" class="border border-gray-700 rounded" placeholder="Write your comment"></textarea> <br>
                <button type="button" class="btn btn-primary" id="submit_reject"><i class="fas fa-paper-plane"></i> Submit</button>
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
                    url: '{{ url("approval/molding/reject_comment/$approval->approver_id/$data_form_molding->id") }}',
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
    });

    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');
    var audio_success = new Audio('{{ url("sounds/success.mp3") }}');
</script>
@endsection
