<!DOCTYPE html>
<html lang="">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PT. YMPI - Vendor Swab Data</title>
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
		<div class="wrap-contact100 col-xs-12 col-md-8" style="padding:0 20px;">
			<div class="contact100-form-title">


				<div id="belum_mengisi" style="width: 100%;">

					<span class="contact100-form-title" style="color: white;background-color: #605ca8;padding-bottom: 0;text-align: center;font-size: 16px;font-weight: bold;margin-top: 20px;padding: 10px">
						<b>PT. Yamaha Musical Products Indonesia</b>
						<br>Kontrol Hasil Swab Vendor - COVID19
					</span>

					<span class="contact100-form-title" style="font-size:16px;text-align: justify;padding:20px;background-color:#eee">
						PT. Yamaha Musical Products Indonesia memperhatikan keselamatan karyawan kami dan tamu. Kami memperhatikan dengan seksama perkembangan pandemi COVID19 dan demi memastikan keselamatan dan kesehatan lingkungan Kerja, maka kami meminta agar anda menjawab dengan jujur beberapa pertanyaan berikut ini.
					</span>

    			<input type="hidden" value="{{csrf_token()}}" name="_token" />
    			<input type="hidden" id="jml_pertanyaan" value="5">
					<label class="label-input1002" style="text-align:left" id="labelnama">Nama</span></label>
					<input type="text" class="form-control" id="name" name="name">

					<label class="label-input1002" style="text-align:left" id="labeldept">Perusahaan</span></label>
					<input type="text" class="form-control" id="company" name="company">

					<label class="label-input1002" style="text-align:left" id="labelstatus">Hasil</span></label>
					<select class="form-control" id="status" name="status" data-placeholder='Status' style="width: 100%">
						<option value="">&nbsp;</option>
						<option value="Positif / Reaktif">Positif / Reaktif</option>
						<option value="Negatif / Non Reaktif">Negatif / Non Reaktif</option>
					</select>

					<div id="rapid">
						<label class="label-input1002" style="text-align:left">Lampiran Hasil Swab Antigen / PCR</label>

						<input type="file" name="file_rapid" id="file_rapid" style="float:left;font-size:14px" value="Upload Bukti Rapid">
					</div>
					
					<div class="container-contact100-form-btn" style="margin-top: 40px">
						<button class="contact100-form-btn" onclick="save()"> 
							<span>
								Submit
								<i class="fa fa-save"></i>
							</span>
						</button>
					</div>

					<span class="contact100-form-title" style="font-size:16px;text-align: left;">
						<i style="color: red"><b>Catatan:</b></i>
						<span style="color: red"><b>Ketika anda berada di PT. YMPI maka wajib mengikuti protokol kesehatan yang berlaku.</b></span>
						<br>
						<span  style="color: red;font-size: 14px !important;">
						<br><b>a.	Harus menggunakan masker medis serta faceshield dan atau masker N95.
						<br>b.	Mematuhi protokol kesehatan di YMPI
						<ul>
							<li>&nbsp;&nbsp;&nbsp; 1.	Cek suhu dan melewati bilik sterilisasi di pos security</li>
							<li>&nbsp;&nbsp;&nbsp; 2.	Menjaga jarak min 1,5m</li>
							<li>&nbsp;&nbsp;&nbsp; 3.	Selalu mencuci tangan dan membawa sapu tangan pribadi</li>
							<li>&nbsp;&nbsp;&nbsp; 4.	Membawa peralatan ibadah dan peralatan makan pribadi</li>
							<li>&nbsp;&nbsp;&nbsp; 5.	Jika suhu tubuh > 37.5∘C maka tidak diperbolehkan memasuki area PT. YMPI</li>
						</ul></b>
					</span>
			  </div>
			  <div id="sudah_mengisi" style="width: 100%;padding: 170px 20px">
					<div class="col-xs-12 col-md-12">
          	<center style="font-size:16px"><b>Terimakasih Bapak / Ibu <span class="name_assessment"></span> telah Mengisi Assessment ini. <div id="resiko_covid"></div></i></u></center>
          	<br>Terima kasih atas kerjasamanya</b>
          <div>

				<span class="contact100-form-title" style="font-size:16px;text-align: left;">
					<i style="color: red"><b>Catatan:</b></i>
					<span style="color: red">Ketika anda berada di PT. YMPI maka wajib mengikuti protokol kesehatan yang berlaku.</span>
					<br>
					<span  style="color: red;font-size: 14px !important;">
					<br><b>a.	Harus menggunakan masker medis serta faceshield dan atau masker N95.
					<br>b.	Mematuhi protokol kesehatan di YMPI
					<ul>
						<li>&nbsp;&nbsp;&nbsp; 1.	Cek suhu dan melewati bilik sterilisasi di pos security</li>
						<li>&nbsp;&nbsp;&nbsp; 2.	Menjaga jarak min 1,5m</li>
						<li>&nbsp;&nbsp;&nbsp; 3.	Selalu mencuci tangan dan membawa sapu tangan pribadi</li>
						<li>&nbsp;&nbsp;&nbsp; 4.	Membawa peralatan ibadah dan peralatan makan pribadi</li>
						<li>&nbsp;&nbsp;&nbsp; 5.	Jika suhu tubuh > 37.5∘C maka tidak diperbolehkan memasuki area PT. YMPI</li>
					</ul></b>
				</span>
        </div>
				<br>

				</span>
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
			openErrorGritter('Error!', 'Pastikan Nama Lengkap Anda Sudah Diisi');
			return false;
		}

		if($("#company").val() == ""){
		    $("#loading").hide();
			openErrorGritter('Error!', 'Pastikan Nama Perusahaan Anda Sudah Diisi');
			return false;
		}

		if($("#status").val() == ""){
		    $("#loading").hide();
			openErrorGritter('Error!', 'Pastikan Status Sudah Diisi');
			return false;
		}

		if ($('#file_rapid').val() == '') {
			$("#loading").hide();
			openErrorGritter('Error!', 'Hasil File Rapid Swab Antigen / PCR Harus Dilampirkan');
			return false;
		}

		var formData = new FormData();

		formData.append('name', $('#name').val());				
		formData.append('company', $('#company').val());
		formData.append('status', $('#status').val());
		formData.append('file_rapid', $('#file_rapid').prop('files')[0]);

		$.ajax({
			url:"{{ url('post/vendor_assessment') }}",
			method:"POST",
			data:formData,
			dataType:'JSON',
			contentType: false,
			cache: false,
			processData: false,
			success: function (response) {
				$("#name_assessment").val($('#name').val())
				$('#resiko_covid').html('Silahkan Datang ke PT. Yamaha Musical Products Indonesia dan Tetap Wajib Menjalankan Protocol Kesehatan.');

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