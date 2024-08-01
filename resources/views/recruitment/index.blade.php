@extends('layouts.display_mobile')
@section('header')
<div class="page-breadcrumb" style="padding: 7px">
    <div class="row align-items-center">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="page-title mb-0 p-0">{{$title}}<span class="text-purple"> </span></h3>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="overlay-wrapper" id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%;display: none; z-index: 30001;">
    <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i></div>
</div>
<style type="text/css">
    select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
        .select2-selection {
            background: #eee;
            box-shadow: none;
        }
        .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
            display: none;
        }
    }
    .form-control[readonly]{
        background-color: #d3d3d3!important;
        opacity: 1;
    }
</style>
<div class="container-fluid" style="">
    <section class="content" style="padding: 10px;">
        <div class="row">
            <form class="form-horizontal" id="formParticipant">
            <div class="mt-2 mb-5" >
                <center><h4 class="text-center" >REKRUTMEN PT. YMPI</h4></center>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-12 mb-2">
                <label>No. KTP</label>
                <input type="text" class="form-control numeric" autocomplete="off" maxlength="16" placeholder="Nomor KTP" name="card_id" id="card_id">
                <div class="text-danger fw-bold invalid card_id"></div>
            </div>

            <div class="col-xs-12 col-md-12 col-lg-12 mb-2 identity_full" style="display:none;">
                <label>Nama</label>
                <input type="text" class="form-control" autocomplete="off" placeholder="Nama" name="name" id="name">
                <div class="text-danger fw-bold invalid name"></div>
            </div>

            <div class="col-xs-12 col-md-12 col-lg-12 mb-2 identity_full" style="display:none;">
                <label>Tempat, Tanggal Lahir</label>
                <input type="text" class="form-control mb-1" autocomplete="off" placeholder="Tempat" name="birth_place" id="birth_place">
                <div class="text-danger fw-bold invalid birth_place"></div>
                <input type="text" class="form-control datepicker" placeholder="Tanggal Lahir" name="birth_date" id="birth_date">
                <div class="text-danger fw-bold invalid birth_date"></div>
            </div>

            <div class="col-xs-12 col-md-12 col-lg-12 mb-2 identity_full" style="display:none;">
                <label>Alamat</label>
                <textarea type="text" class="form-control" autocomplete="off" placeholder="Alamat" name="address" id="address"></textarea>
                <div class="text-danger fw-bold invalid address"></div>
            </div>

            <div class="col-xs-12 col-md-12 col-lg-12 mb-2 identity_full" style="display:none;">
                <label>No. Telepon</label>
                <input type="text" class="form-control mb-1 numeric" autocomplete="off" placeholder="No. Telepon" name="phone" id="phone">
                <div class="text-danger fw-bold invalid phone"></div>
            </div>

            <div class="col-xs-12 col-md-12 col-lg-12 mb-2 identity_full" style="display:none;">
                <label>Pendidikan</label>
                <select class="form-control select2 mb-1" name="education" id="education" style="width: 100%;height: 100%;"></select>
                <div class="text-danger fw-bold invalid education"></div>
            </div>

            <div class="col-sm-12 col-xs-12 mt-3 identity_full" style="display:none;">
                <button class="text-center btn btn-success" style="width:100%;" id="submit">Submit</button>   
            </div>

            </form>

            <div class="col-sm-12 col-xs-12 mt-3" >
                <a class="text-center btn btn-success" style="width:100%;" id="check">Check</a>   
            </div>

        </div>
    </section>
</div>
@stop

@section('scripts')
<script>        
    let optionPendidikan = [
        {'id':'SMA/SMK/MA', 'text':'SMA/SMK/MA'},
        {'id':'D3', 'text':'D3'},
        {'id':'D4/S1', 'text':'D4/S1'},
        // {'id':'S2', 'text':'S2'},
        // {'id':'S3', 'text':'S3'},
    ];

    $('document').ready(function () {
        $('#card_id').focus();
        $('#education').prepend("<option value=''></option>").select2({
            placeholder: 'Pendidikan',
            data: optionPendidikan,
            autoClear: true,
            // theme: 'bootstrap3',
        });
        
    });

    $(document).on('click','#check', function(e) {
        $('.invalid').html('');
        $("#loading").show();
        $("#check").attr('disabled',true);
        let card_id = $("#card_id").val();

        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            url: "{{ url('fetch/ympi_recruitment/participant') }}",
            data: {card_id:card_id},
            success: function(result) {
                $('#loading').hide();
                if (result.status==true) {
                    let thisData = result.data;
                    $(`#card_id`).attr('readonly',true);

                    if(thisData==null){
                        $(`#name`).val('');
                        $(`#birth_place`).val('');
                        $(`#birth_date`).val('');
                        $(`#address`).val('');
                        $(`#phone`).val('');
                        $(`#education`).val('');
                        $(`#test_date`).val('');
                        $('.identity_full').show();
                        $('#check').hide();
                        $('#submit').show();
                    } else {
                        // $(`#name`).val(thisData.name);
                        // $(`#birth_place`).val(thisData.birth_place);
                        // $(`#birth_date`).val(thisData.birth_date);
                        // $(`#address`).val(thisData.address);
                        // $(`#phone`).val(thisData.phone);
                        // $(`#education`).val(thisData.education).trigger('change');
                        // $(`#test_date`).val(thisData.test_date);

                        let redirect = "{{ url('index/ympi_recruitment/kraepelin') }}";
                        window.location.href = redirect;
                    }
                } else {
                    $('#check').show();
                    $('#submit').hide();
                    $("#check").removeAttr('disabled');
                    errorAjax(result.message);
                }
            },
            error: function(xhr, status, error) {
                $('#loading').hide();
                $("#check").removeAttr('disabled');
                let message = xhr.responseJSON.message; 
                errorAjax(message);
            }
        });

    });


    $(document).on('submit','#formParticipant', function(e) {
        e.preventDefault();
        $('.invalid').html('');
        $("#loading").show();
        let formData = new FormData($('#formParticipant')[0]);

        $.ajax({
            method: "POST",
            url: "{{ url('input/ympi_recruitment/participant') }}",
            cache: false,
            processData: false,
            contentType: false,
            data: formData,
            success: function(result) {
                if (result.status) {
                    $('#loading').hide();
                    $(`#card_id`).attr('readonly',false);
                    openSuccessGritter(result.message);
                    let redirect = "{{ url('index/ympi_recruitment/kraepelin') }}";
                    window.location.href = redirect;
                } else {
                    $('#loading').hide();
                    errorAjax(result.message);
                }
            },
            error: function(xhr, status, error) {
                $('#loading').hide();
                let message = xhr.responseJSON.message;
                errorAjax(message);
            }
        });

    });
</script>
@endsection