<!DOCTYPE html>
<html lang="">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PT. YMPI - Change Management System</title>
	<link rel="shortcut icon" type="image/x-icon" href="{{ url('logo_mirai_bundar.png')}}" />

	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/font-awesome.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/icon-font.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/animate.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/hamburgers.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/animsition.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/select2.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/daterangepicker.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/main.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('ympicoid/jquery.gritter.css') }}" >
	<link rel="stylesheet" href="{{ url('ympicoid/bootstrap-datepicker.min.css')}}">
	
	<style type="text/css">

		@font-face {
			font-family: Raleway-SemiBold;
			src: url('../fonts/raleway/Raleway-SemiBold.ttf'); 
		}

		@font-face {
			font-family: Raleway-Bold;
			src: url('../fonts/raleway/Raleway-Bold.ttf'); 
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

		.radio {
			display: inline-block;
			position: relative;
			padding-left: 35px;
			margin-bottom: 12px;
			cursor: pointer;
			font-size: 16px;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}

		/* Hide the browser's default radio button */
		.radio input {
			position: absolute;
			opacity: 0;
			cursor: pointer;
		}

		/* Create a custom radio button */
		.checkmark {
			position: absolute;
			top: 0;
			left: 0;
			height: 25px;
			width: 25px;
			background-color: #ccc;
			border-radius: 50%;
		}

		/* On mouse-over, add a grey background color */
		.radio:hover input ~ .checkmark {
			background-color: #ccc;
		}

		/* When the radio button is checked, add a blue background */
		.radio input:checked ~ .checkmark {
			background-color: #2196F3;
		}

		/* Create the indicator (the dot/circle - hidden when not checked) */
		.checkmark:after {
			content: "";
			position: absolute;
			display: none;
		}

		/* Show the indicator (dot/circle) when checked */
		.radio input:checked ~ .checkmark:after {
			display: block;
		}

		/* Style the indicator (dot/circle) */
		.radio .checkmark:after {
			top: 9px;
			left: 9px;
			width: 8px;
			height: 8px;
			border-radius: 50%;
			background: white;
		}

	</style>
</head>

<body>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<div id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
		<p style="position: absolute; color: White; top: 45%; left: 35%;">
			<span style="font-size: 20px">Loading, mohon tunggu . . . <i class="fa fa-spin fa-refresh"></i></span>
		</p>
	</div>

	<div class="container-contact100" style="align-items: start">
		<div class="wrap-contact100 col-xs-12 col-md-8 col-md-offset-2" style="padding: 0 10px;margin-left: 20px">
			<div id="belum_mengisi" style="width: 100%;">

				<span class="contact100-form-title" style="color: white;background-color: #605ca8;padding-bottom: 0;text-align: center;font-size: 2.0vw;font-weight: bold;margin-top: 20px;padding: 10px">
					CMS PT. YMPI (Change Management System)
				</span>


      	<input type="hidden" value="{{csrf_token()}}" name="_token" />
				<label class="label-input1002" id="labelnama">Vendor Code</label>
				<input type="text" class="form-control" id="name" name="name" placeholder="e.g. : G859Q">

				<label class="label-input1002" id="labeldept">Vendor Name</label>
				<input type="text" class="form-control" id="company" name="company" placeholder="e.g. : PT. Yamaha Musical Products Indonesia">


				<label class="label-input1002" id="pertanyaan">Is there any change regarding to the scope written in the attached CMS addendum ? <span style="color:red">*</span></label>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="radio" style="margin-top: 5px;margin-left: 5px">Yes 
						<input type="radio"  id="jawaban" name="jawaban" value="Yes" onclick="changeJawaban(this.value)">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px">No 
						<input type="radio" id="jawaban" name="jawaban" value="No" onclick="changeJawaban(this.value)">
						<span class="checkmark"></span>
					</label>
				</div>

				<div id="cms">
					<label class="label-input1002">Change CMS File <span style="color:red">*</span></label>

					<input type="file" name="file_cms" id="file_cms" value="Upload CMS Change">
				</div>

				<div class="container-contact100-form-btn" style="margin-top: 20px">
					<button class="contact100-form-btn" onclick="save()">
						<span>
							Submit
							<i class="fa fa-save"></i>
						</span>
					</button>
				</div>
				<br>				
				<div style="margin: auto;">
				<p>Dear PT. YMPI's Supplier,</p>
			<p>
				Regarding to CMS (Change Management System) Yamaha Group Policy.<br>
				There are <b>"Proposal of Change"</b> that must be submitted to Yamaha.
			</p>

			<p>
				If there is change regarding to the scope written in the attached CMS addendum.
				Therefore please check attached files and fill in the <b>"Proposal of Change"</b>.<br>
				Please upload your filled <b>"Proposal of Change"</b> 1.5 Months before changes are made.
			</p>
			<p>
				Please respond whether there are changes or not by filling out the form and upload file on the following link.<br>
				<a href="http://cms.ympi.co.id/">cms.ympi.co.id</a>
			</p>
			<p>
				*******************************************************************************<br>
			</p>
			<p>
				Mengenai kebijakan Yamaha Group terkait CMS (Change Management System).<br>
				Berikut terdapat <b>"Proposal of Change"</b> yang harus diserahkan ke Yamaha.
			</p>

			<p>
				Apabila terdapat perubahan sesuai dengan scope yang tertulis pada CMS addendum terlampir.<br>
				Mohon cek lampiran <b>"Proposal of Change"</b> dan mengisi file tersebut.
				Mohon upload <b>"Proposal of Change"</b> yang sudah diisi 1.5 bulan sebelum perubahan.
			</p>
			<p>
				Mohon merespon ada atau tidaknya perubahan dengan mengisi form dan upload file pada link berikut.<br>
				<a href="http://cms.ympi.co.id/">cms.ympi.co.id</a>
			</p>
			<p>
				-------------------------------------------------------------------<br>
				PT. YAMAHA MUSICAL PRODUCTS INDONESIA<br>
				Jl. Rembang Industri I/36<br>
				Kawasan Industri PIER Pasuruan - Jawa Timur<br>
				Phone : 0343-740290<br>
				Fax   : 0343-740291
			</p>
				<br>
				</div>
	      </div>
	      <div id="sudah_mengisi" style="width: 100%;padding: 170px 20px">
					<div class="col-xs-12 col-md-12">
          	<center style="font-size:20px">Thank You <span class="name_vendor"></span> For The Response. We Will Check it As Soon As Possible</center>
          	</div>
          </div>
		</div>
	</div>

</body>

<script src="{{ url('ympicoid/jquery-3.2.1.min.js')}}"></script>
<script src="{{ url('ympicoid/animsition.min.js')}}"></script>
<script src="{{ url('ympicoid/popper.js')}}"></script>
<script src="{{ url('ympicoid/bootstrap.min.js')}}"></script>
<script src="{{ url('ympicoid/select2.min.js')}}"></script>
<script src="{{ url('ympicoid/moment.min.js')}}"></script>
<script src="{{ url('ympicoid/daterangepicker.js')}}"></script>
<script src="{{ url('ympicoid/countdowntime.js')}}"></script>
<script src="{{ url('ympicoid/jquery.gritter.min.js') }}"></script>
<script src="{{ url('ympicoid/main.js')}}"></script>
<script src="{{ url('ympicoid/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">
	jQuery(document).ready(function() {
		$('#sudah_mengisi').hide();
		$('#cms').hide();

		$('.datepicker').datepicker({
			autoclose: true,
			format: "yyyy-mm-dd",
			todayHighlight: true,
		});
	});
	
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }); 

	function save() {
		$("#loading").show();


		if($("#name").val() == ""){
		    $("#loading").hide();
			openErrorGritter('Error!', 'Please Fill The Empty Column');
			return false;
		}

		if($("#company").val() == ""){
		    $("#loading").hide();
			openErrorGritter('Error!', 'Please Fill The Empty Column');
			return false;
		}

		if ($('input[id="jawaban"]:checked').val() == null) {
			$("#loading").hide();
			openErrorGritter('Error!', 'Column (<b>*</b>) Required');
			return false;
		}

		if ($('input[id="jawaban"]:checked').val() == "Yes" && $('#file_cms').val() == '') {
			$("#loading").hide();
			openErrorGritter('Error!', 'File CMS Required');
			return false;
		}

		var formData = new FormData();

		formData.append('name', $('#name').val());				
		formData.append('company', $('#company').val());
		formData.append('question', $('#pertanyaan').text());
		formData.append('answer', $('input[id="jawaban"]:checked').val());
		formData.append('file_cms', $('#file_cms').prop('files')[0]);

		$.ajax({
			url:"{{ url('post/cms') }}",
			method:"POST",
			data:formData,
			dataType:'JSON',
			contentType: false,
			cache: false,
			processData: false,
			success: function (response) {
				$("#name_vendor").val($('#name').val())
				openSuccessGritter("Success","Berhasil Dibuat");
        $("#loading").hide();
				$('#belum_mengisi').hide();
				$('#sudah_mengisi').show();
			},
			error: function (response) {
				console.log(response.datas);
			},
		})
	}

	function changeJawaban(value) {
		if (value === 'Yes') {
			$('#cms').show();
		}
		else{
			$('#cms').hide();
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