<!DOCTYPE html>
<html lang="">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PT. YMPI - CONTROL DELIVERY</title>
	<link rel="shortcut icon" type="image/x-icon" href="{{ url("logo_mirai.png")}}">
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/animate/animate.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/css-hamburgers/hamburgers.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/animsition/css/animsition.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/select2/select2.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/daterangepicker/daterangepicker.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('css/main.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('css/jquery.gritter.css') }}" >	
	<link rel="stylesheet" href="{{ url("bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css")}}">
	<link rel="stylesheet" href="{{ url('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
	<style type="text/css">

		@font-face {
			font-family: Raleway-SemiBold;
			src: url('../fonts/raleway/Raleway-SemiBold.ttf'); 
		}

		@font-face {
			font-family: Raleway-Black;
			src: url('../fonts/raleway/Raleway-Black.ttf'); 
		}

		.container-contact100 {
			background: url('ympi2.jpg') no-repeat fixed top;
		}

		.contact100-form-title {
			padding-top: 20px;
		}

		td{
			padding-right: 5px;
			padding-left: 5px;
			padding-top: 0px;
			padding-bottom: 0px;
			font-size: 12px;
			vertical-align: middle;
		}

		th{
			font-size: 13px;
			border:1px solid black;
			background-color: #aee571;
			text-align: center;
			padding-right: 5px;
			padding-left: 5px;			
		}

		#main-title {
			color: white;
			background-color: #605ca8;
			padding-bottom: 0;
			text-align: center;
			font-size: 2.0vw;
			font-weight: bold;
			margin-top: 20px;
			padding: 10px;
		}
	</style>
</head>

<body>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<div id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
		<p style="position: absolute; color: White; top: 45%; left: 35%;">
			<span style="font-size: 20px">Loading, please wait . . . <i class="fa fa-spin fa-refresh"></i></span>
		</p>
	</div>

	<div class="container-contact100" style="align-items: start">
		<div class="wrap-contact100 col-xs-12 col-md-8 col-md-offset-2" style="padding: 0 10px; padding-bottom: 6%;">
			<span class="contact100-form-title" id="main-title">
				<small style="font-size: 1.25vw;">CONTROL DELIVERY PT. YMPI</small><br>PO CONFIRMATION
			</span>

			<div id="not_filled" style="width: 100%;">
				<div class="col-xs-12 col-md-8 col-md-offset-2" id="po_field" style="margin-left: 16.67%;">
					<input type="hidden" value="{{csrf_token()}}" name="_token" />
					<label class="label-input1002" id="labeldept">PO Number</label>
					<input type="hidden" id="check_po" value="{{ $check_po }}">
					<input type="text" class="form-control" id="po_number" name="po_number" placeholder="Input PO Number (e.g. : 1014735)" onkeyup="searhPo()">
				</div>

				<div class="col-xs-12 col-md-12" id="main" style="display: none; vertical-align: middle; margin-top: 3%;">
					<p><sup style="font-weight: bold; font-size: 10px;">*)</sup>&nbsp;Please tick the check column to confirm that the PO has been received</p>
					<table style="border:1px solid black; border-collapse: collapse; width: 100%;" id="tableDetail">
						<thead>
							<tr>
								<th style="width: 10%;">GMC</th>
								<th style="width: 30%;">Material</th>
								<th style="width: 10%;">PO Number</th>
								<th style="width: 5%;">Item Line</th>
								<th style="width: 10%;">Order Date</th>
								<th style="width: 10%;">ETA YMPI</th>
								<th style="width: 5%;">Quantity</th>
								<th style="width: 5%;">Check</th>
								<th style="width: 15%;">Note</th>
							</tr>
						</thead>
						<tbody id="bodyDetail">
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>

					<br>
					<center>
						<div class="container-contact100-form-btn" style="margin-top: 2%;">
							<button class="contact1002-form-btn" type="button" onclick="clearAll()" style="display: inline-block; font-family: sans-serif;">
								<span>
									&nbsp;&nbsp;&nbsp;&nbsp;Cancel&nbsp;&nbsp;&nbsp;&nbsp;
								</span>
							</button>

							<button class="contact100-form-btn" onclick="save()" style="display: inline-block; font-family: sans-serif;">
								<span>
									&nbsp;SUBMIT&nbsp;
									<i class="fa fa-save"></i>&nbsp;
								</span>
							</button>
						</div>
					</center>
				</div>
			</div>

			<div id="already_filled" style="width: 100%; display: none; margin-top: 5%;">
				<div class="col-xs-12 col-md-12">
					<center style="font-size: 20px">Thank you <span style="color: #3c8dbc;" id="vendor_name"></span><br>
					For the response. We will check it as soon as possible</center>
				</div>
			</div>
		</div>
	</div>
</body>

<script src="{{ url('vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<script src="{{ url('vendor/animsition/js/animsition.min.js')}}"></script>
<script src="{{ url('vendor/bootstrap/js/popper.js')}}"></script>
<script src="{{ url('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{ url('vendor/select2/select2.min.js')}}"></script>
<script src="{{ url('vendor/daterangepicker/moment.min.js')}}"></script>
<script src="{{ url('vendor/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{ url('vendor/countdowntime/countdowntime.js')}}"></script>
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script src="{{ url('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ url("bower_components/datatables.net/js/jquery.dataTables.min.js")}}"></script>
<script src="{{ url("bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js")}}"></script>


<script type="text/javascript">
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	jQuery(document).ready(function() {
		clearAll();

	});
	
	function clearAll() {
		$("#main").hide();	
		$("#not_filled").show();
		$("#already_filled").hide();
		$("#po_number").prop('disabled', false);

		$("#po_number").val('');
		// $("#po_number").focus();
		
		item_line = [];
	}

	var item_line = [];

	function searhPo() {
		var po_number = $("#po_number").val();
		var check_po = $("#check_po").val();
		console.log(po_number);

		if(po_number.length >= 7){

			if(check_po != po_number){
				openErrorGritter('Error!', "PO number doesn't match the link");
				return false;
			}

			var data = {
				po_number : po_number
			}

			$("#loading").show();
			$.get('{{ url("fetch/po") }}', data, function(result, status, xhr){
				if(result.status){

					$('#loading').show();

					$('#tableDetail').DataTable().clear();
					$('#tableDetail').DataTable().destroy();
					$('#bodyDetail').html("");
					item_line = [];
					$("po_number").prop('disabled', true);

					var tableData = "";
					for (var i = 0; i < result.data.length; i++) {
						$('#vendor_name').text(result.data[i].vendor_name);

						tableData += '<tr>';
						tableData += '<td style="width:10%; padding:0px 5px 0px 5px; text-align:center;">'+ result.data[i].material_number +'</td>';
						tableData += '<td style="width:30%; padding:0px 5px 0px 5px; text-align:left;">'+ result.data[i].material_description +'</td>';
						tableData += '<td style="width:10%; padding:0px 5px 0px 5px; text-align:center;">'+ result.data[i].po_number +'</td>';
						tableData += '<td style="width:5%; padding:0px 5px 0px 5px; text-align:center;">'+ result.data[i].item_line +'</td>';
						tableData += '<td style="width:10%; padding:0px 5px 0px 5px; text-align:center;">'+ result.data[i].issue_date +'</td>';
						tableData += '<td style="width:10%; padding:0px 5px 0px 5px; text-align:center;">'+ result.data[i].eta_date +'</td>';
						tableData += '<td style="width:5%; padding:0px 5px 0px 5px; text-align:right;">'+ result.data[i].quantity +'</td>';
						
						tableData += '<td style="width:5%; padding:18px 5px 0px 5px; text-align:center;">';
						tableData += '<label><input type="checkbox" class="minimal" id="check_'+ result.data[i].item_line +'"></label><br>'
						tableData += '</td>';

						tableData += '<td style="width:15%; padding:0px 5px 0px 5px; text-align:center;">';
						tableData += '<input type="text" class="form-control" style="font-size: 12px;" id="note_'+ result.data[i].item_line +'" placeholder="Reason ...">';
						tableData += '</td>';

						tableData += '</tr>';

						item_line.push(result.data[i].item_line);

					}

					$('#bodyDetail').append(tableData);
					$('#tableDetail').DataTable({
						'dom': 'Bfrtip',
						'responsive':true,
						'lengthMenu': [
						[ -1 ],
						[ 'Show all' ]
						],
						'buttons': {
							buttons:[ ]
						},
						'paging': false,
						'lengthChange': false,
						'searching': false,
						'ordering': false,
						'info': false,
						'autoWidth': true,
						'sPaginationType': 'full_numbers',
						'bJQueryUI': true,
						'bAutoWidth': false,
						'processing': true
					});


					$('#main').show();
					$('#loading').hide();

				}
			});

		}else{
			$('#tableDetail').DataTable().clear();
			$('#tableDetail').DataTable().destroy();
			$('#bodyDetail').html("");
			var tableData = "";

			$('#bodyDetail').append(tableData);
			$('#tableDetail').DataTable({
				'dom': 'Bfrtip',
				'responsive':true,
				'lengthMenu': [
				[ -1 ],
				[ 'Show all' ]
				],
				'buttons': {
					buttons:[ ]
				},
				'paging': false,
				'lengthChange': false,
				'searching': false,
				'ordering': false,
				'info': false,
				'autoWidth': true,
				'sPaginationType': 'full_numbers',
				'bJQueryUI': true,
				'bAutoWidth': false,
				'processing': true
			});
		}
		
	}

	function save() {

		var po_number = $("#po_number").val();
		var data = [];

		for (var i = 0; i < item_line.length; i++) {
			if($('#check_' + item_line[i]).is(":checked")){
				data.push({
					'item_line' : item_line[i],
					'note' : $('#note_' + item_line[i]).val(),
				});
			}else{
				openErrorGritter('Error!', 'Tick all column before submit');
				return false;
			}
		}

		var x = {
			po_number: po_number,
			data: data
		}

		if (confirm("Are you sure to confirm this PO?")) {
			console.log(data);
			$("#loading").show();

			$.post('{{ url("input/po_confirmation") }}', x, function(result, status, xhr){
				if(result.status){
					clearAll();
					$("#not_filled").hide();
					$("#already_filled").show();
					openSuccessGritter('Success', 'PO successfully confirmed');
					$("#loading").hide();

				}else{
					openErrorGritter('Error!', 'Tick all column before submit');
					console.log('Error : ' + result.message)
					$("#loading").hide();
				}
			});
		}
	}



	function openSuccessGritter(title, message){
		jQuery.gritter.add({
			title: title,
			text: message,
			class_name: 'growl-success',
			image: '{{ url("images/image-screen.png") }}',
			sticky: false,
			time: '2000'
		});
	}

	function openErrorGritter(title, message) {
		jQuery.gritter.add({
			title: title,
			text: message,
			class_name: 'growl-danger',
			image: '{{ url("images/image-stop.png") }}',
			sticky: false,
			time: '2000'
		});
	}
</script>

</html>