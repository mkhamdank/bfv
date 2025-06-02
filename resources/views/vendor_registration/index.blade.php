
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>
        @if (isset($title) && isset($title_jp))
            {{ $title }} {{ $title_jp }}
        @endif
    </title>
    
	<link rel="stylesheet" type="text/css" href="{{ url('bootstrap2.min.css')}}">
    <link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">    
    <link rel="stylesheet" href="{{ url('css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ url('font-awesome.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('css/main.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('css/jquery.gritter.css') }}" >
	<link rel="stylesheet" href="{{ url('bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ url('select2.min.css') }}">

	<script src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
    <style>
    .content-header{
        width: 100%;
    }

    .content-header h1 {

        position: relative;
        margin-top: 0px;        
        font-weight: bold;
        background-color: #fff;
        color: #333333;
        border-radius: 12px;
        padding: 20px;
    }
    
    @media (max-width: 360px) {.content-header h1{ margin-top: 12%; padding: 5% 1%; } }

    .div-header { position: absolute; background-color: #605ca8;width: 100%; height: 10px; top: -1px; left: -1px; border-radius: 12px 12px 0 0; }

    .question-box {
        margin: 1% 0;
        padding: 1% 1% 3% 1%;
        border-radius: 12px;
        border: 1px solid rgb(218,220,224);
        background-color: #fff;
    }

    @media (max-width: 360px) {.question-box{ padding: 6% 1%; margin: 6% 1% } }

    .question-box input[type="text"] {       
        /* padding: 1% 2%; */
    }

    .question-box label {
        font-weight: bold;
    }

    .question-box input[type="radio"] {
        margin-right: 10px;
    }

    input[type="radio"] {
        width: 40px;
        height: 40px;
        left: 0px;
    }

    @media (max-width: 380px) {input[type="radio"] { width: 20px; height: 20px; } }

    .label-num { text-align: center;}
    .radio-num {text-align: right;}
    @media (max-width: 380px) {.label-num, .radio-num { text-align: left; width: 10px; height:10px;} }

    .left-label { text-align: right;}
    .right-label { text-align: left;}

    @media (max-width: 380px) {
        .left-label { text-align: right; font-size: 12px;}
        .right-label { text-align: left; font-size: 12px;}
    }

    
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
    
    ul, li {
      margin: 0px;
      list-style-type: disc;
    }

    p{
      color:white !important
    }

    </style>

    
  @if (session('status'))
  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-thumbs-o-up"></i> Success!</h4>
    {{ session('status') }}
  </div>   
  @endif
  @if ($errors->has('password'))
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
    {{ $errors->first() }}
  </div>   
  @endif
  @if (session('error'))
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-ban"></i> Error!</h4>
    {{ session('error') }}
  </div>   
  @endif
  
	<div id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
		<p style="position: absolute; color: White; top: 45%; left: 35%;">
			<span style="font-size: 20px">Loading, mohon tunggu . . .</span>
		</p>
	</div>
    <div class="content-wrapper" style="background-color: #ecf0f5; padding-top: 10px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 content-header">
                    <h1 style="text-align: center; border: 1px solid rgb(218,220,224); font-size: 3.5vw;">
                        <div class="div-header"></div>
                        <span id="training_title" style="margin-top: 20px">{{ $title }}</span>
                    </h1>
                </div>
            </div>

            <div class="row question-box belum_mengisi" style="padding: 0% 1% 1% 1%;">
                <div class="col-xs-12 col-md-12 col-lg-12" style="color: black;margin-top:10px;">
                    {{-- <label class="header-tab">Formulir Pendaftaran Vendor</label> --}}
                    <ul>
									    <li>Tanda <span style="color:red">*</span> Harus Diisi </li>
									    <li>Mohon Upload File PDF / Gambar</li>
                                        <li>Selain Company Profile, Ukuran Maksimum Upload File adalah 2 Mb. Apabila melebihi, harap di compress terlebih dahulu.</li>
      								<!-- <li>Batas Waktu Pengisian Survei adalah <span style="color:blue"><b>Selasa, 27 Mei 2025</b></span></li> -->
                    </ul>
                </div>
            </div>

            {{-- <form role="form" method="post" enctype="multipart/form-data"> --}}
                <input type="hidden" value="{{csrf_token()}}" name="_token" />
                <div class="row question-box belum_mengisi">

                  <div class="col-xs-12 col-md-12 col-lg-12" style="color: white;margin-top:10px;">
                    <div style="width:100%;background-color: #b464f5;padding: 10px;border-radius: 10px;">
                      <label class="header-tab">1. Identitas Perusahaan</label>
                    </div>
                  </div>

                      
                  <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                    <label for="badan_usaha">Badan Usaha <span style="color:red">*</span></label>
                    <select class="form-control select2" id="badan_usaha" name="badan_usaha" required data-placeholder="Pilih Badan Usaha" style="width: 100%; font-size: 20px;">
                      <option value=""></option>
                      <option value="PT">PT (Perseroan Terbatas)</option>
                      <option value="CV">CV (Commanditaire Vennootschap)</option>
                      <option value="UD">UD (Usaha Dagang)</option>
                    </select>
                  </div>
                  
                  <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                      <label for="select_nik">Nama Perusahaan <span style="color:red">*</span></label>
                      <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" value="" required placeholder="Contoh : Yamaha Musical Products Indonesia, PT">
                  </div>
                  
                    <!-- Nama Pimpinan Perusahaan -->
                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="pimpinan_perusahaan">Nama Pimpinan Perusahaan <span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="pimpinan_perusahaan" name="pimpinan_perusahaan" required>
                    </div>
                    
                    
                      <!-- Profil Perusahaan -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="file_profil_perusahaan">Profil Perusahaan <span style="color:red">*</span></label>
                        <input type="file" accept="application/pdf,image/*" class="form-control" id="file_profil_perusahaan" name="file_profil_perusahaan" required>
                      </div>

                      <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="aktivitas_bisnis">Aktivitas Bisnis <span style="color:red">*</span></label>
                        <select class="form-control select2" id="aktivitas_bisnis" name="aktivitas_bisnis" required data-placeholder="Pilih Aktivitas Bisnis" style="width: 100%; font-size: 20px;">
                          <option value=""></option>
                          <option value="Manufaktur">Perusahaan Manufaktur</option>
                          <option value="Dagang">Perusahaan Dagang</option>
                          <option value="Jasa">Perusahaan Jasa</option>
                        </select>
                      </div>
                      
                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                      <div class="validate-input" style="position: relative; width: 100%">
                        <label for="pertanyaan">Apakah anda memiliki akta pendirian ? <span style="color:red">*</span></label>
                        <div style="width: 100%">
                          <label class="radio" style="margin-top: 5px;float: left;">Iya
                            <input type="radio"  id="pertanyaan_akta_pendirian" name="pertanyaan_akta_pendirian" value="Iya" onchange="akta_question(this.value)">
                            <span class="checkmark"></span>
                          </label>
                          <label class="radio" style="margin-top: 5px;margin-left: 10px;float: left;"> Tidak
                            <input type="radio" id="pertanyaan_akta_pendirian" name="pertanyaan_akta_pendirian" value="Tidak" onchange="akta_question(this.value)">
                            <span class="checkmark"></span>
                          </label>
                        </div>
                      </div>
                    </div>

                      <!-- Copy Akte Pendirian -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="padding-top:10px;padding-bottom:10px;background-color:#ecff7b" id="akta_div">
                        <label for="file_akta_pendirian">Copy Akta Pendirian</label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_akta_pendirian" name="file_akta_pendirian">
                      </div>
                    
                    <div class="col-xs-12 col-md-12 col-lg-12" style="padding-top:10px;padding-bottom:10px;background-color:#ecff7b" id="alasan_akta_div">
                      <label for="alasan_akta_pendirian">Masukkan alasan belum memiliki Akta Pendirian <span style="color:red">*</span></label>
                      <textarea class="form-control" id="alasan_akta_pendirian" name="alasan_akta_pendirian" required></textarea>
                    </div>


                  <div class="col-xs-12 col-md-12 col-lg-12" style="color: white;margin-top:10px;">
                    <div style="width:100%;background-color: #b464f5;padding: 10px;border-radius: 10px;">
                      <label class="header-tab">2. Lokasi Perusahaan</label>
                    </div>
                  </div>

                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="answer_name">Alamat Perusahaan <span style="color:red">*</span></label>
                        <textarea class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" required></textarea>
                    </div>

                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="Provinsi">Provinsi <span style="color:red">*</span></label>
                        <select class="form-control select2" id="provinsi" name="provinsi" data-placeholder="Pilih Provinsi" style="width: 100%; font-size: 20px;" required>
                            <option></option>
                            
                        </select>
                    </div>
                    
                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="Kota">Kota / Kabupaten <span style="color:red">*</span></label>
                        <select class="form-control select2" id="kota" name="kota" data-placeholder="Pilih Kota" style="width: 100%; font-size: 20px;" required>
                            <option></option>
                            
                        </select>
                    </div>
                    
                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="Kecamatan">Kecamatan <span style="color:red">*</span></label>
                        <select class="form-control select2" id="kecamatan" name="kecamatan" data-placeholder="Pilih Kecamatan" style="width: 100%; font-size: 20px;" required>
                            <option></option>
                            
                        </select>
                    </div>
                    
                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="Kelurahan">Kelurahan <span style="color:red">*</span></label>
                        <select class="form-control select2" id="kelurahan" name="kelurahan" data-placeholder="Pilih Kelurahan" style="width: 100%; font-size: 20px;" required>
                            <option></option>
                        </select>
                    </div>

                    <!-- Surat Keterangan Domisili -->
                    <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                      <label for="domisili">Surat Keterangan Domisili <span style="color:red">*</span></label>
                      <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="domisili" name="domisili" required>
                    </div>
                    
                    <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                      <label for="domisili">Tanggal Berlaku Surat Domisili <span style="color:red">*</span></label>
                      <input type="date" class="form-control" id="domisili_due_date" name="domisili_due_date" required>
                    </div>
                    
                  <div class="col-xs-12 col-md-12 col-lg-12" style="color: white;margin-top:10px;">
                    <div style="width:100%;background-color: #b464f5;padding: 10px;border-radius: 10px;">
                      <label class="header-tab">3. Kontak Perusahaan</label>
                    </div>
                  </div>

                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="email">Email <span style="color:red">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                      </div>
                    
                      <!-- Nomor Telepon -->
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="telepon">Nomor Telepon <span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="telepon" name="telepon" required>
                      </div>
                    
                      <!-- Nomor Fax -->
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="fax">Nomor Fax</label>
                        <input type="text" class="form-control" id="fax" name="fax">
                      </div>

                    <div class="col-xs-12 col-md-12 col-lg-12" style="color: white;margin-top:10px;">
                      <div style="width:100%;background-color: #b464f5;padding: 10px;border-radius: 10px;">
                        <label class="header-tab">4. Informasi Perbankan</label>
                      </div>
                    </div>
                      <!-- Nama Bank -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="nama_bank">Nama Bank <span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="nama_bank" name="nama_bank" required>
                      </div>
                    
                      <!-- Alamat Bank -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="alamat_bank">Alamat Bank <span style="color:red">*</span></label>
                        <textarea class="form-control" id="alamat_bank" name="alamat_bank" required></textarea>
                      </div>
                    
                      <!-- Nomor Rekening -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="no_rekening">Nomor Rekening <span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="no_rekening" name="no_rekening" required>
                      </div>
                    
                      <!-- Nama Rekening -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="nama_rekening">Nama Rekening <span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="nama_rekening" name="nama_rekening" required onkeyup="check_rekening(this.value)">
                      </div>
                    
                      <!-- Mata Uang -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="mata_uang">Mata Uang <span style="color:red">*</span></label>
                        <select class="form-control select2" id="mata_uang" name="mata_uang" style="height:34px" required>
                          <option value="">Pilih Mata Uang</option>
                          <option value="IDR">IDR - Rupiah</option>
                          <option value="JPY">JPY - Yen</option>
                          <option value="USD">USD - Dollar Amerika</option>
                          <option value="EUR">EUR - Euro</option>
                        </select>
                      </div>
                    
                      <!-- Alasan Rekening -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px" id="alasan_rekening_div">
                        <label for="alasan_rekening">Masukkan alasan nama rekening tidak sama dengan nama vendor <span style="color:red">*</span></label>
                        <textarea class="form-control" id="alasan_rekening" name="alasan_rekening" required></textarea>
                      </div>
                    
                      <!-- Form Bank Account -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label for="form_bank">Form Pernyataaan Rekening Bank Perusahaan <span style="color:red">*</span></label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="form_bank" name="form_bank" required>
                        Download <a href="{{ url('YMPI Vendor Account Bank.doc') }}" style="color:blue">Form Pernyataaan Rekening Bank Perusahaan</a>
                      </div>
                    
                    <div class="col-xs-12 col-md-12 col-lg-12" style="color: white;margin-top:10px;">
                      <div style="width:100%;background-color: #b464f5;padding: 10px;border-radius: 10px;">
                        <label class="header-tab">5. Informasi Perpajakan</label>
                      </div>
                    </div>

                      <!-- Nomor NPWP -->
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="nomor_npwp">Nomor NPWP <span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="nomor_npwp" name="nomor_npwp" pattern="\d*" maxlength="16"  required>
                      </div>
                    
                      <!-- Copy NPWP -->
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="copy_npwp">Copy NPWP <span style="color:red">*</span></label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="copy_npwp" name="copy_npwp" required>
                      </div>
                    
                      <!-- Copy SPT -->
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="spt">Copy SPT Terakhir (Pajak PPH Badan) <span style="color:red">*</span></label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="copy_spt" name="copy_spt" required>
                        (Detail Nilai Boleh Diburamkan)
                      </div>
                      
                    
                    <div class="col-xs-12 col-md-12 col-lg-12" style="color: white;margin-top:10px;">
                      <div style="width:100%;background-color: #b464f5;padding: 10px;border-radius: 10px;">
                        <label class="header-tab">6. Perizinan dan Legalitas</label>
                      </div>
                    </div>
                    
                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                      <div class="validate-input" style="position: relative; width: 100%">
                        <label for="pertanyaan">Apakah Anda Memiliki Nomor Induk Berusaha (NIB) ? <span style="color:red">*</span></label>
                        <div style="width: 100%">
                          <label class="radio" style="margin-top: 5px;float: left;">Iya
                            <input type="radio"  id="pertanyaan_nib" name="pertanyaan_nib" value="Iya" onchange="nib_question(this.value)">
                            <span class="checkmark"></span>
                          </label>
                          <label class="radio" style="margin-top: 5px;margin-left: 10px;float: left;"> Tidak
                            <input type="radio" id="pertanyaan_nib" name="pertanyaan_nib" value="Tidak" onchange="nib_question(this.value)">
                            <span class="checkmark"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                  
                    <!-- NIB -->
                    <div class="col-xs-12 col-md-6 col-lg-6" style="padding-top:10px;padding-bottom:10px;background-color:#ecff7b" id="nib_div">
                      <label for="nib">Nomor Induk Berusaha (NIB) <span style="color:red">*</span></label>
                      <input type="text" class="form-control" id="nib" name="nib" required>
                    </div>
                  
                    <!-- Copy File NIB -->
                    <div class="col-xs-12 col-md-6 col-lg-6" style="padding-top:10px;padding-bottom:10px;background-color:#ecff7b" id="file_nib_div">
                      <label for="file_nib">Copy File NIB <span style="color:red">*</span></label>
                      <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_nib" name="file_nib" required>
                    </div>
                    

                    
                    <div class="col-xs-12 col-md-12 col-lg-12" style="padding-top:10px;padding-bottom:10px;background-color:#ecff7b" id="alasan_nib_div">
                      <label for="alasan_nib">Masukkan alasan belum memiliki NIB <span style="color:red">*</span></label>
                      <textarea class="form-control" id="alasan_nib" name="alasan_nib" required></textarea>
                    </div>

                    <!-- Copy SIUP -->
                    <div class="col-xs-12 col-md-6 col-lg-6" style="padding-top:10px;padding-bottom:10px;background-color:#ecff7b" id="siup_div">
                      <label for="file_siup">Copy SIUP (Surat Izin Usaha Perdagangan)</label>
                      <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_siup" name="file_siup" required>
                    </div>
                  
                    <!-- Copy TDP -->
                    <div class="col-xs-12 col-md-6 col-lg-6" style="padding-top:10px;padding-bottom:10px;background-color:#ecff7b" id="tdp_div">
                      <label for="tdp">Copy TDP (Tanda Daftar Perusahaan)</label>
                      <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_tdp" name="file_tdp" required>
                    </div>
                    
                    <!-- KBLI -->
                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px" id="kbli_div">
                      <label for="kbli">KBLI (Jenis Usaha) <span style="color:red">*</span></label>
                      <select class="form-control select2" id="kbli" name="kbli" multiple required>
                        <option disabled selected>Pilih KBLI</option>
                        @foreach ($kbli as $k)
                          <option value="{{ $k->kode_kbli }}_{{ $k->nama_kbli }}">{{ $k->kode_kbli }} - {{ $k->nama_kbli }}</option>
                        @endforeach
                        <!-- Tambah sesuai data KBLI -->
                      </select>
                    </div>


                    
                    <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                      <div class="validate-input" style="position: relative; width: 100%">
                        <label for="pertanyaan">Apakah Anda Pengusaha Kena Pajak ? <span style="color:red">*</span></label>
                        <div style="width: 100%">
                          <label class="radio" style="margin-top: 5px;float: left;">Iya
                            <input type="radio"  id="pertanyaan_pajak" name="pertanyaan_pajak" value="Iya" onchange="pajak_question(this.value)">
                            <span class="checkmark"></span>
                          </label>
                          <label class="radio" style="margin-top: 5px;margin-left: 10px;float: left;"> Tidak
                            <input type="radio" id="pertanyaan_pajak" name="pertanyaan_pajak" value="Tidak" onchange="pajak_question(this.value)">
                            <span class="checkmark"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                      <!-- Copy SPPKP -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="padding-top:10px;padding-bottom:10px;background-color:#ecff7b" id="sppkp_div">
                        <label for="file_sppkp">Copy SPPKP <span style="color:red">*</span></label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_sppkp" name="file_sppkp" required>
                      </div>
                      
                      <!-- Copy SPPKP -->
                      <div class="col-xs-12 col-md-12 col-lg-12" style="padding-top:10px;padding-bottom:10px;background-color:#ecff7b" id="non_pkp_div">
                        Download <a href="{{ url('YMPI Surat Pernyataan Non PKP.docx') }}" style="color:blue">Form Pernyataaan Non PKP</a>
                        <label for="file_non_pkp">Form Pernyataan Non PKP <span style="color:red">*</span></label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_non_pkp" name="file_non_pkp" required>
                      </div>
                    
                      <!-- Copy Sertifikat Authorized -->
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="sertifikat">Copy Sertifikat Authorized </label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_sertifikat" name="file_sertifikat" required>
                      </div>

                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="iso">Upload Sertifikat ISO 9001</label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_iso_9001" name="file_iso_9001" required>
                      </div>

                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="iso">Upload Sertifikat ISO 14001</label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_iso_14001" name="file_iso_14001">
                      </div>
                      
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="iso">Upload Sertifikat ISO 45001</label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_iso_45001" name="file_iso_45001">
                      </div>
                      
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="iso">SVLK (Sistem Verifikasi Legalitas Kayu) - (Khusus Vendor Kayu)</label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_svlk" name="file_svlk">
                      </div>
                      
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="iso">Sertifikat AEO</label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_aeo" name="file_aeo">
                      </div>
                    
                      <!-- MTA -->
                      {{-- <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="mta">MTA (Master Trade Agreement) <span style="color:red">*</span></label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_mta" name="file_mta" required>
                      </div>
                    
                      <!-- CSR -->
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="csr">CSR <span style="color:red">*</span></label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_csr" name="file_csr" required>
                      </div>
                    
                      <!-- Green Procurement -->
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="file_green_procurement">Green Procurement <span style="color:red">*</span></label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_green_procurement" name="file_green_procurement" required>
                      </div>
                    
                      <!-- ROH -->
                      <div class="col-xs-12 col-md-6 col-lg-6" style="margin-top:10px">
                        <label for="roh">ROH <span style="color:red">*</span></label>
                        <input type="file" accept="application/pdf,image/*" class="form-control fileInput" id="file_roh" name="file_roh" required>
                      </div> --}}
                      
                      <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top:10px">
                        <label class="checkbox" style="margin-top: 14px;"> 
                          <input type="checkbox" class="persetujuan_vendorCheckbox" id="persetujuan_vendor" name="persetujuan_vendor" value="Saya memahami dan setuju seluruh pernyataan di atas">
                          <span class="checkboxmark"></span>
                          Dengan ini, saya menyatakan bahwa data vendor yang saya serahkan adalah benar, lengkap, dan akurat.
                        </label>
                      </div>

                  <div class="col-xs-12 " style="margin-top:20px">
                      <button class="btn btn-primary btn-lg pull-right" onclick="save()">Simpan</button>
                  </div>
                </div>
                {{-- <div class="row">
                    <div class="col-xs-12" style="margin-bottom:20px">
                        <span class="pull-right" onclick="clearForm()" style="cursor: pointer; color:#2196F3;"><b>Kosongkan Formulir</b></span>
                    </div>
                </div>     --}}
            {{-- </form> --}}

            
            <div class="row question-box sudah_mengisi">
                <div class="col-xs-12 col-md-12 col-lg-12" style="color: black;margin-top:10px;text-align: center;">
                    <span style="font-size:24px">Terimakasih Bapak / Ibu telah survey vendor kami.</span>
                    <!-- <span style="font-size:24px">Mohon maaf, proses pendataan kelengkapan data vendor telah ditutup. <br>Harap menantikan informasi selanjutnya dari kami.</span> -->
                </div>
            </div>

            
        </div>
    </div>


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

    <script>        

        code = "";

        jQuery(document).ready(function() {
			    $('.sudah_mengisi').hide();
            
          $('.select2').prop('selectedIndex', 0).change();
          $('.select2').select2({
            dropdownAutoWidth : true,
            allowClear:true
          });
            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
            });
            clearForm();
            code = "{{ isset($_GET['code']) ? $_GET['code'] : null }}";

            const maxSize = 2 * 1024 * 1024; // 2 MB

            $(".fileInput").on("change", function () {
                let isValid = true;

                $.each(this.files, function (index, file) {
                    if (file.size > maxSize) {
                        openErrorGritter('Error!', 'File yang diupload terlalu besar! Maksimal 2 MB.<br>');
                        isValid = false;
                    }
                });

                if (!isValid) {
                    $(this).val(""); // Kosongkan input jika ada file yang tidak valid
                } 
            });
        });
        
		    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const openSuccessGritter = (title, message) => {
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-success',
                image: '{{ url('images/image-screen.png') }}',
                sticky: false,
                time: '2000'
            });
        }

        const openErrorGritter = (title, message) => {
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-danger',
                image: '{{ url('images/image-stop.png') }}',
                sticky: false,
                time: '2000'
            });
        }

        $.getJSON("wilayah-proxy/provinces", function (res) {
            $.each(res.data, function (i, prov) {
             $('#provinsi').append(`<option value="${prov.code}_${prov.name}">${prov.name}</option>`);
            });
        });

        // Load kota saat Provinsi dipilih
        $('#provinsi').on('change', function () {
            let provId = $(this).val().split('_')[0];
            $('#kota').html('<option value="">Pilih Kota / Kabupaten</option>');
            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
            $('#kelurahan').html('<option value="">Pilih Kelurahan / Desa</option>');
            
            if (provId) {
                $.getJSON(`wilayah-proxy/regencies/${provId}`, function (res) {
                    $.each(res.data, function (i, kab) {
                    $('#kota').append(`<option value="${kab.code}_${kab.name}">${kab.name}</option>`);
                    });
                });
            }
        });

        // Load Kecamatan saat kota dipilih
        $('#kota').on('change', function () {
            let kabId = $(this).val().split('_')[0];;
            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
            $('#kelurahan').html('<option value="">Pilih Kelurahan/Desa</option>');
            if (kabId) {
                $.getJSON(`wilayah-proxy/districts/${kabId}`, function (res) {
                    $.each(res.data, function (i, kec) {
                        $('#kecamatan').append(`<option value="${kec.code}_${kec.name}">${kec.name}</option>`);
                    });
                });
            }
        });

        // Load Kelurahan saat Kecamatan dipilih
        $('#kecamatan').on('change', function () {
            let kecId = $(this).val().split('_')[0];;
            $('#kelurahan').html('<option value="">Pilih Kelurahan/Desa</option>');
            if (kecId) {
                $.getJSON(`wilayah-proxy/villages/${kecId}`, function (res) {
                    $.each(res.data, function (i, kel) {
                    $('#kelurahan').append(`<option value="${kel.name}_${kel.postal_code}">${kel.name} (${kel.postal_code})</option>`);
                    });
                });
            }
        });


        function clearForm(){
          
          $('#akta_div, #alasan_akta_div, #nib_div, #file_nib_div, #siup_div, #tdp_div, #sppkp_div, #non_pkp_div, #alasan_nib_div, #alasan_rekening_div').hide();
          
            $('#badan_usaha').prop('selectedIndex', 0).change();
            $('#nama_perusahaan').val('');
            $('#pimpinan_perusahaan').val('');
            $('#file_profil_perusahaan').val('');
            $('#aktivitas_bisnis').prop('selectedIndex', 0).change();
            $('#pertanyaan_akta_pendirian').prop('checked', false);
            $('#alasan_akta_pendirian').val('');
            $('#file_akta_pendirian').val('');
            $('#alamat_perusahaan').val('');
            $('#provinsi').prop('selectedIndex', 0).change();
            $('#kota').prop('selectedIndex', 0).change();
            $('#kecamatan').prop('selectedIndex', 0).change();
            $('#kelurahan').prop('selectedIndex', 0).change();
            $('#domisili').val('');
            $('#domisili_due_date').val('');
            $('#email').val('');
            $('#telepon').val('');
            $('#fax').val('');
            $('#nama_bank').val('');
            $('#alamat_bank').val('');
            $('#no_rekening').val('');
            $('#nama_rekening').val('');
            $('#mata_uang').prop('selectedIndex', 0).change();
            $('#alasan_rekening').val('');
            $('#form_bank').val('');
            $('#nomor_npwp').val('');
            $('#copy_npwp').val('');
            $('#copy_spt').val('');
            $('#pertanyaan_nib').prop('checked', false);
            $('#alasan_nib').val('');
            $('#nib').val('');
            $('#file_nib').val('');
            $('#kbli').val(null).trigger('change');
            $('#file_siup').val('');
            $('#file_tdp').val('');
            $('#pertanyaan_pajak').prop('checked', false);
            $('#file_sppkp').val('');
            $('#file_non_pkp').val('');
            $('#file_sertifikat').val('');
            $('#file_iso_9001').val('');
            $('#file_iso_14001').val('');
            $('#file_iso_45001').val('');
            $('#file_slvk').val('');
            $('#file_aeo').val('');
        }

       function check_rekening(value) {
        var nama_perusahaan = $('#nama_perusahaan').val();
        if (value === '') {
            $('#alasan_rekening_div').hide();
            return;
        }

        // Clean and normalize the input strings
        var nama_perusahaan = $('#nama_perusahaan').val().toLowerCase().replace(/[^a-z0-9\s]/g, '').trim();
        var nama_rekening = value.toLowerCase().replace(/[^a-z0-9\s]/g, '').trim();

        // Split, sort, and join words to normalize word order
        var sorted_perusahaan = nama_perusahaan.split(/\s+/).sort().join(' ');
        var sorted_rekening = nama_rekening.split(/\s+/).sort().join(' ');

        // If sorted words are identical, similarity is 100%
        if (sorted_perusahaan === sorted_rekening) {
            $('#alasan_rekening_div').hide();
            return;
        }

        // Function to calculate Levenshtein distance
        function levenshtein(a, b) {
            var matrix = [];
            var i, j;

            for (i = 0; i <= b.length; i++) {
                matrix[i] = [i];
            }
            for (j = 0; j <= a.length; j++) {
                matrix[0][j] = j;
            }

            for (i = 1; i <= b.length; i++) {
                for (j = 1; j <= a.length; j++) {
                    if (b.charAt(i - 1) === a.charAt(j - 1)) {
                        matrix[i][j] = matrix[i - 1][j - 1];
                    } else {
                        matrix[i][j] = Math.min(
                            matrix[i - 1][j] + 1,
                            matrix[i][j - 1] + 1,
                            matrix[i - 1][j - 1] + 1
                        );
                    }
                }
            }

            return matrix[b.length][a.length];
        }

        // Calculate similarity using Levenshtein distance
        var distance = levenshtein(nama_perusahaan, nama_rekening);
        var maxLength = Math.max(nama_perusahaan.length, nama_rekening.length);
        var similarity = ((maxLength - distance) / maxLength) * 100;

        if (similarity >= 50) {
            $('#alasan_rekening_div').hide();
        } else {
            $('#alasan_rekening_div').show();
        }
    }
        function akta_question(value) {
            if (value == 'Iya') {
                $('#akta_div').show();
                $('#alasan_akta_div').hide();
            } else {
                $('#akta_div').hide();
                $('#alasan_akta_div').show();
            }
        }

        function nib_question(value) {
            if (value == 'Iya') {
                $('#nib_div').show();
                $('#file_nib_div').show();
                $('#alasan_nib_div').hide();
                $('#siup_div').show();
                $('#tdp_div').show();
            } else {
                $('#nib_div').hide();
                $('#file_nib_div').hide();
                $('#alasan_nib_div').show();
                $('#siup_div').show();
                $('#tdp_div').show();
            }
        }
        
        function pajak_question(value) {
            if (value == 'Iya') {
                $('#sppkp_div').show();
                $('#non_pkp_div').hide();
            } else {
                $('#sppkp_div').hide();
                $('#non_pkp_div').show();
            } 
        }

        
		function save() {
      $("#loading").show();

      if ($("#badan_usaha").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Badan Usaha tidak boleh kosong');
          $("#badan_usaha").focus();
          return false;
      }
      if ($("#nama_perusahaan").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Nama Perusahaan tidak boleh kosong');
          $("#nama_perusahaan").focus();
          return false;
      }
      if ($("#pimpinan_perusahaan").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Nama Pimpinan Perusahaan tidak boleh kosong');
          $("#pimpinan_perusahaan").focus();
          return false;
      }
      if ($("#file_profil_perusahaan").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Profil Perusahaan tidak boleh kosong');
          $("#file_profil_perusahaan").focus();
          return false;
      }
      if ($("#aktivitas_bisnis").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Aktivitas Bisnis tidak boleh kosong');
          $("#aktivitas_bisnis").focus();
          return false;
      }
      if (!$("input[name='pertanyaan_akta_pendirian']:checked").val()) {
          $("#loading").hide();
          openErrorGritter('Error!', 'Pertanyaan Akta Pendirian harus dipilih');
          $("input[name='pertanyaan_akta_pendirian']").first().focus();
          return false;
      }
      if ($("#file_akta_pendirian").is(":visible") && $("#file_akta_pendirian").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'File akta pendirian tidak boleh kosong');
          $("#file_akta_pendirian").focus();
          return false;
      }
      if ($("#alasan_akta_pendirian").is(":visible") && $("#alasan_akta_pendirian").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Alasan belum memiliki Akta tidak boleh kosong');
          $("#alasan_akta_pendirian").focus();
          return false;
      }
      if ($("#alamat_perusahaan").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Alamat Perusahaan tidak boleh kosong');
          $("#alamat_perusahaan").focus();
          return false;
      }
      if ($("#provinsi").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Provinsi tidak boleh kosong');
          $("#provinsi").focus();
          return false;
      }
      if ($("#kota").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Kota / Kabupaten tidak boleh kosong');
          $("#kota").focus();
          return false;
      }
      if ($("#kecamatan").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Kecamatan tidak boleh kosong');
          $("#kecamatan").focus();
          return false;
      }
      if ($("#kelurahan").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Kelurahan tidak boleh kosong');
          $("#kelurahan").focus();
          return false;
      }
      if ($("#domisili").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Surat Keterangan Domisili tidak boleh kosong');
          $("#domisili").focus();
          return false;
      }
      if ($("#domisili_due_date").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Tanggal Berlaku Surat Domisili tidak boleh kosong');
          $("#domisili_due_date").focus();
          return false;
      }
      if ($("#email").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Email tidak boleh kosong');
          $("#email").focus();
          return false;
      }
      if ($("#telepon").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Nomor Telepon tidak boleh kosong');
          $("#telepon").focus();
          return false;
      }
      if ($("#nama_bank").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Nama Bank tidak boleh kosong');
          $("#nama_bank").focus();
          return false;
      }
      if ($("#alamat_bank").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Alamat Bank tidak boleh kosong');
          $("#alamat_bank").focus();
          return false;
      }
      if ($("#no_rekening").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Nomor Rekening tidak boleh kosong');
          $("#no_rekening").focus();
          return false;
      }
      if ($("#nama_rekening").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Nama Rekening tidak boleh kosong');
          $("#nama_rekening").focus();
          return false;
      }
      if ($("#mata_uang").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Mata Uang tidak boleh kosong');
          $("#mata_uang").focus();
          return false;
      }
      if ($("#alasan_rekening").is(":visible") && $("#alasan_rekening").val() == "") {
            $("#loading").hide();
            openErrorGritter('Error!', 'Alasan Rekening tidak boleh kosong');
            $("#alasan_rekening").focus();
            return false;
          }
      if ($("#form_bank").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Form Pernyataan Rekening Bank Perusahaan tidak boleh kosong');
          $("#form_bank").focus();
          return false;
      }
      if ($("#nomor_npwp").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Nomor NPWP tidak boleh kosong');
          $("#nomor_npwp").focus();
          return false;
      }
      if ($("#copy_npwp").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Copy NPWP tidak boleh kosong');
          $("#copy_npwp").focus();
          return false;
      }
      if ($("#copy_spt").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Copy SPT Terakhir tidak boleh kosong');
          $("#copy_spt").focus();
          return false;
      }
      if (!$("input[name='pertanyaan_nib']:checked").val()) {
          $("#loading").hide();
          openErrorGritter('Error!', 'Pertanyaan NIB harus dipilih');
          $("input[name='pertanyaan_nib']").first().focus();
          return false;
      }
      if ($("#alasan_nib").is(":visible") && $("#alasan_nib").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Alasan belum memiliki NIB tidak boleh kosong');
          $("#alasan_nib").focus();
          return false;
      }
      if ($("#nib").is(":visible") && $("#nib").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Nomor Induk Berusaha (NIB) tidak boleh kosong');
          $("#nib").focus();
          return false;
      }
      if ($("#file_nib").is(":visible") && $("#file_nib").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Copy File NIB tidak boleh kosong');
          $("#file_nib").focus();
          return false;
      }
      if ($("#kbli").is(":visible") && $("#kbli").val() == null) {
          $("#loading").hide();
          openErrorGritter('Error!', 'KBLI (Jenis Usaha) tidak boleh kosong');
          $("#kbli").focus();
          return false;
      }
      if (!$("input[name='pertanyaan_pajak']:checked").val()) {
          $("#loading").hide();
          openErrorGritter('Error!', 'Pertanyaan Pengusaha Kena Pajak harus dipilih');
          $("input[name='pertanyaan_pajak']").first().focus();
          return false;
      }
      if ($("#sppkp_div").is(":visible") && $("#file_sppkp").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Copy SPPKP tidak boleh kosong');
          $("#file_sppkp").focus();
          return false;
      }
      if ($("#non_pkp_div").is(":visible") && $("#file_non_pkp").val() == "") {
          $("#loading").hide();
          openErrorGritter('Error!', 'Form Pernyataan Non PKP tidak boleh kosong');
          $("#file_non_pkp").focus();
          return false;
      }
      

			var formData = new FormData();
      formData.append('unique_code', code);
      formData.append('badan_usaha', $('#badan_usaha').val());
      formData.append('nama_perusahaan', $('#nama_perusahaan').val());
      formData.append('pimpinan_perusahaan', $('#pimpinan_perusahaan').val());
      formData.append('file_profil_perusahaan', $('#file_profil_perusahaan')[0].files[0]);
      formData.append('aktivitas_bisnis', $('#aktivitas_bisnis').val());
      formData.append('pertanyaan_akta_pendirian', $('input[name="pertanyaan_akta_pendirian"]:checked').val());
      formData.append('file_akta_pendirian', $('#file_akta_pendirian')[0].files[0])
      formData.append('alasan_akta_pendirian', $('#alasan_akta_pendirian').val());;
      formData.append('alamat_perusahaan', $('#alamat_perusahaan').val());
      formData.append('provinsi', $('#provinsi').val());
      formData.append('kota', $('#kota').val());
      formData.append('kecamatan', $('#kecamatan').val());
      formData.append('kelurahan', $('#kelurahan').val());
      formData.append('domisili', $('#domisili')[0].files[0]);
      formData.append('domisili_due_date', $('#domisili_due_date').val());
      formData.append('email', $('#email').val());
      formData.append('telepon', $('#telepon').val());
      formData.append('fax', $('#fax').val());
      formData.append('nama_bank', $('#nama_bank').val());
      formData.append('alamat_bank', $('#alamat_bank').val());
      formData.append('no_rekening', $('#no_rekening').val());
      formData.append('nama_rekening', $('#nama_rekening').val());
      formData.append('mata_uang', $('#mata_uang').val());
      formData.append('alasan_rekening', $('#alasan_rekening').val());
      formData.append('form_bank', $('#form_bank')[0].files[0]);
      formData.append('nomor_npwp', $('#nomor_npwp').val());
      formData.append('copy_npwp', $('#copy_npwp')[0].files[0]);
      formData.append('copy_spt', $('#copy_spt')[0].files[0]);
      formData.append('pertanyaan_nib', $('input[name="pertanyaan_nib"]:checked').val());
      formData.append('alasan_nib', $('#alasan_nib').val());
      formData.append('nib', $('#nib').val());
      formData.append('file_nib', $('#file_nib')[0].files[0]);
      formData.append('kbli', $('#kbli').val().join('%'));
      formData.append('file_siup', $('#file_siup')[0].files[0]);
      formData.append('file_tdp', $('#file_tdp')[0].files[0]);
      formData.append('pertanyaan_pajak', $('input[name="pertanyaan_pajak"]:checked').val());
      formData.append('file_sppkp', $('#file_sppkp')[0].files[0]);
      formData.append('file_non_pkp', $('#file_non_pkp')[0].files[0]);
      formData.append('file_sertifikat', $('#file_sertifikat')[0].files[0]);
      formData.append('file_iso_9001', $('#file_iso_9001')[0].files[0]);
      formData.append('file_iso_14001', $('#file_iso_14001')[0].files[0]);
      formData.append('file_iso_45001', $('#file_iso_45001')[0].files[0]);
      formData.append('file_svlk', $('#file_svlk')[0].files[0]);
      formData.append('file_aeo', $('#file_aeo')[0].files[0]);
      formData.append('token', '{{ csrf_token() }}');

			var vendor_accept = $('input[id="persetujuan_vendor"]:checked').val();

			if (vendor_accept == null) {
				$("#loading").hide();
				openErrorGritter('Error!', 'Pastikan menyetujui semua pernyataan di atas.');
				return false;
			}

			formData.append('vendor_accept', vendor_accept);

			$.ajax({
				_token: "{{ csrf_token() }}",
				url:"{{ url('post/vendor_registration') }}",
				method:"POST",
				data:formData,
				dataType:'JSON',
				contentType: false,
				cache: false,
				processData: false,
				success: function (data) {
					if (data.status) {
						openSuccessGritter("Success","Data Berhasil Disimpan");
						$("#loading").hide();
						$('.belum_mengisi').hide();
						$('.sudah_mengisi').show();
					}else{
						openErrorGritter('Error!',data.message);
						$('#loading').hide();
					}

				},
				error: function (data) {
					openErrorGritter("Error",data.message);
					console.log(data);
				},
			})
		}

    </script>