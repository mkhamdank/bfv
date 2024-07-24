@extends('layouts.master')
@section('styles')
    <link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('css/buttons.dataTables.min.css') }}">
    <style type="text/css">
        .table-bordered {
            border: 1px solid #000000 !important;
        }
    </style>
@stop
@section('header')
    <!-- <section class="content-header">
     <h1>
      {{ $title }}
     </h1>
     <ol class="breadcrumb">
      <li>
       <a href="{{ url('index/workshop/check_molding_vendor/create') }}" class="btn btn-primary" style="color: white"><i class="fa fa-plus"></i>Audit Molding</a>
      </li>
     </ol>
    </section> -->
@stop
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    <div class="overlay-wrapper" id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%;display: none; z-index: 30001;">
        <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i></div>
    </div>

    <section class="content" style="padding: 10px">
        <div class="row">
            <div class="col-md-10">
                <h2>
                    Recruitment 
                </h2>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3 class="rounded" style="text-align: center; margin-bottom: 0px; background-color: #a283dbf5; padding : 7px 0 7px 0;color: #000000!important;">Setting Kraepelin Test</h3>
                <br>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="custom-control custom-switch">
                            <label style="font-size: 18px;">Status Opening Test : </label> <label class="fw-bold status_opening" style="font-size: 20px;"></label>
                            <br><br>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="opening_kraepelin_test" id="opening_kraepelin_test">
                            <label style="cursor:pointer;" class=""><span class="btn-sm rounded status_button"></span></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <h3 class="rounded" style="text-align: center; margin-bottom: 0px; background-color: #a283dbf5; padding : 7px 0 7px 0;color: #000000!important;">Hasil Tes Kraepelin</h3>
                <br>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Tanggal Tes :</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right daterangepicker2" name="date" id="date">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <span>&nbsp;</span>
                <div class="form-group" style="margin-top:9px;">
                    <button class="btn btn-primary" id="search"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
            <div class="col-md-12 ">
                <table class="table table-stripped table-bordered" id="tableKraepelin" style="width: 100%; margin-bottom: 0px;overflow-y: scroll;"></table>
            </div>
        </div>
        
    </section>

    <div class="modal modal-default fade" id="modalTes" data-bs-keyboard="false" data-bs-backdrop="static" >
        <div class="modal-dialog modal-lg" style="max-width: 1200px !important;">
            <div class="modal-content">
                <div class="modal-header bg-purple">
                    <h4 class="modal-title text-left">Rekrutmen</h4>
                    <a href="javascript:;" data-bs-dismiss="modal"><span aria-hidden="true" style="color:white;font-weight: bold;">&times;</span></a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 hasil_tes"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmSetting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title" >Setting Kraepelin Test</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body content_body bodySetting">
                    <h4>Anda yakin membuka tes ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tidak</button>
                    <a id="submitSetting" style="margin-left:30px;" href="javascript:;" type="button" class="btn btn-primary">Ya</a>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script src="{{ url('js/highcharts.js') }}"></script>
<script src="{{ url('ckeditor/ckeditor.js') }}"></script>
<script>
    const openSuccessGritter = (title, message) => {
        jQuery.gritter.add({
            title: title,
            text: message,
            class_name: 'growl-success',
            image: '{{ url("images/image-screen.png") }}',
            sticky: false,
            time: '3000'
        });
    }
    const openErrorGritter = (title, message) => {
        jQuery.gritter.add({
            title: title,
            text: message,
            class_name: 'growl-danger',
            image: '{{ url("images/image-stop.png") }}',
            sticky: false,
            time: '3000'
        });
    }
    const errorAjax = (message) => {
        if($.isArray(message)){
            openErrorGritter('Error!',message.join(', '));
        } else {
            if(typeof message === 'object'){
                Object.keys(message).forEach(function(key) {
                    newKey = key.replace('.','_');
                    $(`.${newKey}`).html(message[key]);
                });
                let firstObject = Object.entries(message)[0][0];
                if (document.getElementById(firstObject)) {
                    $('html, body').animate({scrollTop: $(`#${firstObject}`).offset().top}, 1500);
                }
            } else {
                openErrorGritter('Error!',message);
            }
        }
    }
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('input.daterangepicker2').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            $(this).data('daterangepicker').hide();
    });

    $('.daterangepicker2').daterangepicker({
        locale: { format: 'YYYY-MM-DD', },
        todayHighlight: true,
        autoUpdateInput: true,
        autoclose: true,
        startDate: moment().subtract(30, 'days').format('YYYY-MM-DD'), 
        endDate: moment().format('YYYY-MM-DD'),
    });


    const tableKraepelin = (step=1) => {
        let date = $('#date').val();
        let myTableName = 'tableKraepelin';
        let myTable = $(`#${myTableName}`).DataTable({
            processing: true,
            serverSide: false,
            ordering: true,
            destroy: true,
            searching: true,
            footer: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('index/ympi_recruitment_monitoring')}}",
                type: 'GET',
                data:{date:date},
                async: true,
                error: function (xhr, error, code) {
                    //autoreload table jika error sebanyak step
                    if(step < 5){
                        step += 1;
                        tableKraepelin(step)
                    }
                }
            },
            columns: [
                { data: 'DT_RowIndex', title: 'No', orderable: false, searchable: false },
                { data: 'card_id', title: 'No. KTP', className: "myFilter"},
                { data: 'name', title: 'Nama', className: "myFilter"},
                { data: 'age', title: 'Usia', className: "myFilter"},
                { data: 'test_date', title: 'Tanggal Tes', className: "myFilter"},
                { data: 'education', title: 'Pendidikan', className: "myFilter"},
                { data: 'test_done', title: 'Status Tes', render : function ( data, type, row ) {
                        let action_ = ``;
                        if(row.test_done == true){
                            action_ = `<center><span class="btn-sm rounded btn-success">Selesai</span></center>`;
                        } else {
                            action_ = `<center><span class="btn-sm rounded btn-danger">Belum</span></center>`;
                        }
                        return action_;
                    }
                },
                { data: 'action', title: 'Action', render : function ( data, type, row ) {
                        let action_ = ``;
                        if(row.test_done == true){
                            action_ = `<span class="btn-sm rounded btn-info result mr-2" participant_id="${row.participant_id}" test_date="${row.test_date}" test_type="${row.test_type}" style="cursor:pointer;" title="Show Result">Show</span>`;
                            action_ += `<span class="btn-sm rounded btn-success result_download" participant_id="${row.participant_id}" test_date="${row.test_date}" test_type="${row.test_type}" style="cursor:pointer;" title="Download"><i class="fa fa-arrow-down"></i></span>`;
                        }
                        return action_;
                    }
                },
            ],
            tfoot: $(`#${myTableName} tfoot`),
            scrollY: true,
            scrollX: true,
            'buttons': {
                buttons:[
                {
                    extend: 'pageLength',
                    className: 'btn btn-default',
                },
                ]
            },
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': false,
            'order': [],
            'info': true,
            'autoWidth': true,
            "sPaginationType": "full_numbers",
            "bJQueryUI": true,
            "bAutoWidth": false,
            "processing": true,
            "destroy": true,
        });

        $(`#${myTableName}_wrapper thead th`).each(function () {
            let title = $(this).text();
            let thisWidth = $(this).width();
            if ($(this).hasClass('myFilter')) {
                if(thisWidth < 60){ thisWidth = 60; }
                $(this).html(title+`<br> <input type="text" style="width:${thisWidth}px;" class="col-search-input form-control form-control-sm" placeholder="Cari" />`);
            }
        });
        myTable.columns().every(function () {
            let table = this;
            $('input', this.header()).on('keyup change', function () {
                if (table.search() !== this.value) {
                    table.search(this.value).draw();
                }
            });
        });
    }

    const showResult = (test_date, participant_id, test_type, type='view') => {
        $('.invalid').html('');
        $("#loading").show();
        $('.hasil_tes').html('');
        let url = '';

        if(test_type=='kraepelin'){
            url = "{{ url('fetch/ympi_recruitment_monitoring/kraepelin_result') }}";
        }
        if(url != ''){
            $.ajax({
                method: "GET",
                url: url,
                data: {test_date:test_date, participant_id:participant_id, test_type:test_type, type:type},
                success: function(res) {
                    if (res.status) {
                        $('#loading').hide();
                        $('#modalTes').modal('show');
                        $('.hasil_tes').html(res.data);
                    } else {
                        $('#loading').hide();
                        errorAjax(res.message);
                    }
                },
                error: function(xhr, status, error) {
                    $('#loading').hide();
                    let message = xhr.responseJSON.message;
                    errorAjax(message);
                }
            });
        }
    }

    const downloadResult = (test_date, participant_id, test_type, type='download') => {
        var myHeader = {'X-CSRF-TOKEN': "{{ csrf_token() }}", Accept: "application/json",};
        var winURL = "{{ url('download/ympi_recruitment_monitoring/kraepelin_result')}}";
        var windowoption='resizable=no,height=1,width=1,location=0,menubar=0,scrollbars=0';
        var params = {
            '_token':"{{ csrf_token() }}",
            test_date : test_date, 
            participant_id : participant_id,
            test_type : test_type,
            type:type
        };         

        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", winURL);
        for (var i in params) {
            if (params.hasOwnProperty(i)) {
              var input = document.createElement('input');
              input.type = 'hidden';
              input.name = i;
              input.value = params[i];
              form.appendChild(input);
            }
        }              
        document.body.appendChild(form);                       
        form.submit();                 
        document.body.removeChild(form);           
    }

    const changeOpeningKraepelinTest = () => {
        let opening_kraepelin_test = $("#opening_kraepelin_test").val();
        $('#loading').show();

        $.ajax({
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}", Accept: "application/json",},
            method: "POST",
            url: "{{ url('input/ympi_recruitment_monitoring/change_setting') }}",
            data: {status:opening_kraepelin_test, type:'opening_kraepelin_test'},
            success: function(result) {
                $('#confirmSetting').modal('hide');
                if (result.status) {
                    $('#loading').hide();
                    changeSettingStatus(result.data.status)
                    openSuccessGritter('Success', result.data.message)
                } else {
                    $('#loading').hide();
                    errorAjax(result.message);
                }
            },
            error: function(xhr, status, error) {
                $('#loading').hide();
                $('#confirmSetting').modal('hide');
                let message = xhr.responseJSON.message;
                errorAjax(message);
            }
        });
    }

    const changeSettingStatus = (from='') => {
        let from_ = (from==undefined || from==null) ? '' : from;
        let statusFrom = {
            '' : 'open',
            'close' : 'open',
            'open' : 'close',
        };

        if(statusFrom[from_] == 'open'){
            $('.status_opening').removeClass('text-success').addClass('text-danger').html('OFF');
            $('#opening_kraepelin_test').val(statusFrom[from_]);
            $('.status_button').removeClass('btn-danger').addClass('btn-success').html('OPEN TEST');
        } else {
            $('.status_opening').removeClass('text-danger').addClass('text-success').html('ON');
            $('#opening_kraepelin_test').val(statusFrom[from_]);
            $('.status_button').removeClass('btn-success').addClass('btn-danger').html('CLOSE TEST');
        }
    }

    jQuery(document).ready(function() {
        let statusOpeningTest = "{{ @$statusOpeningTest->status }}";
        tableKraepelin()
        changeSettingStatus(statusOpeningTest)
    });

    $(document).on('click','#search', function(e) {
        tableKraepelin()
    });

    $(document).on('click','.status_button', function(e) {
        let changeStatusTo = $('#opening_kraepelin_test').val();
        if(changeStatusTo == 'open'){
            $('.bodySetting').html('<h4>Anda yakin membuka tes ?</h4>');
        }
        else {
            $('.bodySetting').html('<h4>Anda yakin menutup tes ?</h4>');
        }
        $('#confirmSetting').modal('show');
    });

    $(document).on('click','#submitSetting', function(e) {
        changeOpeningKraepelinTest()
    });

    $(document).on('click','.result', function(e) {
        let test_date = $(this).attr('test_date');
        let participant_id = $(this).attr('participant_id');
        let test_type = $(this).attr('test_type');
        showResult(test_date, participant_id, test_type)
    });

    $(document).on('click','.result_download', function(e) {
        let test_date = $(this).attr('test_date');
        let participant_id = $(this).attr('participant_id');
        let test_type = $(this).attr('test_type');
        downloadResult(test_date, participant_id, test_type, 'download')
    });

</script>
@endsection
