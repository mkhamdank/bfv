<!DOCTYPE html>
<html>
<head>
  <link rel="shortcut icon" type="image/x-icon" href="{{ url("logo_mirai.png")}}" />
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>YMPI 情報システム</title>
  <link rel="stylesheet" href="{{ url("bower_components/bootstrap/dist/css/bootstrap.min.css")}}">
  <link rel="stylesheet" href="{{ url("bower_components/font-awesome/css/font-awesome.min.css")}}">
  <link rel="stylesheet" href="{{ url("bower_components/Ionicons/css/ionicons.min.css")}}">
  <link rel="stylesheet" href="{{ url("bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css")}}">
  <link rel="stylesheet" href="{{ url("bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css")}}">
  <link rel="stylesheet" href="{{ url("plugins/iCheck/all.css")}}">
  <link rel="stylesheet" href="{{ url("bower_components/select2/dist/css/select2.min.css")}}">
  <link rel="stylesheet" href="{{ url("dist/css/AdminLTE.min.css")}}">
  <link rel="stylesheet" href="{{ url("dist/css/skins/skin-purple.css")}}">
  <link rel="stylesheet" href="{{ url("fonts/SourceSansPro.css")}}">
  <link rel="stylesheet" href="{{ url("css/buttons.dataTables.min.css")}}">
  <link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
  @yield('stylesheets')
</head>


<body class="hold-transition skin-purple layout-top-nav">
  <div class="wrapper">
    <header class="main-header" >
      <nav class="navbar navbar-static-top">
        {{-- <div class="container"> --}}
          <div class="navbar-header">
            <a href="{{ url("/home") }}" class="logo">
              <span style="font-size: 35px"><img src="{{ url("images/logo_mirai_bundar.png")}}" height="45px" style="margin-bottom: 6px;">&nbsp;<b>M I R A I</b></span>
            </a>
          </div>
          <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
            <ul class="nav navbar-nav">
              <li>
                <a style="font-size: 20px; font-weight: bold;" class="text-yellow">
                  {{ $title }}
                </a>
              </li>
            </ul>
          </div>

        </nav>
      </header>
      <div class="content-wrapper" style="background-color: #ecf0f5; padding-top: 10px;">
        <section class="content">
          <div id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
            <p style="position: absolute; color: White; top: 45%; left: 35%;">
              <span style="font-size: 40px">Loading, please wait <i class="fa fa-spin fa-refresh"></i></span>
            </p>
          </div>
          <div class="error" style="text-align: center;">
            <p>
              <h2>
                <i class="fa fa-book"></i>
                {{ $message }} <br>
              </h2>
              @if ($status)
              @if ($status2 == 'new_pic')
              <center id="text_loc"><h3>Please Select Location Asset : </h3></center>
              @else
              <h1 class="text-green"><i class="fa fa-check-circle"></i>&nbsp;{{ $message2 }}</h1>
              @endif
              @else
              @if ($status2 == 'reject')
              <h1 class="text-red"><i class="fa fa-times-circle"></i>&nbsp;{{ $message2 }}</h1>
              @elseif ($status2 == 'hold')
              <h1 class="text-blue"><i class="fa fa-exclamation-circle "></i>&nbsp;{{ $message2 }}</h1>
              @endif
              @endif
            </p>
            <br>
          </div>

          @if(isset($loc_list) && $status2 == 'new_pic')
          <div style="text-align: center;">
            <form id="location_form" method="post" autocomplete="off" action="{{ url("approval/fixed_asset/disposal/new_pic") }}">
              <center>
                <input type="hidden" value="{{csrf_token()}}" name="_token">
                <input type="hidden" name="id" id="id" value="{{ $asset->id }}">
                <input type="hidden" name="form_number_loc" id="form_number_loc" value="{{ $asset->form_number }}">
                <input type="hidden" name="nama" id="nama" value="{{ $nama }}">
                <select class="form-control select2" name="location" id="location" data-placeholder='Select new Asset Location' style="width: 70%" required>
                  <option value=""></option>
                  @foreach($loc_list as $loc)
                  <option value="{{ $loc->location }}">{{ $loc->location }}</option>
                  @endforeach
                </select>
                <br>
                <button type="submit" class="btn btn-success" id="send_location" onclick="loading()"><i class="fa fa-check"></i> Approve</button>
              </center>
            </form>
          </div>
          @endif

        </section>
      </div>
      @include('layouts.footer')
    </div>
    <script src="{{ url("bower_components/jquery/dist/jquery.min.js")}}"></script>
    <script src="{{ url("bower_components/bootstrap/dist/js/bootstrap.min.js")}}"></script>
    <script src="{{ url("bower_components/datatables.net/js/jquery.dataTables.min.js")}}"></script>
    <script src="{{ url("bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js")}}"></script>
    <script src="{{ url("bower_components/select2/dist/js/select2.full.min.js")}}"></script>
    <script src="{{ url("bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}"></script>
    <script src="{{ url("bower_components/jquery-slimscroll/jquery.slimscroll.min.js")}}"></script>
    <script src="{{ url("plugins/iCheck/icheck.min.js")}}"></script>
    <script src="{{ url("bower_components/fastclick/lib/fastclick.js")}}"></script>
    <script src="{{ url("dist/js/adminlte.min.js")}}"></script>
    <script src="{{ url("dist/js/demo.js")}}"></script>
    <script src="{{ url("js/jquery.gritter.min.js") }}"></script>


    @section('scripts')
    <script>
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      function loading() {
        if ($("form").valid()) {
          $("#loading").show();
        }
      }

      $('.select2').select2();

      $('#retired_date').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true
      });

      function postComment() {
        var formData = new FormData();
        formData.append('id_form', $("#id_form").val());
        formData.append('stat2', $("#stat2").val());
        formData.append('position', "{{ $message }}");
        formData.append('nama', "{{ $nama }}");
        formData.append('comment', $("#comment").val());

        $.ajax({
          url: '{{ url("post/approval/fixed_asset") }}',
          type: 'POST',
          data: formData,
          contentType: false,
          cache: false,
          processData: false,
          success: function (response) {
            $("#comment").hide();
            $("#btn_send").hide();
            $("#text_comment").hide();

            openSuccessGritter('success', 'Fixed {{$message}} Successfully '+$("#stat2").val())
          }
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

    @endsection

    @yield('scripts')
  </body>
  </html>
