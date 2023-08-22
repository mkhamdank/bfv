<!DOCTYPE html>
<html lang="">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PT. YMPI - Guest Assessment</title>
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
		.container-contact100 {
			background: url('ympi2.jpg') no-repeat fixed top;
		}

		.contact100-form-title {
		    padding-top: 20px;
		}

		.gritter-item p{
			color: white;
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
		<div class="wrap-contact100 col-xs-12 col-md-5" style="padding:0 20px;">
			<div class="contact100-form-title">
				<img src="{{url('ympi.jpg')}}" style="width: 100%">
				<span class="contact100-form-title" style="color: white;padding-top: 20px;padding-bottom: 20px;background-color: #5b3a90;font-size: 20px">
					<center><b>PT. Yamaha Musical Products Indonesia</b></center>
				</span>

				<span class="contact100-form-title" style="font-size:18px;text-align: justify;padding:20px;background-color:#eee">
				PT. Yamaha Musical Products Indonesia memperhatikan keselamatan karyawan kami dan tamu. Kami memperhatikan dengan seksama perkembangan pandemi COVID19 dan demi memastikan keselamatan dan kesehatan lingkungan Kerja, maka kami meminta agar anda menjawab dengan jujur beberapa pertanyaan berikut ini. <br>YMPIは常にCovid-19の進行状況を監視し、従業員及びお客様の安全を考えます。職場の安全衛生を確実にするため下記の質問を答えて下さい。 
				</span>

				<span class="contact100-form-title" style="font-size:16px;text-align: left;padding:20px;">
					<i style="color: red"><b>NOTE:</b></i>
					<span style="color: red">Ketika anda berada di PT. YMPI maka wajib mengikuti protokol kesehatan yang berlaku.</span>
					<br>
					<span style="color: red">注意：YMPIにいらっしゃる時に必ず決定したプロトコルを守ってください。</span>
					<br>
					<span  style="color: red;font-size: 14px !important;">
					<br><b>a.	Harus menggunakan masker medis serta faceshield dan atau masker N95. <br>メディカルマスク+フェースシールド又はN95マスクをかけてください。
					<br>b.	Mematuhi protokol kesehatan di YMPI. <br>コロナ感染防止プロトコルを守ってください。
					<ul>
						<li>&nbsp;&nbsp;&nbsp; 1.	Cek suhu dan melewati bilik sterilisasi di pos security
							<br>体温を測定し、消毒ゲートを通り抜けてください。</li>
						<li>&nbsp;&nbsp;&nbsp; 2.	Menjaga jarak min 1,5m
							<br>最低1.5メートルの距離を確保してください。</li>
						<li>&nbsp;&nbsp;&nbsp; 3.	Selalu mencuci tangan dan membawa sapu tangan pribadi
							<br>常に手を洗い、自分のハンカチを持ってきてください。</li>
						<li>&nbsp;&nbsp;&nbsp; 4.	Membawa peralatan ibadah dan peralatan makan pribadi
							<br>自分の食器を使ってください。</li>
						<li>&nbsp;&nbsp;&nbsp; 5.	Jika suhu tubuh > 37.5∘C maka tidak diperbolehkan memasuki area PT. YMPI<br>
						体温が> 37.5∘Cになった場合、YMPIに入ってはいけません。 </li>
					</ul></b>
					</span>

					<br>Terima kasih atas kerjasamanya <br>ご協力ありがとうございます。
					<br>
				</span>
			</div>
		</div>
		<div class="wrap-contact100 col-xs-12 col-md-6" style="padding: 0 20px;margin-left: 20px">
			<div id="belum_mengisi" style="width: 100%;">

			<input type="hidden" value="{{csrf_token()}}" name="_token" />


				<span class="contact100-form-title" style="color: white;background-color: #605ca8;padding-bottom: 0;text-align: center;font-size: 2.0vw;font-weight: bold;margin-top: 20px;padding: 10px">
					Guest Self Assessment (GSA-COVID19)
				</span>
				
      			<input type="hidden" id="jml_pertanyaan" value="4">

				<label class="label-input1002" id="labelnama">Nama 名前</label>
				<input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap">

				<label class="label-input1002" id="labeldept">Perusahaan 会社名</label>
				<input type="text" class="form-control" id="company" name="company" placeholder="Contoh : PT. YMPI">

				<label class="label-input1002" id="labeldept">Nomor Telepon 電話番号</label>
				<input type="text" class="form-control" id="phone" name="phone" placeholder="Nomor Handphone">

				<label class="label-input1002" id="labeltanggaldari">Tanggal Dari 訪問日付（~日から）</label>
				<input type="text" class="form-control datepicker" id="date_from" name="date_from" placeholder=" Tanggal Kunjungan Dari">
				
				<label class="label-input1002" id="labeltanggalsampai">Tanggal Sampai 訪問日付（~日まで）</label>
				<input type="text" class="form-control datepicker" id="date_to" name="date_to" placeholder=" Tanggal Kunjungan Sampai">

				<label class="label-input1002" id="labelreason">Keperluan ご用件</label>
				<input type="text" class="form-control" id="reason" name="reason" placeholder="Contoh : Perbaikan Mesin, Trial Project">

				<label class="label-input1002" id="labelpic">PIC YMPI YMPI担当者名</label>
				<input type="text" class="form-control" id="pic" name="pic" placeholder="Contoh : Ani-HRGA">

				<label class="label-input1002" id="pertanyaan0">1. Apakah anda mungkin pernah kontak atau tinggal satu rumah dengan orang terkonfirmasi positif COVID19 dalam 14 hari terakhir 過去 14日間以内に感染者と接触および同居してみますか ? <span style="color:red">*</span></label>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="radio" style="margin-top: 5px;margin-left: 5px">Iya はい
						<input type="radio"  id="jawaban0" name="jawaban0" value="Iya" onclick="checkJawaban()">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px">Tidak いいえ
						<input type="radio" id="jawaban0" name="jawaban0" value="Tidak" onclick="checkJawaban()">
						<span class="checkmark"></span>
					</label>
				</div>

				<label class="label-input1002" id="pertanyaan1">2. Apakah anda pernah bepergian ke luar negeri atau domestik antar provinsi dalam 14 Hari terakhir 過去14日間以内に県外・国外に行きましたか ? <span style="color:red">*</span></label>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="radio" style="margin-top: 5px;margin-left: 5px">Iya はい
						<input type="radio"  id="jawaban1" name="jawaban1" value="Iya" onclick="checkJawaban()">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px">Tidak いいえ
						<input type="radio" id="jawaban1" name="jawaban1" value="Tidak" onclick="checkJawaban()">
						<span class="checkmark"></span>
					</label>
				</div>

				<label class="label-input1002" id="pertanyaan2">3. Apakah anda akan melakukan pertemuan di dalam ruangan selama > 30 Menit 30分以上室内会議に参加する予定はありますか ? <span style="color:red">*</span></label>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="radio" style="margin-top: 5px;margin-left: 5px">Iya はい
						<input type="radio"  id="jawaban2" name="jawaban2" value="Iya" onclick="checkJawaban()">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px">Tidak いいえ
						<input type="radio" id="jawaban2" name="jawaban2" value="Tidak" onclick="checkJawaban()">
						<span class="checkmark"></span>
					</label>
				</div>

				<label class="label-input1002" id="pertanyaan3">4. Apakah anda merasakan gejala – gejala seperti 下記の病状はありますか : <span style="color:red">*</span></label>
					<ul style="margin: 15px; padding: 10px">
						<li style="list-style-type: disc;">Demam (suhu badan > 37.5ºC) 発熱（体温 > 37.5°C）</li>
						<li style="list-style-type: disc;">Batuk – batuk 咳</li>
						<li style="list-style-type: disc;">Pernapasan cepat 息切れ</li>
						<li style="list-style-type: disc;">Dahak kental (kuning – kehijauan) 痰唾</li>
						<li style="list-style-type: disc;">Badan terasa ngilu 関節痛、筋肉痛、だるさ</li>
					</ul>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="radio" style="margin-top: 5px;margin-left: 5px">Iya はい
						<input type="radio"  id="jawaban3" name="jawaban3" value="Iya" onclick="checkJawaban()">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px">Tidak いいえ
						<input type="radio" id="jawaban3" name="jawaban3" value="Tidak" onclick="checkJawaban()">
						<span class="checkmark"></span>
					</label>
				</div>

				<!-- <label class="label-input1002" id="pertanyaan4">5. Apakah anda telah mendapatkan vaksin COVID19 COVID-19バークチン接種受けましたか ? <span style="color:red">*</span></label>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="radio" style="margin-top: 5px;margin-left: 5px">Iya はい
						<input type="radio"  id="jawaban4" name="jawaban4" value="Iya" onclick="changeJawaban(this.value)">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px">Tidak いいえ
						<input type="radio" id="jawaban4" name="jawaban4" value="Tidak" onclick="changeJawaban(this.value)">
						<span class="checkmark"></span>
					</label>
				</div> -->

				<label class="label-input1002" id="labellokasi">Flow Penanganan K3 Tamu :</label>
				<a href="{{ url('ympi_guest.png') }}" target="_blank" style="color:blue" onclick="viewImage()">Klik Untuk Melihat Gambar</a>
				<span style="color:red;font-size: 14px;"> (Mohon dibaca dan dipahami terkait aturan diatas)</span>
				<span id="cek_gambar" style="display:none"></span>

				<label class="label-input1002" id="labellokasi">Asal Provinsi</label>
				<a href="javascript:void(0)" style="border-color: black; color: black;" id="btn_non_jatim" onclick="btnCategory('non_jatim')" class="btn btn-sm">Non Jawa Timur</a>
				<a href="javascript:void(0)" style="border-color: black; color: black;" id="btn_jatim" onclick="btnCategory('jatim')" class="btn btn-sm">Jawa Timur</a>
				<input type="hidden" id="createCategory">

				<div id="kategori_vaksin_jatim">
					<label class="label-input1002" id="labelkategori">Kategori Vaksin</label>
					<a href="javascript:void(0)" style="border-color: black; color: black;" id="btn_belum_vaksin" onclick="check_vaksin('Belum Vaksin')" class="btn btn-sm">Belum Vaksin</a>
					<a href="javascript:void(0)" style="border-color: black; color: black;" id="btn_sudah_vaksin" onclick="check_vaksin('Sudah Vaksin')" class="btn btn-sm">Sudah Vaksin</a>
				</div>

				<div id="kategori_vaksin_non_jatim">
					<label class="label-input1002" id="labelkategori">Kategori Vaksin</label>
					<a href="javascript:void(0)" style="border-color: black; color: black;" id="btn_belum_vaksin_non_jatim" onclick="check_vaksin('Belum Vaksin Non Jatim')" class="btn btn-sm">Belum Vaksin</a>
					<a href="javascript:void(0)" style="border-color: black; color: black;" id="btn_vaksin_1" onclick="check_vaksin('Sudah Vaksin Dosis 1')" class="btn btn-sm">Vaksin Dosis 1</a>
					<a href="javascript:void(0)" style="border-color: black; color: black;" id="btn_vaksin_2" onclick="check_vaksin('Sudah Vaksin Dosis 2')" class="btn btn-sm">Vaksin Dosis 2</a>
					<a href="javascript:void(0)" style="border-color: black; color: black;" id="btn_vaksin_3" onclick="check_vaksin('Sudah Vaksin Dosis 3')" class="btn btn-sm">Vaksin Dosis 3</a>
				</div>


				<input type="hidden" id="createVaksin">


				<div id="vaksin">
					<label class="label-input1002">Lampiran Sertifikat Vaksin <span style="color:red">*</span></label>

					<input type="file" name="file_vaksin" id="file_vaksin" value="Upload Bukti Vaksin">
				</div>

				<div id="pcr">
					<label class="label-input1002">Lampiran PCR <span style="color:red">*</span></label>

					<input type="file" name="file_pcr" id="file_pcr" value="Upload File PCR">
				</div>

				<div id="rapid">
					<label class="label-input1002">Lampirkan Hasil Rapid Antigen Maksimal H-3 Sebelum Tiba di PT. YMPI <span style="color:red">*</span></label>

					<input type="file" name="file_rapid" id="file_rapid" value="Upload Bukti Rapid">
				</div>

				<!-- demam, batuk, kejadian, tenggorokan sakit, sesak nafas, indera perasa & penciuman terganggu,  -->
			
				<div class="container-contact100-form-btn" style="margin-top: 20px">
					<button class="contact100-form-btn" onclick="save()">
						<span>
							Submit
							<i class="fa fa-save"></i>
						</span>
					</button>
				</div>
				<br><br>
	      </div>
	      <div id="sudah_mengisi" style="width: 100%;padding: 170px 20px">
					<div class="col-xs-12 col-md-12">
          	<center style="font-size:16px">Terimakasih Bapak / Ibu <span class="name_assessment"></span> telah Mengisi Assessment ini. <div id="resiko_covid"></div></i></u></center>
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
		$('#vaksin').hide();
		$('#rapid').hide();
		$('#pcr').hide();


		$('.datepicker').datepicker({
			autoclose: true,
			format: "yyyy-mm-dd",
			todayHighlight: true,
		});

		$('#kategori_vaksin_jatim').hide();
		$('#kategori_vaksin_non_jatim').hide();
	});
	
	$.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }); 

    function viewImage(){
    	$("#cek_gambar").html('Sudah');
    }

	function save() {
		$("#loading").show();

		if ($("#cek_gambar").text() == "") {
			$("#loading").hide();
			openErrorGritter('Error!', 'Mohon Baca dan Pahami Aturan K3 dengan klik link pada K3');
			return false;
		}

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

		if($("#phone").val() == ""){
		    $("#loading").hide();
			openErrorGritter('Error!', 'Pastikan Nomor Telepon Anda Sudah Diisi');
			return false;
		}

		if ($('input[id="jawaban0"]:checked').val() == "Iya") {
		    $("#loading").hide();
			openErrorGritter('Error!', 'Mohon maaf Hasil Assessment Anda Telah Kami Tolak');
			return false;
		}
		else if ($('input[id="jawaban3"]:checked').val() == "Iya") {
		    $("#loading").hide();
			openErrorGritter('Error!', 'Mohon maaf Hasil Assessment Anda Telah Kami Tolak');
			return false;
		}


		if ($('#createCategory').val() == "") {
			$("#loading").hide();
			openErrorGritter('Error!', 'Pastikan Kolom Provinsi Sudah Diisi');
			return false;
		}

		if ($('#createVaksin').val() == "") {
			$("#loading").hide();
			openErrorGritter('Error!', 'Pastikan Data Vaksin Sudah Diisi');
			return false;
		}

		var pertanyaan = [];
		var jawaban = [];
		var jumlah_pertanyaan = $("#jml_pertanyaan").val();

		for(var i = 0;i < jumlah_pertanyaan; i++){
			var question = '#pertanyaan'+i;
			pertanyaan.push($(question).text());

			var answer = 'jawaban'+i;

			if ($('input[id="'+answer+'"]:checked').val() == null) {
				$("#loading").hide();
				openErrorGritter('Error!', 'Semua Kolom (<b>*</b>) Harus Diisi.');
				return false;
			}

			jawaban.push($('input[id="'+answer+'"]:checked').val());
		}

		// if ($('input[id="jawaban4"]:checked').val() == "Iya" && $('#file_vaksin').val() == '') {
		if (($('#createVaksin').val() == "Belum Vaksin" || $('#createVaksin').val() == "Belum Vaksin Non Jatim" || $('#createVaksin').val() == "Sudah Vaksin Dosis 1") && $('#file_pcr').val() == '') {
			$("#loading").hide();
			openErrorGritter('Error!', 'Hasil PCR Harus Dilampirkan');
			return false;
		}
		else if ($('#createVaksin').val() == "Sudah Vaksin Dosis 2" && $('#file_rapid').val() == ''){
			$("#loading").hide();
			openErrorGritter('Error!', 'Sertifikat Rapid Antigen Harus Dilampirkan');
			return false;
		}
		else if (($('#createVaksin').val() == "Sudah Vaksin" || $('#createVaksin').val() == "Sudah Vaksin Dosis 3") && $('#file_vaksin').val() == ''){
			$("#loading").hide();
			openErrorGritter('Error!', 'Sertifikat Vaksin Harus Dilampirkan');
			return false;
		}
		
		// 
		else if ($('input[id="jawaban0"]:checked').val() == "Iya" || $('input[id="jawaban1"]:checked').val() == "Iya" || $('input[id="jawaban2"]:checked').val() == "Iya" || $('input[id="jawaban3"]:checked').val() == "Iya" ) {
			
			if (($('#createVaksin').val() == "Belum Vaksin" || $('#createVaksin').val() == "Belum Vaksin Non Jatim" || $('#createVaksin').val() == "Sudah Vaksin Dosis 1") && $('#file_pcr').val() == '') {
					// $("#loading").hide();
					// openErrorGritter('Error!', 'Hasil PCR Harus Dilampirkan');
					// return false;
			}
			else if ($('#createVaksin').val() == "Sudah Vaksin" || $('#createVaksin').val() == "Sudah Vaksin Dosis 2" || $('#createVaksin').val() == "Sudah Vaksin Dosis 3"){
				// $("#loading").hide();
				// openErrorGritter('Error!', 'Sertifikat Vaksin Harus Dilampirkan');
				// return false;
			}
		}


		var formData = new FormData();

		formData.append('name', $('#name').val());				
		formData.append('company', $('#company').val());
		formData.append('phone', $('#phone').val());
		formData.append('date_from', $('#date_from').val());
		formData.append('date_to', $('#date_to').val());
		formData.append('reason', $('#reason').val());
		formData.append('pic', $('#pic').val());
		formData.append('phone', $('#phone').val());
		formData.append('location', $('#createCategory').val());
		formData.append('vaksin', $('#createVaksin').val());
		formData.append('question', pertanyaan);
		formData.append('answer', jawaban);

		formData.append('file_vaksin', $('#file_vaksin').prop('files')[0]);
		formData.append('file_rapid', $('#file_rapid').prop('files')[0]);
		formData.append('file_pcr', $('#file_pcr').prop('files')[0]);

		$.ajax({
			url:"{{ url('post/guest_assessment') }}",
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

		// var data = {
		// 	name : $('#name').val(),
		// 	company : $('#company').val(),
		// 	phone : $('#phone').val(),
		// 	question: pertanyaan,
		// 	answer: jawaban
		// }

		// $.post('{{ url("post/guest_assessment") }}', data, function(result, status, xhr){
		// 	if(result.status == true){
		// 		$("#name_assessment").val($('#name').val())
		// 		$('#resiko_covid').html('Mohon tunggu kabar selanjutnya dari kami');

		// 		openSuccessGritter("Success","Berhasil Dibuat");
  		//      $("#loading").hide();
				
		// 		$('#belum_mengisi').hide();
		// 		$('#sudah_mengisi').show();
		// 	}
		// 	else {
		// 		$("#loading").hide();
		// 		openErrorGritter('Error!', result.datas);
		// 	}
		// })
	}

	function changeJawaban(value) {
		// if (value === 'Iya') {
		// 	if ($('#createCategory').val() == "non_jatim") {
		// 		$('#pcr').show();
		// 		$('#vaksin').hide();
		// 		$('#rapid').hide();
		// 	}else{
		// 		$('#pcr').hide();
		// 		$('#vaksin').show();
		// 		$('#rapid').hide();
		// 	}
		// }
		// else{
		// 	$('#vaksin').hide();
		// 	var jumlah_pertanyaan = $("#jml_pertanyaan").val();
		// 	var stat = 0;
			
		// 	for(var i = 0;i < jumlah_pertanyaan; i++){	
		// 		var answer = 'jawaban'+i;

		// 		if ($('input[id="'+answer+'"]:checked').val() == "Iya") {
		// 			stat = 1;
		// 		}
		// 	}

		// 	if (stat == 1) {	
		// 		if ($('#createCategory').val() == "non_jatim") {
		// 			$('#pcr').show();
		// 			$('#vaksin').hide();
		// 			$('#rapid').hide();
		// 		}else{
		// 			$('#pcr').hide();
		// 			$('#rapid').show();
		// 			$('#rapid').hide();
		// 		}
		// 	}
		// 	else{
		// 		$('#rapid').hide();
		// 	}
		// }
	}

	function checkJawaban(){
		var jumlah_pertanyaan = $("#jml_pertanyaan").val();

		if ($('input[id="jawaban0"]:checked').val() == "Iya") {
			$("#loading").hide();
			openErrorGritter('Error!', 'Mohon maaf Hasil Assessment Anda Telah Kami Tolak');
			return false;
		}
		else if ($('input[id="jawaban3"]:checked').val() == "Iya") {
			$("#loading").hide();
			openErrorGritter('Error!', 'Mohon maaf Hasil Assessment Anda Telah Kami Tolak');
			return false;
		}
		
		// if ($('input[id="jawaban4"]:checked').val() != "Iya") {
		// 	if ($('input[id="jawaban0"]:checked').val() == "Iya" || $('input[id="jawaban1"]:checked').val() == "Iya" || $('input[id="jawaban2"]:checked').val() == "Iya" || $('input[id="jawaban3"]:checked').val() == "Iya") {
		// 		$('#rapid').show();
		// 	}
		// 	else{
		// 		$('#rapid').hide();
		// 	}
		// }
	}

	function btnCategory(cat){
		
		$('#btn_non_jatim').css('background-color', 'white');
		$('#btn_jatim').css('background-color', 'white');
		$('#btn_'+cat).css('background-color', '#90ed7d');

		if (cat == "non_jatim") {
			$('#kategori_vaksin_jatim').hide();
			$('#kategori_vaksin_non_jatim').show();
		}else {

			$('#kategori_vaksin_jatim').show();
			$('#kategori_vaksin_non_jatim').hide();
		}

		$('#createCategory').val(cat);
	}

	function check_vaksin(cat){
		
		$('#btn_sudah_vaksin').css('background-color', 'white');
		$('#btn_belum_vaksin').css('background-color', 'white');
		$('#btn_vaksin_1').css('background-color', 'white');
		$('#btn_vaksin_2').css('background-color', 'white');
		$('#btn_vaksin_3').css('background-color', 'white');
		$('#btn_belum_vaksin_non_jatim').css('background-color', 'white');

		// $('#btn_'+cat).css('background-color', '#90ed7d');

		if (cat == "Sudah Vaksin") {
			$('#btn_sudah_vaksin').css('background-color', '#90ed7d');
			$('#pcr').hide();
			$('#vaksin').show();
			$('#rapid').hide();
		}else if(cat == "Belum Vaksin"){
			$('#btn_belum_vaksin').css('background-color', '#90ed7d');
			$('#pcr').show();
			$('#vaksin').hide();
			$('#rapid').hide();
		}else if(cat == "Sudah Vaksin Dosis 1"){
			$('#btn_vaksin_1').css('background-color', '#90ed7d');
			$('#pcr').show();
			$('#vaksin').hide();
			$('#rapid').hide();
		}else if(cat == "Sudah Vaksin Dosis 2"){
			$('#btn_vaksin_2').css('background-color', '#90ed7d');
			$('#pcr').hide();
			$('#vaksin').hide();
			$('#rapid').show();
		}else if(cat == "Sudah Vaksin Dosis 3"){
			$('#btn_vaksin_3').css('background-color', '#90ed7d');
			$('#pcr').hide();
			$('#vaksin').show();
			$('#rapid').hide();
		}else if(cat == "Belum Vaksin Non Jatim"){
			$('#btn_belum_vaksin_non_jatim').css('background-color', '#90ed7d');
			$('#pcr').show();
			$('#vaksin').hide();
			$('#rapid').hide();
		}

		$('#createVaksin').val(cat);
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