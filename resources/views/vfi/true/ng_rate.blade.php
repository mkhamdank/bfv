@extends('layouts.master')

@section('title', 'VFI')

@section('styles')
<link href="<?php echo e(url("css/jquery.numpad.css")); ?>" rel="stylesheet">
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
<link rel="stylesheet" href="{{ url("css/buttons.dataTables.min.css")}}">
    <style>
        #vfi-container {
            color: #333333;
        }

        .menu-btn {
            width: 100%;
            margin: 1% 0;
        }

        .menu-name {

        }

        .auth-name {
            font-weight: 600;
            color: #BA241C;
        }

        thead>tr>th{
            text-align:center;
            overflow:hidden;
        }
        tbody>tr>td{
            text-align:center;
        }
        tfoot>tr>th{
            text-align:center;
        }
        th:hover {
            overflow: visible;
        }
        td:hover {
            overflow: visible;
        }
        table.table-bordered{
            border:1px solid black;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid black;
            padding-top: 0;
            padding-bottom: 0;
            vertical-align: middle;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid black;
            padding: 0px;
            vertical-align: middle;
        }
        table.table-bordered > tfoot > tr > th{
            border:1px solid black;
            padding:0;
            vertical-align: middle;
            background-color: rgb(126,86,134);
            color: #FFD700;
        }
        thead {
            background-color: rgb(126,86,134);
        }
        td{
            overflow:hidden;
            text-overflow: ellipsis;
        }
        #ngTemp {
            height:200px;
            overflow-y: scroll;
        }

        #ngList2 {
            height:454px;
            overflow-y: scroll;
            /*padding-top: 5px;*/
        }
        #loading, #error { display: none; }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            /* display: none; <- Crashes Chrome on hover */
            -webkit-appearance: none;
            margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
        }

        input[type=number] {
            -moz-appearance:textfield; /* Firefox */
        }
        .page-wrapper{
            padding-top: 0px;
        }
        .datepicker-days > table > thead,
        .datepicker-days > table > thead >tr>th,
        .datepicker-months > table > thead>tr>th,
        .datepicker-years > table > thead>tr>th,
        .datepicker-decades > table > thead>tr>th,
        .datepicker-centuries > table > thead>tr>th{
            background-color: white;
            color: #696969 !important;
        }

        .select2-selection__choice{
        	color: black !important;
        }

    </style>
@stop

@section('content')
    <section id="vfi-container">
        <div id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8;">
            <p style="position: absolute; color: White; top: 45%; left: 35%;">
                <span style="font-size: 40px">Please Wait...<i class="fa-solid fa-spinner fa-spin"></i></span>
            </p>
        </div>
        <input type="hidden" id="start_time" value="">
        <input type="hidden" id="incoming_check_code" value="">
        <div class="row" style="margin: 1%">
            <div class="card">
                <div class="card-header mt-2">
                    <span>Defect Rate Vendor {{Auth::user()->name}}</span>
                </div>
                <div class="row mt-2 mb-2">
                	<div class="col-md-2" style="">
						<div class="input-group date">
							<div class="input-group-addon bg-green" style="border: none; background-color: #469937; color: white;">
								<i class="fa fa-calendar" style="padding: 10px"></i>
							</div>
							<input type="date" class="form-control" id="date_from" name="date_from" placeholder="Select Date From">
						</div>
					</div>
					<div class="col-md-2" style="padding-left: 0;">
						<div class="input-group date">
							<div class="input-group-addon bg-green" style="border: none; background-color: #469937; color: white;">
								<i class="fa fa-calendar" style="padding: 10px"></i>
							</div>
							<input type="date" class="form-control" id="date_to" name="date_to" placeholder="Select Date To">
						</div>
					</div>
					<div class="col-md-2" style="padding-left: 0;">
						<div class="form-group">
							<select class="form-control select2" multiple="multiple" id='materialSelect' onchange="changeMaterial()" data-placeholder="Select Material" style="width: 100%;color: black !important">
								@foreach($materials as $material)
								<option style="color: black;" value="{{$material->material_number}}">{{$material->material_number}} - {{$material->material_description}}</option>
								@endforeach
							</select>
							<input type="text" name="material" id="material" style="color: black !important" hidden>
						</div>
					</div>
					<div class="col-md-2" style="padding-left: 0;">
						<button class="btn btn-success pull-left" onclick="fetchData()" style="font-weight: bold;">
							Search
						</button>
					</div>
					<div class="col-md-2" style="padding-left: 0px">
						<div class="pull-right" id="last_update" style="margin: 0px;padding-top: 0px;padding-right: 0px;font-size: 12.5px"></div>
					</div>
					<div class="col-md-12" style="padding-top: 5px">
						<div id="container" style="width: 100%;height: 74vh;"></div>
					</div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalDetail">
			<div class="modal-dialog modal-xl" style="width: 1140px">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="modalDetailTitle"></h4>
						<div class="modal-body table-responsive no-padding" style="min-height: 100px">
							<center><h3 style="font-weight: bold;color:black ;font-size: 20px" id="judul_detail"></h3></center>
							<div class="col-md-12" id="bodyDetail">
					          <table class="table user-table no-wrap" style="font-size:15px" id="tableDetail">
					          	<thead style="background-color: #3f50b5;color: white !important">
					          		<tr>
					          			<th style="background-color: #3f50b5;color: white !important">Serial Number</th>
					          			<th style="background-color: #3f50b5;color: white !important">Material</th>
					          			<th style="background-color: #3f50b5;color: white !important">Prod. Result</th>
					          			<th style="background-color: #3f50b5;color: white !important">Qty OK</th>
					          			<th style="background-color: #3f50b5;color: white !important">NG Name</th>
					          			<th style="background-color: #3f50b5;color: white !important">Qty NG</th>
					          			<th style="background-color: #3f50b5;color: white !important">Inspector</th>
					          		</tr>
					          	</thead>
					          	<tbody id="bodyTableDetail">
					          		
					          	</tbody>
					          </table>

					          <table class="table table-bordered table-striped" style="font-size:15px" id="tableDetailTotal">
					          </table>
					        </div>
						</div>
					</div>
					<div class="modal-footer">
			          <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
			        </div>
				</div>
			</div>
		</div>

    </section>
@endsection

@section('scripts')
<script src="<?php echo e(url("js/jquery.numpad.js")); ?>"></script>
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="{{ url("js/jquery.dataTables.min.js") }}"></script>
<script src="{{ url("js/dataTables.bootstrap4.min.js") }}"></script>
<script src="{{ url("js/jquery.flot.min.js") }}"></script>
<script src="{{ url("js/dataTables.buttons.min.js")}}"></script>
<script src="{{ url("js/buttons.flash.min.js")}}"></script>
<script src="{{ url("js/jszip.min.js")}}"></script>
<script src="{{ url("js/vfs_fonts.js")}}"></script>
<script src="{{ url("js/buttons.html5.min.js")}}"></script>
<script src="{{ url("js/buttons.print.min.js")}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // $.fn.numpad.defaults.gridTpl = '<table class="table modal-content" style="align-items:left"></table>';
        // $.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in" style="opacity:.5"></div>';
        // $.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" style="font-size:1.5vw; height: 50px;width:100%"/>';
        // $.fn.numpad.defaults.buttonNumberTpl =  '<button type="button" class="btn btn-primary" style="font-size:1.5vw; width:40px;background-color:#b5ffa8;color:black"></button>';
        // $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn" style="font-size:1.5vw; width: 100%;background-color:#ffb84d;color:black"></button>';
        // $.fn.numpad.defaults.onKeypadCreate = function(){$(this).find('.done').addClass('btn-primary');};

        $.fn.numpad.defaults.gridTpl = '<table class="table modal-content" style="width: 25%;background-color:white;"></table>';
        $.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in" style="opacity:.4"></div>';
        $.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" style="font-size:2vw; height: 50px;"/>';
        $.fn.numpad.defaults.buttonNumberTpl =  '<button type="button" class="btn btn-info" style="font-size:2vw; width:50px;"></button>';
        $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn btn-success" style="font-size:2vw; width: 100%;color:white;background-color:green;border-color:green"></button>';
        $.fn.numpad.defaults.onKeypadCreate = function(){
            $(this).find('.done').css('background-color','white');
            $(this).find('.done').css('color','rgb(72,156,78)');
            $(this).find('.done').css('border-color','rgb(72,156,78)');

            $(this).find('.sep').css('background-color','white');
            $(this).find('.sep').css('color','black');
            $(this).find('.sep').css('border-color','#0dcaf0');
            $(this).find('.sep').css('width','50px');

            $(this).find('.cancel').css('background-color','white');
            $(this).find('.cancel').css('color','rgb(219,103,115)');
            $(this).find('.cancel').css('border-color','rgb(219,103,115)');

            $(this).find('.clear').css('background-color','white');
            $(this).find('.clear').css('color','black');
            $(this).find('.clear').css('border-color','black');

            $(this).find('.del').css('background-color','white');
            $(this).find('.del').css('color','black');
            $(this).find('.del').css('border-color','black');
        };

        var hour;
        var minute;
        var second;
        var intervalTime;
        var intervalUpdate;
        $(document).ready(function() {
            $('body').toggleClass("sidebar-collapse");
            $('#side_vfi').addClass('menu-open');

            $('.numpad').numpad({
                hidePlusMinusButton : true,
                decimalSeparator : '.'
            });

            $('.select2').select2({
            	allowClear:true
            });

            fetchData();
			setInterval(fetchData,150000);
        });

        function changeMaterial() {
		$("#material").val($("#materialSelect").val());
	}

	function fetchData() {
		var data = {
			date_from:$('#date_from').val(),
			date_to:$('#date_to').val(),
			material:$('#material').val(),
		}
		$.get('{{ url("vfi/fetch/ng_rate/true") }}',data, function(result, status, xhr) {
			if(xhr.status == 200){
				if(result.status){
					var categories = [];
					var checkes = [];
					var ng = [];
					var persen = [];

					$.each(result.outgoing, function(key,value){
						categories.push(value.check_date);
						checkes.push(parseInt(value.qty_check));
						ng.push(parseInt(value.qty_ng));
						persen.push(parseFloat(value.ng_ratio));
					});

					Highcharts.chart('container', {
					    chart: {
					        zoomType: 'xy',
					        backgroundColor:'none'
					    },
					    title: {
					        text: 'Production NG Rate {{Auth::user()->name}}',
					        style:{
					        	color:'black'
					        }
					    },
					    subtitle: {
					        text: result.firstDateTitle+' - '+result.lastDateTitle,
					        style:{
					        	color:'black'
					        }

					    },
					    xAxis: {
					        categories: categories,
					        crosshair: true,
					        style:{
					        	color:'black'
					        },
					        labels:{
					        	style:{
						        	color:'black'
						        }
					        }
					    },
					    yAxis: [{ 
					        labels: {
					            format: '{value}',
					            style: {
					                color: '#000'
					            }
					        },
					        title: {
					            text: 'Qty',
					            style: {
					                color: '#000'
					            }
					        }
					    }, { 
					        title: {
					            text: 'NG Rate',
					            style: {
					                color: '#000'
					            }
					        },
					        labels: {
					            format: '{value}%',
					            style: {
					                color: '#000'
					            }
					        },
					        opposite: true
					    }],
					    tooltip: {
					        shared: true
					    },
					    legend: {
			              layout: 'horizontal',
			              backgroundColor:
			              Highcharts.defaultOptions.legend.backgroundColor || '#eee',
			              color:'black',
			              itemStyle: {
			                fontSize:'12px',
			                color:'black'
			              },
			              reversed : true
			            },
					    credits: {
						     enabled: false
						},
					    plotOptions: {
							series:{
								cursor: 'pointer',
				                point: {
				                  events: {
				                    click: function () {
				                    	showModalDetail(this.category);
				                    }
				                  }
				                },
								dataLabels: {
									enabled: true,
									format: '{point.y}',
									style:{
										fontSize: '13px'
									}
								},
								animation: false,
								pointPadding: 0.93,
								groupPadding: 0.93,
								cursor: 'pointer',
							},
							spline:{
								cursor: 'pointer',
				                point: {
				                  events: {
				                    click: function () {
				                    	showModalDetail(this.category);
				                    }
				                  }
				                },
								dataLabels: {
									enabled: true,
									format: '{point.y}%',
									style:{
										fontSize: '13px'
									}
								},
								animation: false,
								pointPadding: 0.93,
								groupPadding: 0.93,
								cursor: 'pointer',
								borderColor: 'black',
							},
						},
					    series: [
					    {
					        name: 'NG',
					        type: 'column',
					        data: ng,
					        color: '#802626'

					    },{
					        name: 'Total Check',
					        type: 'column',
					        data: checkes,
					        color: '#268044'

					    }, {
					        name: 'NG Rate',
					        type: 'spline',
					        data: persen,
					        color: '#ed151d',
					        yAxis: 1,
					        tooltip: {
					            valueSuffix: '%'
					        }
					    }]
					});
				}
			}
		})
	}

	function showModalDetail(categories) {
		$('#loading').show();
		$('#judul_detail').html("");
		var data = {
			date_from:$('#date_from').val(),
			date_to:$('#date_to').val(),
			material:$('#material').val(),
			categories:categories
		}

		$.get('{{ url("vfi/fetch/ng_rate/detail/true") }}',data, function(result, status, xhr) {
			if(xhr.status == 200){
				if(result.status){
					$('#judul_detail').html("Detail NG Rate Production Check on "+categories);
					$('#tableDetail').DataTable().clear();
                	$('#tableDetail').DataTable().destroy();
					$('#bodyTableDetail').html("");
					$('#tableDetailTotal').html("");
					var bodyDetail = "";
					var bodyDetailTotal = "";
					var total_ng = 0;
					var total_check= 0;

					var serial_number = [];
					var serial_total_check = [];

					$.each(result.outgoing, function(key,value){
						bodyDetail += '<tr>';
						bodyDetail += '<td style="padding:2px">'+value.serial_number+'</td>';
						bodyDetail += '<td style="padding:2px">'+value.material_number+' - '+value.material_description+'</td>';
						bodyDetail += '<td style="padding:2px">'+value.qty_check+'</td>';
						bodyDetail += '<td style="padding:2px">'+value.total_ok+'</td>';
						bodyDetail += '<td style="padding:2px">'+value.ng_name+'</td>';
						bodyDetail += '<td style="padding:2px">'+value.ng_qty+'</td>';
						bodyDetail += '<td style="padding:2px">'+value.inspector+'</td>';
						bodyDetail += '</tr>';

						total_ng = total_ng + parseInt(value.ng_qty);
						total_check = total_check + parseInt(value.qty_check);

						serial_number.push(value.serial_number);
						serial_total_check.push({serial_number:value.serial_number,qty_check:value.qty_check});
					});

					var serial_number_new = serial_number.filter(onlyUnique);

					total_check = 0;
					for(var i = 0; i < serial_number_new.length;i++){
						for(var j = 0; j < serial_total_check.length;j++){
							if (serial_number_new[i] == serial_total_check[j].serial_number) {
								total_check = total_check + parseInt(serial_total_check[j].qty_check);
								break;
							}
						}
					}

					bodyDetailTotal += '<tr style="border-bottom:3px solid black;border-top:3px solid black;background-color:#cddc39;color:black;font-size:15px">';
					bodyDetailTotal += '<td colspan="5" style="color:black;text-align:right;padding:5px">TOTAL NG</td>';
					bodyDetailTotal += '<td colspan="2" style="color:black;border-left:3px solid black;text-align:left;padding:5px">'+total_ng+'</td>';
					bodyDetailTotal += '</tr>';
					bodyDetailTotal += '<tr style="border-bottom:3px solid black;border-top:3px solid black;background-color:#cddc39;color:black;font-size:15px">';
					bodyDetailTotal += '<td colspan="5" style="color:black;text-align:right;padding:5px">TOTAL CHECK</td>';
					bodyDetailTotal += '<td colspan="2" style="color:black;border-left:3px solid black;text-align:left;padding:5px">'+total_check+'</td>';
					bodyDetailTotal += '</tr>';
					bodyDetailTotal += '</tr>';
					bodyDetailTotal += '<tr style="border-bottom:3px solid black;border-top:3px solid black;background-color:#cddc39;color:black;font-size:15px">';
					bodyDetailTotal += '<td colspan="5" style="color:black;text-align:right;padding:5px">NG RATE</td>';
					bodyDetailTotal += '<td colspan="2" style="color:black;border-left:3px solid black;text-align:left;padding:5px">'+((total_ng / total_check) * 100).toFixed(2)+' %</td>';
					bodyDetailTotal += '</tr>';

					$('#bodyTableDetail').append(bodyDetail);
					$('#tableDetailTotal').append(bodyDetailTotal);

					var table = $('#tableDetail').DataTable({
	                    'dom': 'Bfrtip',
	                    'responsive':true,
	                    'lengthMenu': [
	                    [ 10, 25, 50, -1 ],
	                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
	                    ],
	                    'buttons': {
	                        buttons:[
	                        {
	                            extend: 'pageLength',
	                            className: 'btn btn-default',
	                        },
	                        {
	                            extend: 'copy',
	                            className: 'btn btn-success',
	                            text: '<i class="fa fa-copy"></i> Copy',
	                                exportOptions: {
	                                    columns: ':not(.notexport)'
	                            }
	                        },
	                        {
	                            extend: 'excel',
	                            className: 'btn btn-info',
	                            text: '<i class="fa fa-file-excel-o"></i> Excel',
	                            exportOptions: {
	                                columns: ':not(.notexport)'
	                            }
	                        },
	                        {
	                            extend: 'print',
	                            className: 'btn btn-warning',
	                            text: '<i class="fa fa-print"></i> Print',
	                            exportOptions: {
	                                columns: ':not(.notexport)'
	                            }
	                        }
	                        ]
	                    },
	                    'paging': true,
	                    'lengthChange': true,
	                    'pageLength': 10,
	                    'searching': true   ,
	                    'ordering': true,
	                    'order': [],
	                    'info': true,
	                    'autoWidth': true,
	                    "sPaginationType": "full_numbers",
	                    "bJQueryUI": true,
	                    "bAutoWidth": false,
	                    "processing": true
	                });
					$('#modalDetail').modal('show');
					$('#loading').hide();
				}
			}
		});
	}

        var audio_error = new Audio('{{ url("sounds/error.mp3") }}');

        $.date = function(dateObject) {
            var d = new Date(dateObject);
            var day = d.getDate();
            var month = d.getMonth() + 1;
            var year = d.getFullYear();
            if (day < 10) {
                day = "0" + day;
            }
            if (month < 10) {
                month = "0" + month;
            }
            var date = day + "/" + month + "/" + year;

            return date;
        };

        function addZero(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }

        function getActualFullDate() {
            var d = new Date();
            var day = addZero(d.getDate());
            var month = addZero(d.getMonth()+1);
            var year = addZero(d.getFullYear());
            var h = addZero(d.getHours());
            var m = addZero(d.getMinutes());
            var s = addZero(d.getSeconds());
            return year + "-" + month + "-" + day + " " + h + ":" + m + ":" + s;
        }

        function openSuccessGritter(title, message){
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-success',
                image: '{{ url("images/image-screen.png") }}',
                sticky: false,
                time: '3000'
            });
        }

        function openErrorGritter(title, message) {
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-danger',
                image: '{{ url("images/image-stop.png") }}',
                sticky: false,
                time: '3000'
            });
        }

        Highcharts.createElement('link', {
		href: '{{ url("fonts/UnicaOne.css")}}',
		rel: 'stylesheet',
		type: 'text/css'
	}, null, document.getElementsByTagName('head')[0]);

	Highcharts.theme = {
		colors: ['#90ee7e', '#2b908f', '#eeaaee', '#ec407a', '#7798BF', '#f45b5b',
		'#ff9800', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
		chart: {
			backgroundColor: {
				linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
				stops: [
				[0, '#2a2a2b'],
				[1, '#2a2a2b']
				]
			},
			style: {
				fontFamily: 'sans-serif'
			},
			plotBorderColor: '#606063'
		},
		title: {
			style: {
				color: '#E0E0E3',
				textTransform: 'uppercase',
				fontSize: '20px'
			}
		},
		subtitle: {
			style: {
				color: '#E0E0E3',
				textTransform: 'uppercase'
			}
		},
		xAxis: {
			gridLineColor: '#707073',
			labels: {
				style: {
					color: '#E0E0E3'
				}
			},
			lineColor: '#707073',
			minorGridLineColor: '#505053',
			tickColor: '#707073',
			title: {
				style: {
					color: '#A0A0A3'

				}
			}
		},
		yAxis: {
			gridLineColor: '#707073',
			labels: {
				style: {
					color: '#E0E0E3'
				}
			},
			lineColor: '#707073',
			minorGridLineColor: '#505053',
			tickColor: '#707073',
			tickWidth: 1,
			title: {
				style: {
					color: '#A0A0A3'
				}
			}
		},
		tooltip: {
			backgroundColor: 'rgba(0, 0, 0, 0.85)',
			style: {
				color: '#F0F0F0'
			}
		},
		plotOptions: {
			series: {
				dataLabels: {
					color: 'white'
				},
				marker: {
					lineColor: '#333'
				}
			},
			boxplot: {
				fillColor: '#505053'
			},
			candlestick: {
				lineColor: 'white'
			},
			errorbar: {
				color: 'white'
			}
		},
		legend: {
			itemStyle: {
				color: '#E0E0E3'
			},
			itemHoverStyle: {
				color: '#FFF'
			},
			itemHiddenStyle: {
				color: '#606063'
			}
		},
		credits: {
			style: {
				color: '#666'
			}
		},
		labels: {
			style: {
				color: '#707073'
			}
		},

		drilldown: {
			activeAxisLabelStyle: {
				color: '#F0F0F3'
			},
			activeDataLabelStyle: {
				color: '#F0F0F3'
			}
		},

		navigation: {
			buttonOptions: {
				symbolStroke: '#DDDDDD',
				theme: {
					fill: '#505053'
				}
			}
		},

		rangeSelector: {
			buttonTheme: {
				fill: '#505053',
				stroke: '#000000',
				style: {
					color: '#CCC'
				},
				states: {
					hover: {
						fill: '#707073',
						stroke: '#000000',
						style: {
							color: 'white'
						}
					},
					select: {
						fill: '#000003',
						stroke: '#000000',
						style: {
							color: 'white'
						}
					}
				}
			},
			inputBoxBorderColor: '#505053',
			inputStyle: {
				backgroundColor: '#333',
				color: 'silver'
			},
			labelStyle: {
				color: 'silver'
			}
		},

		navigator: {
			handles: {
				backgroundColor: '#666',
				borderColor: '#AAA'
			},
			outlineColor: '#CCC',
			maskFill: 'rgba(255,255,255,0.1)',
			series: {
				color: '#7798BF',
				lineColor: '#A6C7ED'
			},
			xAxis: {
				gridLineColor: '#505053'
			}
		},

		scrollbar: {
			barBackgroundColor: '#808083',
			barBorderColor: '#808083',
			buttonArrowColor: '#CCC',
			buttonBackgroundColor: '#606063',
			buttonBorderColor: '#606063',
			rifleColor: '#FFF',
			trackBackgroundColor: '#404043',
			trackBorderColor: '#404043'
		},

		legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
		background2: '#505053',
		dataLabelsColor: '#B0B0B3',
		textColor: '#C0C0C0',
		contrastTextColor: '#F0F0F3',
		maskColor: 'rgba(255,255,255,0.3)'
	};
	Highcharts.setOptions(Highcharts.theme);

	function onlyUnique(value, index, self) {
	  return self.indexOf(value) === index;
	}

    </script>
@endsection
