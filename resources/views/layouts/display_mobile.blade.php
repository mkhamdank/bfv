<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" 
    content="worker-src blob:; child-src blob: gap:;img-src 'self' blob: data:;default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap: content:">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icon -->
    <link rel="icon" href="{{ url('img/bridgesmall.png') }}">
    <!-- Title -->
    <title>
        @if (isset($title))
            {{ $title }}
        @else
            Bridge for Vendor
        @endif
    </title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ url('adminlte/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/dist/css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('css/jquery.gritter.css') }}" > 

    @yield('styles')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script src="{{ url('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('adminlte/dist/js/adminlte.min.js') }}"></script>    
    <script src="{{ url('adminlte/plugins/jszip/jszip.js') }}"></script>
    <script src="{{ url('adminlte/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <style>
    .main-sidebar {
        height: 100% !important;
    }
    .container-login100 {
      width: 100%;  
      min-height: 100vh;
      display: -webkit-box;
      display: -webkit-flex;
      display: -moz-box;
      display: -ms-flexbox;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      padding: 5px;
      background: #e0e0e0;  

    }
    .wrap-login100 {
      width: 500px;
      background: #fff;
      border-radius: .375rem;
      border: 1px solid #0000002d;
      min-height: 99vh !important;
      justify-content: center;
      align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top:-8px!important;
    }
    </style>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</head>



<body class="sidebar-mini container-login100">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <main class="py-3 wrap-login100">
        <div class="progress" style="margin-top:-25px;display:none;height:25px;">
            <div class="progress-bar bg-danger progress-bar-striped" role="progressbar" style="width: 40%;">
            </div>
        </div>
        <div class="overlay-wrapper" id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%;display: none; z-index: 30001;">
            <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i></div>
        </div>
        @yield('content')
    </main>

    <script src="{{ url('js/notifications.js') }}" defer></script>    
    <script>        
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

        //fungsi ini ditaruh disini karena untuk mendeteksi jika browser memiliki local storage tes kraepelin maka langsung dikirim 
        const saveAnswerKraepelinAll = (toRedirect='', step=1) => {
            let recruitment_ympi_kraepelin_answer = localStorage.getItem("recruitment_ympi_kraepelin_answer");
            if(recruitment_ympi_kraepelin_answer != null){
                $.ajax({
                    headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}", Accept: "application/json",},
                    url: "{{ url('input/ympi_recruitment/kraepelin_answer') }}",
                    type: "POST",
                    data: {
                        data_answer:recruitment_ympi_kraepelin_answer,
                    },
                    success: function (resp) {
                        if(resp.status == true){
                            localStorage.removeItem("recruitment_ympi_kraepelin_answer");
                            arr_stringify = [];
                            if(toRedirect!=''){
                                let redirect = "{{ url('index/ympi_recruitment/kraepelin') }}";
                                openSuccessGritter('Berhasil disimpan');
                                window.location.href = redirect;
                            }
                        } else {
                            // errorAjax(resp.message)
                        }
                    },
                    error: function (jqXHR, exception) {
                        //autosend ajax jika error sebanyak step
                        if(step < 5){
                            step += 1;
                            saveAnswerKraepelinAll(toRedirect, step)
                        }
                    },
                });
            } else {
                if(toRedirect!=''){
                    let redirect = "{{ url('index/ympi_recruitment/kraepelin') }}";
                    openSuccessGritter('Berhasil disimpan');
                    window.location.href = redirect;
                }
            }
        }

        $('document').ready(function () {
            // saveAnswerKraepelinAll() ditaruh sini karena untuk mengkondisikan jika terdapat jawaban tes kraepelin dari user yg tertampung di localstorage browser maka akan langsung dikirim, keyword localstorage : recruitment_ympi_kraepelin
            saveAnswerKraepelinAll()
            
            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
            });
            $('.numeric').keyup(function (e) {
                this.value = this.value.replace(/\D/g,'');
            });
        });
        $(document).on('keyup change', ".numeric", function (e) {
            this.value = this.value.replace(/\D/g,'');
        });

    </script>
    
    @yield('scripts')
</body>

<script src="{{ url('js/jquery.gritter.min.js') }}"></script>
</html>
