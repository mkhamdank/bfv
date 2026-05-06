@extends('layouts.master')
@section('stylesheets')
    <link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
    <style type="text/css">
        #loading,
        #error {
            display: none;
        }

        table.table-bordered>thead>tr>th {
            color: white;
            background-color: black;
        }

        table.table-bordered>tbody>tr>td {
            color: black;
            background-color: white;
            border: 1px solid black;
        }

        table.table-bordered>tbody>tr>td:hover {
            border-color: #f1de5dff;
        }
        
        #loading {
            display: none;
        }

        div.dataTables_filter {
            float: right !important;
            text-align: right;
        }
    </style>
@stop
@section('header')
@stop
@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content" style="padding: 10px">
        <div id="loading"
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display:none">
            <p style="position: absolute; color: white; top: 45%; left: 35%;">
                <span style="font-size: 40px">Loading, Please Wait . . . <i class="fa fa-spin fa-refresh"></i></span>
            </p>
        </div>

        <div class="row">
            <div class="col-md-10">
                <input type="hidden" id="green">
                <h2>Daftar Form Diagnosa Molding</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Month Range</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="month_range">
                                </div>
                            </div>
                        </div>

                        <table class="table table-hover table-bordered dataTable dtr-inline" style="width: 100%; margin-bottom: 10px" id="tableShot">
                            <thead style="text-align: center">
                                <tr>
                                    <th style="width: 1%">No</th>
                                    <th style="width: 8%">FA Number</th>
                                    <th style="border-right: 1px solid red">Nama Molding</th>
                                    @foreach ($month_range_grouped as $month)
                                        <th style="width: 10%">{{ $month[1] }} ~<br> {{ $month[0] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="bodyTableShot">
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @foreach ($month_range_grouped as $month)
                                        <td></td>
                                    @endforeach
                                </tr>
                            </tfoot> -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal_shot" tabindex="-1" role="dialog" aria-labelledby="modalShotLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <center>
                    <h5 class="modal-title" id="modalShotLabel" style="font-weight: bold;">
                         Riwayat Shot Molding
                    </h5>
                </center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fixed Asset Number</label>
                                <input type="text" class="form-control" id="fa_number" readonly>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Nama Molding</label>
                                <input type="text" class="form-control" id="nama_molding" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Period</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="period" placeholder="Periode" style="text-align: center" readonly value="{{ date('Y-m') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Shot</label>
                                <input type="text" class="form-control" id="shot" placeholder="Masukkan Jumlah Shot">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label><br>
                            <button type="button" class="btn btn-primary" style="width: 100%;" onclick="updateShot()"><i class="fas fa-file-export"></i> Update Shot</button>
                        </div>
                    </div>

                    <table class="table table-bordered" style="width: 100%; margin-bottom: 10px" id="tableShot">
                        <thead>
                        <tr>
                            <th style="width: 2%">No</th>
                            <th style="width: 10%">Tanggal</th>
                            <th style="width: 7%">Jumlah Shot</th>
                            <th style="width: 7%">Total Shot</th>
                            <th style="width: 7%">Input By</th>
                            <th style="width: 5%">Action</th>
                        </tr>
                        </thead>
                        <tbody id="tbodyShot">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ url('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('js/buttons.flash.min.js') }}"></script>
    <script src="{{ url('js/jszip.min.js') }}"></script>
    <script src="{{ url('js/vfs_fonts.js') }}"></script>
    <script src="{{ url('js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('js/buttons.print.min.js') }}"></script>
    <script src="{{ url('js/sweetalert2.min.js') }}"></script>
    <script src="{{ url('js/toastr.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var check_point = [];
        var moldings = <?php echo json_encode($master_molding); ?>;
        var month_range_grouped = <?php echo json_encode($month_range_grouped); ?>;
        var parts = [];
        var part_status = [];
        var part_err = [];
        var num = 1;
        const compressedFiles = [];

        jQuery(document).ready(function() {
            $("#wrapper").toggleClass("toggled");

            getData();

            $('.select2').select2({
                dropdownAutoWidth: true,
                allowClear: true,
                dropdownParent: $('#molding_select')
            });

            $('#month_range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'MM/YYYY'
                },
                ranges: {
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                minDate: moment().subtract(12, 'month').startOf('month'),
                maxDate: moment().endOf('month'),
                opens: 'left',
                drops: 'down'
            }, function(start, end, label) {
                $('#month_range').val(start.format('MM/YYYY') + ' - ' + end.format('MM/YYYY'));
            });
        })

        $(function () {
           var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        })

        function getData() {
            // var params = {
            //     start_date: $('#month_range').val(),
            //     end_date: $('#month_range').val()
            // }

            // tambahkan kolom 3 & 4
            let arr = Object.values(month_range_grouped);

            let month_range_grouped_new = arr.map(row => {
                let date1 = parseDate(row[0]);
                let date2 = parseDate(row[1]);

                return [
                    row[0],
                    row[1],
                    date1,
                    date2
                ];
            });

            var params = {
                start_date: month_range_grouped_new[month_range_grouped_new.length - 1][3].toISOString().split('T')[0],
                end_date: month_range_grouped_new[0][2].toISOString().split('T')[0]
            }
            $.get('{{ url('fetch/diagnose_molding/molding_shot') }}', params, function(result, status, xhr) {
                if (result.status) {
                    // const uniqueWeeks = [...new Set(result.data.map(item => item.week_number))];

                    let data_new = result.data.map(item => {
                        let [y, m, d] = item.create_date.split('-');
                        return {
                            ...item,
                            create_date_obj: new Date(y, m - 1, d) // FIX
                        };
                    });

                    console.table(month_range_grouped_new);
                    console.table(data_new);

                    var body = "";
                    $.each(moldings, function(key, value) {
                        body += "<tr>";
                        body += "<td>" + num + "</td>";
                        body += "<td>" + value.fixed_asset_number + "</td>";
                        body += "<td style='border-right: 1px solid red'>" + value.fixed_asset_name + "</td>";

                        $.each(month_range_grouped_new, function(index, week) {
                            var first_date = week[1];
                            var last_date = week[0];
                            
                            // var firstDate = new Date(first_date);
                            // var lastDate = new Date(last_date);
                            // var formattedFirstDate = firstDate.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' });
                            // var formattedLastDate = lastDate.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' });

                            
                            let hasil = data_new.filter(item => {
                                let itemDate = normalize(item.create_date_obj);

                                // cek apakah masuk salah satu range
                                let endDate = normalize(week[2]);   // kolom ke-3 (Date object)
                                let startDate = normalize(week[3]); // kolom ke-4 (Date object)

                                return itemDate >= startDate && itemDate <= endDate && item.fixed_asset_number === value.fixed_asset_number;
                            });
                            
                            // var data = result.data.filter(item => item.fixed_asset_number === value.fixed_asset_number && item.week_number === week);
                            
                            var cursors = "";
                            var onclick = "";

                            if(index == 0) {
                                cursors = "cursor: pointer;";
                                onclick = "onclick='openModalShot(\""+value.fixed_asset_number+"\", \""+value.fixed_asset_name+"\")'";
                            }
                            
                            if(hasil.length > 0){
                                body += "<td style='" + cursors + " text-align: center; background-color: #9df982ff; font-weight: bold;'>" + hasil[0].total_shot + " / " + hasil[0].accumulative_shot + "</td>";
                            }else{
                                body += "<td style='" + cursors + " text-align: center; background-color: #f67e7eff' " + onclick +">-</td>";
                            }
                        });

                        // const row = 4;
                        // for (let i = 0; i < row; i++) {
                        //     if(i == 0)
                        //         body += "<td style='" + cursors + " text-align: center; background-color: #f67e7eff' " + onclick +">-</td>";
                        //     else
                        //         body += "<td style='text-align: center; background-color: #f67e7eff'>-</td>";
                        // }
                        body += "</tr>";
                        num++;
                    });
        
                    $("#bodyTableShot").html(body);

                    if ($.fn.DataTable.isDataTable('#tableShot')) {
                        $('#tableShot').DataTable().destroy();
                    }
        
                    //add input search each th tfoot
                    // $("#tableShot").find("tfoot").find("th").each(function() {
                    //     var title = $(this).text();
                    //     $(this).html("<input type='text' placeholder='Search ' />");
                    // });
        
                    // $('#tableShot').DataTable({
                    //     'dom': '<"top">rt<"bottom"ilp><"clear">',
                    //     'deferRender': true,
                    //     'scrollY': 600,
                    //     'scrollCollapse': true,
                    //     'ordering': true,
                    //     'info': true,
                    //     'paging': false,
                    //     'searching': true,
                    //     'lengthChange': false,
                    //     'autoWidth': false,
                    //     'columnDefs': [
                    //         {
                    //             'targets': [3,4,5,6,7,8],
                    //             'orderable': false
                    //         }
                    //     ],
                    //     'pageLength': 10,
                    //     'language': {
                    //         'lengthMenu': "_MENU_",
                    //         'info': "Showing _START_ to _END_ of _TOTAL_ entries",
                    //         'infoEmpty': "Showing 0 to 0 of 0 entries",
                    //         'infoFiltered': "(filtered from _MAX_ total entries)",
                    //         'zeroRecords': "No matching records found",
                    //         'emptyTable': "No data available in table",
                    //         'loadingRecords': "Loading...",
                    //         'processing': "Processing...",
                    //         'search': "Search:",
                    //         'paginate': {
                    //             'first': "First",
                    //             'last': "Last",
                    //             'next': "Next",
                    //             'previous': "Previous"
                    //         }
                    //     },
                    //     'initComplete': function() {
                    //         this.api().columns().every(function() {
                    //             var that = this;
                    //             $('input', this.footer()).on('keyup change clear', function() {
                    //                 if (that.search() !== this.value) {
                    //                     that
                    //                         .search(this.value)
                    //                         .draw();
                    //                 }
                    //             });
                    //         });
                    //     }
                    // });
        
                    //move tfoot column th and td to thead

                }
            })

            
        }

        function openModalShot(asset_number, asset_name) {
            $('#modal_shot').modal('show');
            $('#fa_number').val(asset_number);
            $('#nama_molding').val(asset_name);

            var data = {
                asset_number : asset_number
            };

            $.get('{{ url('fetch/diagnose_molding/shot_list') }}', data, function(result, status, xhr) {
                if(result.status) {
                    var tableData = "";
                    $('#tbodyShot').empty();
                    if(result.data.length > 0) {
                        var no = 1;
                        $.each(result.data, function(key, value) {
                            tableData += '<tr>';
                            tableData += '<td>' + no++ + '</td>';
                            tableData += '<td>' + value.created_at + '</td>';
                            tableData += '<td>' + value.total_shot + '</td>';
                            tableData += '<td>' + value.accumulative_shot + '</td>';
                            tableData += '<td>' + value.created_by + '</td>';
                            tableData += '<td></td>';
                            tableData += '</tr>';
                        });
                    } else {
                        tableData += '<tr>';
                        tableData += '<td colspan="6" class="text-center">Tidak ada data</td>';
                        tableData += '</tr>';
                    }
                    $('#tbodyShot').html(tableData);
                } else {
                    toastr.error(result.message);
                    audio_error.play();
                }
            })
        }

        function updateShot() {
            var asset_number = $('#fa_number').val();
            var shot = $('#shot').val();

            var data = {
                asset_number : asset_number,
                molding_name : $('#nama_molding').val(),
                period : $('#period').val(),
                shot : shot
            };

            $.post('{{ url('update/diagnose_molding/shot') }}', data, function(result, status, xhr) {
                if(result.status) {
                    toastr.success(result.message);
                    audio_success.play();
                    var data = {
                        asset_number : asset_number
                    };

                    $.get('{{ url('fetch/diagnose_molding/shot_list') }}', data, function(result, status, xhr) {
                        if(result.status) {
                            var tableData = "";
                            $('#tbodyShot').empty();
                            if(result.data.length > 0) {
                                var no = 1;
                                $.each(result.data, function(key, value) {
                                    tableData += '<tr>';
                                    tableData += '<td>' + no++ + '</td>';
                                    tableData += '<td>' + value.created_at + '</td>';
                                    tableData += '<td>' + value.total_shot + '</td>';
                                    tableData += '<td>' + value.accumulative_shot + '</td>';
                                    tableData += '<td>' + value.created_by + '</td>';
                                    tableData += '<td></td>';
                                    tableData += '</tr>';
                                });
                            } else {
                                tableData += '<tr>';
                                tableData += '<td colspan="6" class="text-center">Tidak ada data</td>';
                                tableData += '</tr>';
                            }
                            $('#tbodyShot').html(tableData);

                            //wait 2 sec and reload page
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(result.message);
                            audio_error.play();
                        }
                    })
                } else {
                    toastr.error(result.message);
                    audio_error.play();
                }
            })
        }

        function parseDate(str) {
            return new Date(str.replace(/(\d{2}) (\w{3}) (\d{2})/, '$1 $2 20$3'));
        }

        function normalize(d) {
            if (!d || !(d instanceof Date)) return null;
            return new Date(d.getFullYear(), d.getMonth(), d.getDate());
        }

        // Handle the change event for the file input
        var audio_error = new Audio('{{ url('sounds/error.mp3') }}');
        var audio_success = new Audio('{{ url('sounds/success.mp3') }}');
    </script>
@endsection
