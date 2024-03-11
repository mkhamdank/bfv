<!DOCTYPE html>
<html lang="">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PT. YMPI - Work Permit With Enviromental & Safety Analysis</title>
	<!-- <link rel="shortcut icon" type="image/x-icon" href="{{ url('logo_mirai_bundar.png')}}" /> -->

	<link rel="stylesheet" type="text/css" href="{{ url('bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('font-awesome.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('css/main.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('css/jquery.gritter.css') }}" >
	<link rel="stylesheet" href="{{ url('bootstrap-datepicker.css')}}">

	<style type="text/css">
/* 
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
		} */

		.container-contact100 {
			background: url('ympi.jpg') no-repeat fixed left;
		}


		.form-control {
			border-radius: 0;
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

		.checkbox {
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

		/* Hide the browser's default checkbox button */
		.checkbox input {
			position: absolute;
			opacity: 0;
			cursor: pointer;
		}

		/* On mouse-over, add a grey background color */
		.checkbox:hover input ~ .checkboxmark {
			background-color: #ccc;
		}

		/* When the checkbox button is checked, add a blue background */
		.checkbox input:checked ~ .checkboxmark {
			background-color: #2196F3;
		}

		.checkboxmark {
			position: absolute;
			top: 0;
			left: 0;
			height: 25px;
			width: 25px;
			background-color: #ccc;
		}

		/* Create the indicator (the dot/circle - hidden when not checked) */
		.checkboxmark:after {
			content: "";
			position: absolute;
			display: none;
		}

		/* Show the indicator (dot/circle) when checked */
		.checkbox input:checked ~ .checkboxmark:after {
			display: block;
		}

		/* Style the indicator (dot/circle) */
		.checkbox .checkboxmark:after {
			top: 9px;
			left: 9px;
			width: 8px;
			height: 8px;
			background: white;
		}

		p{
			color: white !important;
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
		<div class="wrap-contact100 col-xs-12 col-md-8" style="padding: 0 20px;margin-left: 20px">
			<div id="belum_mengisi" style="width: 100%;">
				<span class="contact100-form-title" style="color: white;background-color: #605ca8;text-align: center;font-weight: bold;margin-top: 10px;padding-bottom: 0;padding: 10px;border-radius: 10px;font-size: 22px">
					<span><b>PT. Yamaha Musical Products Indonesia</b></span><br>
					<span style="font-size: 16px">Work Permit With Enviromental & Safety Analysis</span>
				</span>

				<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
					<span>Informasi Vendor</span><br>
				</span>

  			<input type="hidden" value="{{csrf_token()}}" name="_token" />
  			<input type="hidden" id="jml_pertanyaan" value="5">
				<label class="label-input1002" id="labelnama">Nama Perusahaan <span style="color: red">*</span></label>
				<input type="text" class="form-control" id="company_name" name="company_name" placeholder="">

				<label class="label-input1002" id="labeldept">Alamat Perusahaan <span style="color: red">*</span></label>
				<input type="text" class="form-control" id="company_address" name="company_address" placeholder="">

				<label class="label-input1002" id="labeldept">Email <span style="color: red">*</span></label>
				<input type="email" class="form-control" id="company_email" name="company_email" placeholder="">

				<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
					<span>Rencana Kunjungan</span><br>
					<span style="font-size: 11px">Kapan Pekerjaan Tersebut Akan Dilaksanakan ?</span>
				</span>

				<label class="label-input1002" id="labeltanggaldari">Tanggal Dari <span style="color: red">*</span></label>
				<input type="date" class="form-control" id="date_from" name="date_from" placeholder="">
				
				<label class="label-input1002" id="labeltanggalsampai">Tanggal Sampai <span style="color: red">*</span></label>
				<input type="date" class="form-control" id="date_to" name="date_to" placeholder="">

				<!-- <label class="label-input1002" id="labelreason">Keperluan</label>
				<input type="text" class="form-control" id="reason" name="reason" placeholder="Contoh : Perbaikan Mesin, Trial Project"> -->

				<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
					<span>Penanggung Jawab</span><br>
				</span>

				<label class="label-input1002" id="labelpic">Nama Penanggung Jawab <span style="color: red">*</span></label>
				<input type="text" class="form-control" id="company_pic" name="company_pic" placeholder="">

				<label class="label-input1002" id="labeljabatan">Jabatan</label>
				<input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="">

				<label class="label-input1002" id="labelnohp">Nomor HP <span style="color: red">*</span></label>
				<input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="">

				<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
					<span>Detail Pekerjaan</span>
				</span>

				<label class="label-input1002" id="labeldept">Jenis Pekerjaan <span style="color: red">*</span></label>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="jenis_pekerjaanCheckbox" name="jenis_pekerjaan" value="Konstruksi">
						<span class="checkboxmark"></span>
						Konstruksi
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="jenis_pekerjaanCheckbox" name="jenis_pekerjaan" value="Sipil">
						<span class="checkboxmark"></span>
						Sipil
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="jenis_pekerjaanCheckbox" name="jenis_pekerjaan" value="Mekanik">
						<span class="checkboxmark"></span>
						Mekanik
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="jenis_pekerjaanCheckbox" name="jenis_pekerjaan" value="Listrik">
						<span class="checkboxmark"></span>
						Listrik
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="jenis_pekerjaanCheckbox" name="jenis_pekerjaan" value="Perbaikan">
						<span class="checkboxmark"></span>
						Perbaikan
					</label>
				</div>

				<!-- <input type="jenis" class="form-control" id="jenis" name="jenis" placeholder=""> -->

				<label class="label-input1002" id="labeldept">Deskripsi Pekerjaan <span style="color: red">*</span></label>
				<input type="deskripsi" class="form-control" id="deskripsi" name="deskripsi" placeholder="">

				<label class="label-input1002" id="labeldept">Lokasi Kerja <span style="color: red">*</span></label>
				<input type="lokasi" class="form-control" id="lokasi" name="lokasi" placeholder="">

				<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
					<span>Potensi Bahaya & Ketentuan Kerja</span>
				</span>

				<br>

				<b>Bahaya yang mungkin timbul </b><br><br>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="bahayaCheckbox" name="bahaya" value="Terhisap Gas Beracun">
						<span class="checkboxmark"></span>
						Terhisap Gas Beracun
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="bahayaCheckbox" name="bahaya" value="Terkena Bahan Kimia">
						<span class="checkboxmark"></span>
						Terkena Bahan Kimia
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="bahayaCheckbox" name="bahaya" value="Terkena Putaran/Gerakan Mesin/Alat Kerja">
						<span class="checkboxmark"></span>
						Terkena Putaran/Gerakan Mesin/Alat Kerja
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="bahayaCheckbox" name="bahaya" value="Kebakaran/Ledakan">
						<span class="checkboxmark"></span>
						Kebakaran/Ledakan
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="bahayaCheckbox" name="bahaya" value="Jatuh/Terpeleset/Terbentur">
						<span class="checkboxmark"></span>
						Jatuh/Terpeleset/Terbentur
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="bahayaCheckbox" name="bahaya" value="Sengatan Aliran Listrik">
						<span class="checkboxmark"></span>
						Sengatan Aliran Listrik
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="bahayaCheckbox" name="bahaya" value="Tertimpa/Terkena Benda Jatuh">
						<span class="checkboxmark"></span>
						Tertimpa/Terkena Benda Jatuh
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="bahayaCheckbox" name="bahaya" value="Radiasi/Panas">
						<span class="checkboxmark"></span>
						Radiasi/Panas
					</label>
				</div>

				<br>

				<b>Aspek Lingkungan Yang Mungkin Timbul </b><br><br>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="lingkunganCheckbox" name="lingkungan" value="Ceceran Limbah/Bahan Berbahaya dan Beracun (B3)">
						<span class="checkboxmark"></span>
						Ceceran Limbah/Bahan Berbahaya dan Beracun (B3)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="lingkunganCheckbox" name="lingkungan" value="Pemakaian Sumber Daya Listrik/Air/Kompressor">
						<span class="checkboxmark"></span>
						Pemakaian Sumber Daya Listrik/Air/Kompressor
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="lingkunganCheckbox" name="lingkungan" value="Ceceran Serbuk Logam/Kayu dan bahan pada lainnya">
						<span class="checkboxmark"></span>
						Ceceran Serbuk Logam/Kayu dan bahan pada lainnya
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="lingkunganCheckbox" name="lingkungan" value="Kebisingan">
						<span class="checkboxmark"></span>
						Kebisingan
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="lingkunganCheckbox" name="lingkungan" value="Debu">
						<span class="checkboxmark"></span>
						Debu
					</label>
				</div>

				<br>

				<b>Hal Hal Yang Perlu Dilaksanakan </b><br><br>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="prosedurCheckbox" name="prosedur" value="Lepas V-Belt Mesin">
						<span class="checkboxmark"></span>
						Lepas V-Belt Mesin
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="prosedurCheckbox" name="prosedur" value="Matikan Mesin/Lepas Sekering">
						<span class="checkboxmark"></span>
						Matikan Mesin/Lepas Sekering
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="prosedurCheckbox" name="prosedur" value="Periksa Kabel Listrik/Slang Gas">
						<span class="checkboxmark"></span>
						Periksa Kabel Listrik/Slang Gas
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="prosedurCheckbox" name="prosedur" value="Pasang Ventilasi Di Ruangan">
						<span class="checkboxmark"></span>
						Pasang Ventilasi Di Ruangan
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="prosedurCheckbox" name="prosedur" value="Singkirkan Bahan/Cairan yang mudah terbakar">
						<span class="checkboxmark"></span>
						Singkirkan Bahan/Cairan yang mudah terbakar
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="prosedurCheckbox" name="prosedur" value="Deteksi gas berbahaya/ beracun atau meledak">
						<span class="checkboxmark"></span>
						Deteksi gas berbahaya/ beracun atau meledak
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="prosedurCheckbox" name="prosedur" value="Pasang blower/ penghisap udara">
						<span class="checkboxmark"></span>
						Pasang blower/ penghisap udara
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="prosedurCheckbox" name="prosedur" value="Pasang pagar/ terpal pengaman">
						<span class="checkboxmark"></span>
						Pasang pagar/ terpal pengaman
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="prosedurCheckbox" name="prosedur" value="Pasang welding curtain/ blanket">
						<span class="checkboxmark"></span>
						Pasang welding curtain/ blanket
					</label>
				</div>

				
				<br>

				<b>Alat Safety yang harus dipakai dan tersedia di lokasi </b><br><br>

				<div class="validate-input" style="position: relative; width: 100%">
					1. Alat Pemadam / APAR
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="safety0" name="safety0" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="safety0" name="safety0" value="Iya">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
				</div>
				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					2. Safety Belt/Full Body Harness
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="safety1" name="safety1" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="safety1" name="safety1" value="Iya">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
				</div>
				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					3. Safety Helmet
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="safety2" name="safety2" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="safety2" name="safety2" value="Iya">
						<span class="checkmark"></span>
					</label>
				</div>

				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					4. Safety Shoes
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="safety3" name="safety3" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="safety3" name="safety3" value="Iya">
						<span class="checkmark"></span>
					</label>
				</div>

				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					5. Sarung Tangan
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="safety4" name="safety4" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="safety4" name="safety4" value="Iya">
						<span class="checkmark"></span>
					</label>
				</div>

				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					6. Masker
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="safety5" name="safety5" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="safety5" name="safety5" value="Iya">
						<span class="checkmark"></span>
					</label>
				</div>

				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					7. Kaca Mata Pelindung
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="safety6" name="safety6" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="safety6" name="safety6" value="Iya">
						<span class="checkmark"></span>
					</label>
				</div>

				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					8. Celemek/Apron
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="safety7" name="safety7" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="safety7" name="safety7" value="Iya">
						<span class="checkmark"></span>
					</label>
				</div>

				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					9. Tutup Muka/Face Shield
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="safety8" name="safety8" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="safety8" name="safety8" value="Iya">
						<span class="checkmark"></span>
					</label>
				</div>

				<br>
				<b>Papan Peringatan Yang Harus Dipasang</b><br><br>

				<div class="validate-input" style="position: relative; width: 100%">
					1. Awas Bahaya Api
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="peringatan0" name="peringatan0" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="peringatan0" name="peringatan0" value="Iya">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
				</div>
				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					2. Awas Bahaya Dari Atas
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="peringatan1" name="peringatan1" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="peringatan1" name="peringatan1" value="Iya">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
				</div>
				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					3. Mesin Diperbaiki
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="peringatan2" name="peringatan2" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="peringatan2" name="peringatan2" value="Iya">
						<span class="checkmark"></span>
					</label>
				</div>

				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					4. Awas Bahaya Listrik
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="peringatan3" name="peringatan3" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="peringatan3" name="peringatan3" value="Iya">
						<span class="checkmark"></span>
					</label>
				</div>

				<br>
				<div class="validate-input" style="position: relative; width: 100%">
					5. Awas Lantai Licin
					<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
						<input type="radio" id="peringatan4" name="peringatan4" value="Tidak">
						<span class="checkmark"></span>
					</label>
					&nbsp;&nbsp;
					<label class="radio" style="margin-top: 5px;float: right;">Iya
						<input type="radio"  id="peringatan4" name="peringatan4" value="Iya">
						<span class="checkmark"></span>
					</label>
				</div>

				<br>

				<b>Ketentuan-Ketentuan Lain </b><br><br>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="ketentuanCheckbox" name="ketentuan" value="Dilarang Merokok">
						<span class="checkboxmark"></span>
						Dilarang Merokok
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="ketentuanCheckbox" name="ketentuan" value="Dilarang Las Karbit">
						<span class="checkboxmark"></span>
						Dilarang Las Karbit
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="ketentuanCheckbox" name="ketentuan" value="Dilarang Las Listrik">
						<span class="checkboxmark"></span>
						Dilarang Las Listrik
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="ketentuanCheckbox" name="ketentuan" value="Dilarang Las Acetylene">
						<span class="checkboxmark"></span>
						Dilarang Las Acetylene
					</label>
				</div>
				<br>
				<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
					<span>
						PIC YMPI
					</span>
				</span>
				<br>

				<b>Department/Section Terkait <span style="color: red">*</span></b><br>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="Quality Assurance Department">
						<span class="checkboxmark"></span>
						Quality Assurance (QA)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="Maintenance Department">
						<span class="checkboxmark"></span>
							Plant Maintenance (PM)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="General Affairs Department">
						<span class="checkboxmark"></span>
							General Affairs (GA)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="Human Resources Department">
						<span class="checkboxmark"></span>
							Human Resources (HR)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="Production Engineering Department">
						<span class="checkboxmark"></span>
							Production Engineering (PE)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="Management Information System Department">
						<span class="checkboxmark"></span>
							Management Information System (MIS)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="Logistic Department">
						<span class="checkboxmark"></span>
							Logistic (LOG)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="Woodwind Instrument - Parts Process (WI-PP) Department">
						<span class="checkboxmark"></span>
							Production (Parts Process Dept.)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="Woodwind Instrument - Welding Process (WI-WP) Department">
						<span class="checkboxmark"></span>
							Production (Welding Process Dept.)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="Woodwind Instrument - Surface Treatment (WI-ST) Department">
						<span class="checkboxmark"></span>
						Production (Surface Treatment Process Dept.)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="checkbox" class="departemenCheckbox" name="departemen" value="Woodwind Instrument - Final Assembly (WI-FA) Department">
						<span class="checkboxmark"></span>
							Production (Final Assembly Dept.)
					</label>
				</div>


				<label class="label-input1002" id="labelnama">Pihak Penanggung Jawab dari YMPI <span style="color: red">*</span></label>
				<input type="text" class="form-control" id="pic_ympi" name="pic_ympi" placeholder="">
				<br>

				<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;padding: 10px;font-size: 16px;">
					<span>
						Catatan
						<br>◆ Tandai kolom "checkboxes" diatas sebagai perhatian khusus dan telah dikerjakan
						<br>◆ Bekerja dalam jarak ± 10 m dari bahan yang mudah terbakar harus di-cover/isolasi dengan aman
						<br>◆ Pekerjaan hanya boleh dikerjakan setelah Work Permit ini di approve dan anda telah menerima email konfirmasi
						<br>◆ Penanggung jawab vendor harus memastikan 5S & keselamatan lokasi area kerja setelah selesai pekerjaan
					</span>
				</span>

				<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;padding: 10px;font-size: 16px;">
					<span>
						SUB FORM
						<br>Lampiran yang harus diisi berdasarkan kategori pekerjaan yang dilakukan
					</span>
				</span>
				<br>

				<b>Dari work permit yang telah dibuat terdapat beberapa sub form yang harus dilengkapi lagi sesuai dengan jenis pekerjaannya <span style="color: red">*</span></b><br><br>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="radio" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="radio" id="work_permit" name="work_permit" value="Height Permit" onchange="ChangePermit(this)">
						<span class="checkmark"></span>
						1. Work at Height Permit (Apabila dari ijin kerja yang telah dibuat ada pekerjaan di ketinggian > 2,5 meter)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="radio" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="radio" id="work_permit" name="work_permit" value="Hot Work Permit" onchange="ChangePermit(this)">
						<span class="checkmark"></span>
						2. Hot Work Permit (Apabila dari ijin kerja yang telah dibuat ada pekerjaan yang menimbulkan panas)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="radio" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="radio" id="work_permit" name="work_permit" value="Confined Space Permit" onchange="ChangePermit(this)">
						<span class="checkmark"></span>
						3. Confined Space Permit (Apabila dari ijin kerja yang telah dibuat ada pekerjaan di area terbatas)
					</label>
				</div>

				<div class="validate-input" style="position: relative; width: 100%">
					<label class="radio" style="margin-top: 5px;margin-left: 25px;"> 
						<input type="radio" id="work_permit" name="work_permit" value="None" onchange="ChangePermit(this)">
						<span class="checkmark"></span>
						4. Selesai (Pernyataan Vendor)
					</label>
				</div>

				<div id="height_permit" style="display:none">
					<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
					<span>WORK at HEIGHT PERMIT (IJIN KERJA UNTUK PEKERJAAN DI ATAS)</span><br>
					<span style="font-size: 11px">Berlaku untuk pekerjaan di atas/di bawah 2,5 meter dan operator tidak berlisensi</span>

					</span>


					<label class="label-input1002" id="labeljenis">Jenis Pekerjaan <span style="color: red">*</span></label>
					<input type="text" class="form-control" id="height_type" name="height_type" placeholder="">
					<br>

					<label class="label-input1002" id="labeljenis">Lokasi Pekerjaan <span style="color: red">*</span></label>
					<input type="text" class="form-control" id="height_location" name="height_location" placeholder="">
					<br>

					<b>Fall Protection <span style="color: red">*</span></b><br>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="heightProtectionCheckbox" name="height_protection" value="Safety Belt / Safety Harness">
							<span class="checkboxmark"></span>
							Safety Belt / Safety Harness
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="heightProtectionCheckbox" name="height_protection" value="Safety Net">
							<span class="checkboxmark"></span>
							Safety Net
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="heightProtectionCheckbox" name="height_protection" value="Safety Helmet">
							<span class="checkboxmark"></span>
							Safety Helmet
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="heightProtectionCheckbox" name="height_protection" value="Safety Shoes">
							<span class="checkboxmark"></span>
							Safety Shoes
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="heightProtectionCheckbox" name="height_protection" value="Safety Google">
							<span class="checkboxmark"></span>
							Safety Google
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="heightProtectionCheckbox" name="height_protection" value="Tool / Equipment Pelindung dari Resiko Kejatuhan">
							<span class="checkboxmark"></span>
							Tool / Equipment Pelindung dari Resiko Kejatuhan
						</label>
					</div>

					<br>

					<b>Warning Sign <span style="color: red">*</span></b>
					<br>
					Papan peringatan yang diperlukan : 
					<br><br>
					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="heightWarningCheckbox" name="height_warning" value="Awas Ada Pekerjaan Di Atas">
							<span class="checkboxmark"></span>
							Awas Ada Pekerjaan Di Atas
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="heightWarningCheckbox" name="height_warning" value="Clear Area (Hati- hati area disekitar kerja ada potensi kejatuhan)">
							<span class="checkboxmark"></span>
							Clear Area (Hati- hati area disekitar kerja ada potensi kejatuhan)
						</label>
					</div>

					<br>

					<b>LIFTING EQUIPMENT <span style="color: red">*</span></b>
					<br>
					Alat angkat yang digunakan sudah di pasang dengan baik dan di-cek 
					<br><br>

					<div class="validate-input" style="position: relative; width: 100%">
						1. Chain Block
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="alat0" name="alat0" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="alat0" name="alat0" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>
					<br>
					<div class="validate-input" style="position: relative; width: 100%">
						2. Crane
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="alat1" name="alat1" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="alat1" name="alat1" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>
					<br>
					<div class="validate-input" style="position: relative; width: 100%">
						3. Kerekan
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="alat2" name="alat2" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="alat2" name="alat2" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>
					<br>
					<div class="validate-input" style="position: relative; width: 100%">
						4. Palang
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="alat3" name="alat3" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="alat3" name="alat3" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>

					<br>

					<b>Jalan Masuk / Jalan Keluar <span style="color: red">*</span></b>
					<br>
					Akses jalan keluar masuk untuk pekerjaan diketinggian : 
					<br><br>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="jalanCheckbox" name="jalan" value="Tangga / Scafolding yang digunakan sudah di-inspeksi dan OK">
							<span class="checkboxmark"></span>
							Tangga / Scafolding yang digunakan sudah di-inspeksi dan OK
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="jalanCheckbox" name="jalan" value="Platform untuk landasan tangga scalfolding datar dan/atau stabil">
							<span class="checkboxmark"></span>
							Platform untuk landasan tangga scalfolding datar dan/atau stabil
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="jalanCheckbox" name="jalan" value="Jalan untuk masuk dan keluar terhindar dari resiko kecelakaan kerja lainnya">
							<span class="checkboxmark"></span>
							Jalan untuk masuk dan keluar terhindar dari resiko kecelakaan kerja lainnya
						</label>
					</div>

					<br>


					<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
						<span>Penyataan Pelaksana</span>
					</span>

					Dengan ini, kami yang melaksanakan pekerjaan di atas 2,5 meter akan memenuhi semua ketentuan yang  disebutkan di atas,  jika tidak memenuhi ketentuan di ataskami bersedia menghentikan proses pekerjaan sampai semua persyaratan dipenuhi dan safety untuk melaksanakan pekerjaan di atas / di bawah 2,5 meter.

				</div>
				<div id="hot_work_permit" style="display:none">
					<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
					<span>HOT WORK PERMIT</span><br>
					<span style="font-size: 11px">Berlaku untuk pekerjaan : Welding, Grinda, Proses kerja lainnya yang menimbulkan Nyala Api / Api dan Panas</span>

					</span>

					<label class="label-input1002" id="labeljenis">Jenis Pekerjaan <span style="color: red">*</span></label>
					<input type="text" class="form-control" id="hot_type" name="hot_type" placeholder="">
					<br>

					<label class="label-input1002" id="labeljenis">Lokasi Pekerjaan <span style="color: red">*</span></label>
					<input type="text" class="form-control" id="hot_location" name="hot_location" placeholder="">
					<br>

					<b>Sumber Gas Yang Mudah Terbakar Lainnya <span style="color: red">*</span></b><br>
					Apakah sudah dilakukan pengecekan untuk potensial lainnya yang menyebakan timbulnya nyala api/kebakaran di sekitar area kerja?<br>

					<div class="validate-input" style="position: relative; width: 100%">
						1. Tabung gas/Acetylene apakah sudah dijauhkan
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="sumber0" name="sumber0" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="sumber0" name="sumber0" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>
					<br>
					<div class="validate-input" style="position: relative; width: 100%">
						2. Selang gas sudah dalam kondisi safety
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="sumber1" name="sumber1" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="sumber1" name="sumber1" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>
					<br>
					<div class="validate-input" style="position: relative; width: 100%">
						3. Instrument listrik yang berpotensial sudah dimatikan
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="sumber2" name="sumber2" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="sumber2" name="sumber2" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>
					<br>
					<div class="validate-input" style="position: relative; width: 100%">
						4. Bahan-bahan lainnya yang mudah terbakar, seperti kayu, karton, kertas, dan plastik
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="sumber3" name="sumber3" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="sumber3" name="sumber3" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>

					<br>

					<b>ALAT PELINDUNG DIRI <span style="color: red">*</span></b>
					<br><br>
					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="hot_safetyCheckbox" name="hot_safety" value="Welding Curtain">
							<span class="checkboxmark"></span>
							Welding Curtain
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="hot_safetyCheckbox" name="hot_safety" value="Welding Blanket">
							<span class="checkboxmark"></span>
							Welding Blanket
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="hot_safetyCheckbox" name="hot_safety" value="Welding Goggle">
							<span class="checkboxmark"></span>
							Welding Goggle
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="hot_safetyCheckbox" name="hot_safety" value="Safety Shoes">
							<span class="checkboxmark"></span>
							Safety Shoes
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="hot_safetyCheckbox" name="hot_safety" value="Safety Helmet">
							<span class="checkboxmark"></span>
							Safety Helmet
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="hot_safetyCheckbox" name="hot_safety" value="Tool / Equipment Pelindung dari resiko Hot Work">
							<span class="checkboxmark"></span>
							Tool / Equipment Pelindung dari resiko Hot Work
						</label>
					</div>

					<br>
					
					<b>Warning Sign <span style="color: red">*</span></b>
					<br>
					Papan peringatan yang diperlukan : 
					<br><br>
					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="hotWarningCheckbox" name="hot_warning" value="Awas Ada Pekerjaan Hot Work. Hati-hati kebakaran">
							<span class="checkboxmark"></span>
							Awas Ada Pekerjaan Hot Work. Hati-hati kebakaran
						</label>
					</div>

					<br>

					<b>Inspeksi / Pengecekan <span style="color: red">*</span></b><br>
					Apakah sudah dipastikan area kerja aman setelah 30 menit pekerjaan selesai?<br>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="radio" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="radio" id="inspeksi" name="inspeksi" value="iya">
							<span class="checkmark"></span>
							Ya
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="radio" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="radio" id="inspeksi" name="inspeksi" value="Tidak">
							<span class="checkmark"></span>
							Tidak
						</label>
					</div>


					<br>

					<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
						<span>Penyataan Pelaksana</span>
					</span>
					Untuk pekerjaan Hot Work ini selalu memastikan area kerja sebelum dan sesudah pelaksanaan betul-betul bebas dari resiko kebakaran dimana untuk material-material yang berpotensi menyebabkan kebakaran di-isolasi minimal 10 meter dari lokasi kerja atau di-isolasi dengan menggunakan welding curtain/blanket jika kondisi tidak memungkinkan.
				</div>
				<div id="space_permit" style="display:none">
					<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
					<span>CONFINED SPACE ENTRY PERMIT (IJIN KERJA DI RUANG TERBATAS)</span><br>
					<span style="font-size: 11px">Berlaku untuk area pekerjaan dibawah 1,5 meter atau di ruang terbatas (tertutup/ sempit)</span>

					</span>

					<label class="label-input1002" id="labeljenis">Jenis Pekerjaan <span style="color: red">*</span></label>
					<input type="text" class="form-control" id="space_type" name="space_type" placeholder="">
					<br>

					<label class="label-input1002" id="labeljenis">Lokasi Pekerjaan <span style="color: red">*</span></label>
					<input type="text" class="form-control" id="space_location" name="space_location" placeholder="">
					<br>

					<b>Prosedur Komunikasi </b>
					<br>
					Prosedur komunikasi antara personil didalam ruangan terbatas dan stand by menggunakan : 
					<br><br>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="komunikasiCheckbox" name="komunikasi" value="Handy Talkie">
							<span class="checkboxmark"></span>
							Handy Talkie
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="komunikasiCheckbox" name="komunikasi" value="Handphone">
							<span class="checkboxmark"></span>
							Handphone
						</label>
					</div>

					<br>

					<b>Equipment Rescue </b>
					<br>
					Selama proses pekerjaan di ruang terbatas sudah melengkapi dengan :
					<br><br>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="rescueCheckbox" name="rescue" value="Self Contained Breathing Apparatus (SCBA)">
							<span class="checkboxmark"></span>
							Self Contained Breathing Apparatus (SCBA)
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="rescueCheckbox" name="rescue" value="Gas Alert">
							<span class="checkboxmark"></span>
							Gas Alert
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="rescueCheckbox" name="rescue" value="Blower">
							<span class="checkboxmark"></span>
							Blower
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="rescueCheckbox" name="rescue" value="Tripod">
							<span class="checkboxmark"></span>
							Tripod
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="rescueCheckbox" name="rescue" value="Safety Belt/Harness">
							<span class="checkboxmark"></span>
							Safety Belt/Harness
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="rescueCheckbox" name="rescue" value="Safety Helmet">
							<span class="checkboxmark"></span>
							Safety Helmet
						</label>
					</div>

					<br>

					<b>Training </b>
					<br>
					Pelatihan dilakukan sebelum pekerjaan dilaksanakan :
					<br><br>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="trainingCheckbox" name="training" value="Pelaksana">
							<span class="checkboxmark"></span>
							Pelaksana
						</label>
					</div>

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 5px;margin-left: 25px;"> 
							<input type="checkbox" class="trainingCheckbox" name="training" value="Personnil Stand By">
							<span class="checkboxmark"></span>
							Personnil Stand By
						</label>
					</div>

					<br>

					<b>LIFTING EQUIPMENT <span style="color: red">*</span></b>
					<br>
					Alat angkat yang digunakan sudah di pasang dengan baik dan di-cek 
					<br><br>

					<div class="validate-input" style="position: relative; width: 100%">
						Chain Block
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="space_alat0" name="space_alat0" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="space_alat0" name="space_alat0" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>
					<br>
					<div class="validate-input" style="position: relative; width: 100%">
						Crane
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="space_alat1" name="space_alat1" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="space_alat1" name="space_alat1" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>
					<br>
					<div class="validate-input" style="position: relative; width: 100%">
						Tripod
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;margin-left: 5px;float: right;"> Tidak
							<input type="radio" id="space_alat2" name="space_alat2" value="Tidak">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
						<label class="radio" style="margin-top: 5px;float: right;">Iya
							<input type="radio"  id="space_alat2" name="space_alat2" value="Iya">
							<span class="checkmark"></span>
						</label>
						&nbsp;&nbsp;
					</div>

					<br>

					<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
						<span>Penyataan Pelaksana</span>
					</span>

					Pelaksanaan pekerjaan di area terbatas harus memenuhi semua ketentuan yang telah ditetapkan, dan selalu memastikan semua peralatan rescue berfungsi dengan baik, dan selalu memastikan komunikasi antara personil yang masuk area terbatas dan stand by personil
				</div>

				

				<div id="vendor_permit" style="display:none">
					<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
						<span>Pernyataan Vendor</span>
					</span>
					Dengan di submitnya form ini, maka kami bersedia memenuhi ketentuan yang telah disebutkan di atas. Jika tidak dipenuhi ketentuan diatas kami bersedia menerima sanksi berupa penghentian hubungan kerja secara sepihak atau sanksi yang diberikan perusahaan sesuai dengan tingkat pelanggarannya.

					<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
						<span>Ketentuan Yang Harus Dipatuhi</span>
					</span>

					<img src="{{url('images/vendor_ympi.jpg')}}" style="width: 100%;">

					<span class="contact100-form-title" style="margin-top: 10px;color: white;background-color: #b464f5;text-align:left;font-weight: bold;padding: 10px;font-size: 16px;">
						<span>Slogan K3 - Yamaha</span>
					</span>
					<img src="{{url('images/k3.jpg')}}" style="width: 100%;">

					<div class="validate-input" style="position: relative; width: 100%">
						<label class="checkbox" style="margin-top: 14px;"> 
							<input type="checkbox" class="persetujuan_vendorCheckbox" id="persetujuan_vendor" name="persetujuan_vendor" value="Saya memahami dan setuju seluruh pernyataan di atas">
							<span class="checkboxmark"></span>
							Saya memahami dan setuju dengan seluruh pernyataan di atas
						</label>
					</div>
				</div>

				<div class="container-contact100-form-btn" style="margin-top: 20px">
					<button class="contact100-form-btn" onclick="save()">
						<span>
							Submit
						</span>
					</button>
				</div>

				<br><br>
	      </div>
	      <div id="sudah_mengisi" style="width: 100%;padding: 170px 20px">
			<div class="col-xs-12 col-md-12">
          		<center style="font-size:16px">Terimakasih Bapak / Ibu <span class="name_assessment"></span> telah Mengisi Assessment ini.</i></u></center>
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
<!-- <script src="{{ url('js/main.js')}}"></script> -->
<script src="{{ url('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">
	jQuery(document).ready(function() {
		$('#sudah_mengisi').hide();

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

	function ChangePermit(elem){

		if (elem.value == "Height Permit") {
			$('#height_permit').show();
			$('#hot_work_permit').hide();
			$('#space_permit').hide();
			$('#vendor_permit').show();
		}

		else if (elem.value == "Hot Work Permit") {
			$('#height_permit').hide();
			$('#hot_work_permit').show();
			$('#space_permit').hide();
			$('#vendor_permit').show();
		}

		else if (elem.value == "Confined Space Permit") {
			$('#height_permit').hide();
			$('#hot_work_permit').hide();
			$('#space_permit').show();
			$('#vendor_permit').show();
		}

		else if (elem.value == "None") {
			$('#height_permit').hide();
			$('#hot_work_permit').hide();
			$('#space_permit').hide();
			$('#vendor_permit').show();
		}
	}

	function save() {
		$("#loading").show();

		if($("#company_name").val() == "" || $("#company_address").val() == "" || $("#company_email").val() == "" || $("#date_from").val() == "" || $("#date_to").val() == "" || $('#company_pic').val() == "" || $('#no_hp').val() == "" || $('#deskripsi').val() == "" || $('#lokasi').val() == "" || $('#pic_ympi').val() == "" ||  $('input[id="work_permit"]:checked').val() == ""){
		    $("#loading").hide();
			openErrorGritter('Error!', 'Pastikan Semua Bertanda * Sudah Diisi');
			return false;
		}

		var jenis_pekerjaan = [];
		$("input[name='jenis_pekerjaan']:checked").each(function (i) {
            jenis_pekerjaan[i] = $(this).val();
        });

		var jenis_pekerjaan_all = jenis_pekerjaan.join();

		var bahaya = [];
		$("input[name='bahaya']:checked").each(function (i) {
            bahaya[i] = $(this).val();
        });

		var bahaya_all = bahaya.join();

		var lingkungan = [];
		$("input[name='lingkungan']:checked").each(function (i) {
            lingkungan[i] = $(this).val();
        });

		var lingkungan_all = lingkungan.join();

		var prosedur = [];
		$("input[name='prosedur']:checked").each(function (i) {
            prosedur[i] = $(this).val();
        });

		var prosedur_all = prosedur.join();

		var ketentuan = [];
		$("input[name='ketentuan']:checked").each(function (i) {
            ketentuan[i] = $(this).val();
        });

		var ketentuan_all = ketentuan.join();

		var departemen = [];
		$("input[name='departemen']:checked").each(function (i) {
            departemen[i] = $(this).val();
        });

		var departemen_all = departemen.join();

		var safety = [];

		for(var i = 0;i<9; i++){
			var answer = 'safety'+i;
			safety.push($('input[id="'+answer+'"]:checked').val());
		}

		var peringatan = [];

		for(var i = 0;i<5; i++){
			var answer = 'peringatan'+i;
			peringatan.push($('input[id="'+answer+'"]:checked').val());
		}


		var type;
		var location;
		var question1;
		var question2;
		var question3;
		var question4;

		if ($('input[id="work_permit"]:checked').val() == "Height Permit") {
			var protection = [];
			$("input[name='height_protection']:checked").each(function (i) {
	            protection[i] = $(this).val();
	        });
			var protection_all = protection.join();

			var warning = [];
			$("input[name='height_warning']:checked").each(function (i) {
	            warning[i] = $(this).val();
	        });
			var warning_all = warning.join();

			var alat = [];
			for(var i = 0;i<4; i++){
				var answer = 'alat'+i;
				alat.push($('input[id="'+answer+'"]:checked').val());
			}

			var jalan = [];
			$("input[name='jalan']:checked").each(function (i) {
	            jalan[i] = $(this).val();
	        });
			var jalan_all = jalan.join();

			if($('input[id="work_permit"]:checked').val() == "Height Permit" && ($("#height_type").val() == "" || $("#height_location").val() == "" || protection_all == null || warning_all == null || alat == null || jalan_all == null)){
			    $("#loading").hide();
				openErrorGritter('Error!', 'Pastikan Semua Bertanda (*) Sudah Diisi 1');
				return false;
			}


			type = $('#height_type').val();
			location = $('#height_location').val();
			question1 = protection_all;
			question2 = warning_all;
			question3 = alat;
			question4 = jalan_all;


			// formData.append('type', $('#height_type').val());
			// formData.append('location', $('#height_location').val());
			// formData.append('question1', protection_all);
			// formData.append('question2', warning_all);
			// formData.append('question3', alat);
			// formData.append('question4', jalan_all);

			// console.log($('#height_type').val());
			// console.log($('#height_location').val());
			// console.log(protection_all);
			// console.log(warning_all);
			// console.log(alat);
			// console.log(jalan_all);
		}

		else if ($('input[id="work_permit"]:checked').val() == "Hot Work Permit") {

			var sumber = [];
			for(var i = 0;i<4; i++){
				var answer = 'sumber'+i;
				sumber.push($('input[id="'+answer+'"]:checked').val());
			}

			var hot_safety = [];
			$("input[name='hot_safety']:checked").each(function (i) {
	            hot_safety[i] = $(this).val();
	        });
			var hot_safety_all = hot_safety.join();

			var hot_warning = [];
			$("input[name='hot_warning']:checked").each(function (i) {
	            hot_warning[i] = $(this).val();
	        });
			var hot_warning_all = hot_warning.join();

			var inspeksi = [];
			inspeksi.push($('input[id="inspeksi"]:checked').val());

			if($('input[id="work_permit"]:checked').val() == "Hot Work Permit" && ($("#hot_type").val() == "" || $("#hot_location").val() == "" || sumber == null || hot_safety == null || hot_warning == null || inspeksi == null)){
			    $("#loading").hide();
				openErrorGritter('Error!', 'Pastikan Semua Bertanda (*) Sudah Diisi 2');
				return false;
			}


			type = $('#hot_type').val();
			location = $('#hot_location').val();
			question1 = sumber;
			question2 = hot_safety;
			question3 = hot_warning;
			question4 = inspeksi;

			// formData.append('type', $('#hot_type').val());
			// formData.append('location', $('#hot_location').val());
			// formData.append('question1', sumber);
			// formData.append('question2', hot_safety);
			// formData.append('question3', hot_warning);
			// formData.append('question4', inspeksi);

			// console.log($('#hot_type').val());
			// console.log($('#hot_location').val());
			// console.log(sumber);
			// console.log(hot_safety);
			// console.log(hot_warning);
			// console.log(inspeksi);
		}

		else if ($('input[id="work_permit"]:checked').val() == "Confined Space Permit") {

			var komunikasi = [];
			$("input[name='komunikasi']:checked").each(function (i) {
	            komunikasi[i] = $(this).val();
	        });
			var komunikasi_all = komunikasi.join();

			var rescue = [];
			$("input[name='rescue']:checked").each(function (i) {
	            rescue[i] = $(this).val();
	        });
			var rescue_all = rescue.join();

			var training = [];
			$("input[name='training']:checked").each(function (i) {
	            training[i] = $(this).val();
	        });
			var training_all = training.join();

			var space_alat = [];
			for(var i = 0;i<4; i++){
				var answer = 'space_alat'+i;
				space_alat.push($('input[id="'+answer+'"]:checked').val());
			}

			if($('input[id="work_permit"]:checked').val() == "Confined Space Permit" && ($("#space_type").val() == "" || $("#space_location").val() == "" || komunikasi_all == null || rescue_all == null || training_all == null || space_alat == null)){
			    $("#loading").hide();
				openErrorGritter('Error!', 'Pastikan Semua Bertanda (*) Sudah Diisi 3');
				return false;
			}


			type = $('#space_type').val();
			location = $('#space_location').val();
			question1 = komunikasi_all;
			question2 = rescue_all;
			question3 = training_all;
			question4 = space_alat;

			// formData.append('type', $('#space_type').val());
			// formData.append('location', $('#space_location').val());
			// formData.append('question1', komunikasi_all);
			// formData.append('question2', rescue_all);
			// formData.append('question3', training_all);
			// formData.append('question4', space_alat);

			// console.log($('#space_type').val());
			// console.log($('#space_location').val());
			// console.log(komunikasi_all);
			// console.log(rescue_all);
			// console.log(training_all);
			// console.log(space_alat);
		}

		else if ($('input[id="work_permit"]:checked').val() == "None") {
			/*formData.append('type', null);
			formData.append('location', null);
			formData.append('question1', null);
			formData.append('question2', null);
			formData.append('question3', null);
			formData.append('question4', null);*/


			type = null;
			location = null;
			question1 = null;
			question2 = null;
			question3 = null;
			question4 = null;
		}


		var formData = new FormData();

		formData.append('company_name', $('#company_name').val());				
		formData.append('company_address', $('#company_address').val());
		formData.append('company_email', $('#company_email').val());
		formData.append('date_from', $('#date_from').val());
		formData.append('date_to', $('#date_to').val());
		formData.append('company_pic', $('#company_pic').val());
		formData.append('jabatan', $('#jabatan').val());
		formData.append('no_hp', $('#no_hp').val());
		formData.append('jenis_pekerjaan', jenis_pekerjaan_all);
		formData.append('deskripsi', $('#deskripsi').val());
		formData.append('lokasi', $('#lokasi').val());
		formData.append('bahaya', bahaya_all);
		formData.append('lingkungan', lingkungan_all);
		formData.append('prosedur',prosedur_all);
		formData.append('safety', safety);
		formData.append('peringatan', peringatan);
		formData.append('ketentuan', ketentuan_all);
		formData.append('pic_ympi', $('#pic_ympi').val());
		formData.append('departemen', departemen_all);
		formData.append('work_permit', $('input[id="work_permit"]:checked').val());
		formData.append('type', type);
		formData.append('location', location);
		formData.append('question1', question1);
		formData.append('question2', question2);
		formData.append('question3', question3);
		formData.append('question4', question4);

		var vendor_accept = $('input[id="persetujuan_vendor"]:checked').val();

		if (vendor_accept == null) {
			 $("#loading").hide();
			openErrorGritter('Error!', 'Pastikan menyetujui semua pernyataan di atas.');
			return false;
		}

		formData.append('vendor_accept', vendor_accept);

		$.ajax({
			url:"{{ url('post/wpos') }}",
			method:"POST",
			data:formData,
			dataType:'JSON',
			contentType: false,
			cache: false,
			processData: false,
			success: function (response) {
				$("#name_assessment").val($('#company_name').val())
				openSuccessGritter("Success","Berhasil Dibuat");
        		$("#loading").hide();
				$('#belum_mengisi').hide();
				$('#sudah_mengisi').show();
			},
			error: function (response) {
				openErrorGritter("Error",response.datas);
				console.log(response);
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