@extends('layouts.display_mobile')
@section('header')
<div class="page-breadcrumb" style="padding: 7px">
    <div class="row align-items-center">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="page-title mb-0 p-0">{{$title}}<span class="text-purple"> </span></h3>
        </div>
    </div>
</div>
@endsection

@section('content')
<style type="text/css">
    select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
        .select2-selection {
            background: #eee;
            box-shadow: none;
        }
        .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
            display: none;
        }
    }
    .form-control[readonly]{
        background-color: #d3d3d3!important;
        opacity: 1;
    }
    .bg-ympi {
        background-color: #605ca8;
    }
</style>

<div class="container-fluid" style="">
    <section class="content" style="padding-right: 10px;padding-left: 10px;">
        <div class="row">
            <div class="mt-2 mb-2" >
                <center><h4 class="bg-ympi rounded text-white header_test" style="padding:5px;">Tes Kraepelin</h4></center>
            </div>

            <form class="form-horizontal" id="formTest" style="display: block;">
                <div class="col-xs-12 col-md-12 col-lg-12 mb-2">
                    <label>Nama</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Nama" name="name" id="name" value="{{ session('session_ympi_recruitment')['name']}}" readonly>
                    <input type="hidden" name="test_type" value="kraepelin" readonly>
                    <div class="text-danger fw-bold invalid name"></div>
                </div>

                <div class="col-xs-12 col-md-12 col-lg-12 mb-2">
                    <label>Tanggal Tes</label>
                    <input type="text" class="form-control" placeholder="Tanggal Tes" name="test_date" id="test_date" value="{{ date('Y-m-d')}}" readonly>
                    <div class="text-danger fw-bold invalid test_date"></div>
                </div>

               <!--  <div class="col-sm-12 col-xs-12 mt-3">
                    <input type="hidden" name="test_type" value="kraepelin">
                    <button class="text-center btn btn-success" style="width:100%;" id="submit">Submit</button>   
                </div> -->
            </form>
        </div>

        @if($testDone)
        <div class="row">
            <div class="col-sm-12 col-xs-12 mt-3 text-center" >
                <img class="img-fluid" src="{{ asset('images/success.png')}}" style="max-height: 180px;" />
            </div>
            <div class="col-sm-12 col-xs-12 mt-3 text-center" >
                <h3>Anda telah mengerjakan</h3>
            </div>
        </div>
        @else
        <div class="row instruction" style="display: block;margin-top: 10px;">
            <h5 class="text-danger font-weight-bold">INSTRUKSI</h5>
            <div class="mt-2 mb-2" >
                <span >Dalam tes ini Anda akan menemukan kolom-kolom yang terdiri dari angka. Tugas Anda adalah :</span><br>
            </div>
            <div class="mt-2 mb-2" >
                <span >1. Menjumlahkan setiap angka dengan angka diatasnya. Penjumlahan dilakukan dari bawah ke atas.</span><br>
            </div>
            <div class="mt-2 mb-2" >
                <span >2. Dari hasil penjumlahan dua angka tersebut, Anda hanya menuliskan angka satuannya saja. Angka satuan tersebut diketik di sebelah kanan kolom, tepat di kedua angka yang Anda jumlahkan. Contoh 7+5= 12, Maka ditulis 2</span><br>
            </div>
            <div class="mt-2 mb-2" >
                <span >3. Dalam pengerjaannya dibatasi oleh waktu, kerjakan dengan cepat dan tepat. Sistem akan otomatis berpindah ke kolom berikutnya walaupun baris tidak semuanya terisi</span><br>
            </div>
            <div class="mt-2 mb-2" >
                <span >4. Anda diberi kesempatan untuk melakukan latihan pengerjaan. Setelah Anda klik tombol "Mulai Latihan" waktu akan langsung berjalan oleh karena itu pastikan Anda sudah siap </span><br>
            </div>
            <div class="mt-2 mb-2" >
                <span >5. Jika latihan sudah selesai akan muncul tombol "Mulai Tes" sehingga pastikan Anda sudah siap</span><br>
            </div>
            <h5 class="text-danger font-weight-bold">Contoh Pengerjaan</h5>
            <div class="mt-2 mb-2" >
                <img src="{{ asset('images/recruitment/kraepelin.png')}}" class="img-fluid"><br>
            </div>

            <div class="col-sm-12 col-xs-12 mt-3" >
                <a class="text-center btn btn-success" style="width:100%;" id="start_trial">Mulai Latihan</a>   
            </div>
        </div>

        <div class="row trial_test" style="display: none;">
            <div class="col-sm-12 col-md-12" id="trial_test"></div>
            <div class="col-sm-12 col-xs-12 mt-3 button_test" style="display: none;">
                <a class="text-center btn btn-success" style="width:100%;" id="start_test">Mulai Tes</a>   
            </div>
        </div>

        <div class="row test" style="display: none;">
            <div class="col-sm-12 col-md-12" id="test" style="height:350px;"></div>
            <div class="col-sm-12 col-xs-12 mt-3 button_submit" style="display: none;">
                <a class="text-center btn btn-success" style="width:100%;" id="submit_test">Submit</a>   
            </div>
        </div>
        @endif

    </section>
</div>

<div class="modal fade" id="confirmLatihan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title" >Tes Kraepelin</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body content_body">
                <h4>Anda yakin mulai latihan ?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tidak</button>
                <a id="startLatihan" style="margin-left:30px;" href="javascript:;" type="button" class="btn btn-success">Ya</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmTest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title" >Tes Kraepelin</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body content_body">
                <span class="text-danger" style="font-size: 20px;">Saat tes berjalan tidak bisa kembali ke halaman sebelumnya</span>
                <h4>Anda yakin mulai tes ?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tidak</button>
                <a id="startTest" style="margin-left:30px;" href="javascript:;" type="button" class="btn btn-success">Ya</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>        
    const column = ['aa','ab','ba','bb','ca','cb','da','db','ea','eb','fa','fb','ga','gb','ha','hb','ia','ib','ja','jb','ka','kb','la','lb','ma','mb','na','nb','oa','ob','pa','pb','qa','qb','ra','rb','sa','sb','ta','tb','ua','ub','va','vb','wa','wb','xa','xb','ya','yb'];

    const column_training = ['xb','ya','yb'];
    const row = [35,34,33,32,31,30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1];
    const count_row = row.length;
    let count = parseInt("{{ count($listQuestions) }}");
    let list_questions = [];
    let column_questions = [];
    let all_train   = '';
    let all_test    = '';
    let t           = '';
    let t_train     = '';
    let t_test      = '';
    let progress    = document.querySelector('.progress-bar');         
    let cnt         = 0;
    let cntTrain    = 0;
    let second_bar  = 0;
    let second_barTrain= 0;
    let detik       = 15;
    let myVar;
    let myVarTraining;
    let currentColumn;
    let nextColumn;
    let arr_stringify = [];
    let data_answer ;
    let checkAnswerTraining = 0;
    let checkAnswer = 0;
    let answerToShow = 5;
    let answerToShow_ = {};
    let columnToShow = 3;
    let columnToShow_ = {};
    let jumlahArraySoal = answerToShow+1;
    let jumlahArrayJawab = answerToShow;
    let stepIdentityCol = [];
    let groupIdentityCol = [];
    let stepIdentity = [];
    let groupIdentity = [];
    let arrSoal = [];
    let arrJawab = [];
    for (let i = 2; i <= count_row; i++) {
        let soalTo = [];
        let jawabTo = [];
        let pengaliBatasArr = (groupIdentity.length==0) ? 1 : groupIdentity.length+1;
        let batasArr = (answerToShow * pengaliBatasArr) + 1;

        if(stepIdentity.length == answerToShow+1){
            stepIdentity = [];
            if(stepIdentity.length == 0){
                for (let s = i-1; s <= batasArr; s++) {
                    arrSoal.push(s);
                }
                for (let j = i; j <= batasArr; j++) {
                    arrJawab.push(j);
                }
            }
            stepIdentity.push(i);
        } else {
            if(stepIdentity.length == 0){
                for (let s = i-1; s <= batasArr; s++) {
                    arrSoal.push(s);
                }
                for (let j = i; j <= batasArr; j++) {
                    arrJawab.push(j);
                }
            }
            answerToShow_[i] = {'soal':arrSoal, 'jawab':arrJawab};
            stepIdentity.push(i);
            if(stepIdentity.length == answerToShow){
                groupIdentity.push(i);
                stepIdentity = [];
                arrSoal = [];
                arrJawab = [];
            }
        }
    }

    $.each(column, function(k_c, val){
        if(k_c % columnToShow == 0){
            stepIdentityCol = [];
            let batasArr = (k_c + columnToShow) - 1;
            for (let s = k_c; s <= batasArr; s++) {
                stepIdentityCol.push(s);
            }
        }
        columnToShow_[k_c] = stepIdentityCol;
    });

    if(count > 0){
        //LIST SOAL
        $.each({!! json_encode($listQuestions) !!}, function(i, item){
            //Memasukkan angka2 soal pada variabel
            list_questions[i] = item;
        });

        //FOR TRAINING 
        $.each(column_training, function(i, item){
            t_train += `<td style="padding-right:45px;"><table class="table_training _${item}">`;
            $.each(row, function(k, val){
                let coordinate = item+val;
                t_train += `<tr class="soal_${val} coordinateSoal"><td><b style="font-size:16px;">${list_questions[coordinate]}</b></td><td></td></tr>`;
                if(val > 1){
                    next_train = val==count_row ? 2 : val+1;
                    thiscol_train = val==count_row ? column_training[i+1] : item;
                    t_train += `<tr class="jawab_${val} coordinateJawab">
                            <td></td>
                            <td><input type="text" pattern="[0-9]*" inputmode="numeric" class="answer_train text-danger ${item+val}" style="font-size:16px;font-weight:bold;" maxlength="1" size="1" nexttraining="${thiscol_train+next_train}" currentcoltraining="${i}" nextcoltraining="${i+1}" thiscolumn="${item}" thisrow="${val}" thiscoordinate="${item+val}">
                            </td>
                        </tr>`;
                }
            });
            t_train += `</table></td>`;
        });
        all_train = `<center><table><tr>${t_train}</tr></table></center>`;
        $("#trial_test").html(all_train);


        // FOR TEST
        $.each({!! json_encode($columnQuestions) !!}, function(i, item){
            column_questions[i] = item;
        });
        $.each(column, function(i, item){
            t_test += `<td style="padding-right:50px;"><table class="table_test _${item}" id="_${item}">`;
            $.each(row, function(k, val){
                let coordinate = item+val;
                t_test += `<tr class="soal_${val} coordinateSoal"><td><b style="font-size:15px;">${list_questions[coordinate]}</b></td><td><input hidden name="column_number[]" value="${column_questions[coordinate]}"></td></tr>`;
                if(val > 1){
                    next = val==count_row ? 2 : val+1;
                    thiscol = val==count_row ? column[i+1] : item;
                    t_test += `<tr class="jawab_${val} coordinateJawab">
                            <td></td>
                            <td><input type="text" pattern="[0-9]*" inputmode="numeric" class="answer text-danger ${item+val}" style="font-size:15px;font-weight:bold;" maxlength="1" size="1" id="${item+val}" next="${thiscol+next}" currentcol="${i}" nextcol="${i+1}" name="answer[]" column="${column_questions[coordinate]}" coordinate="${item+val}" thiscolumn="${item}" thisrow="${val}" thiscoordinate="${item+val}">
                                <input hidden name="column[]" value="${item+val}">
                            </td>
                        </tr>`;
                }
            });
            t_test += `</table></td>`;
        });
        all_test = `<center><table><tr>${t_test}</tr></table></center>`;
        $("#test").html(all_test);

    }

    const training = (repeat='') => {
        let startColumn = 'xb';
        $('.instruction').hide();
        currentColumnTrain = 0;
        nextColumnTrain = 1;
        lock_column('table_training', 'answer_train', startColumn, 1)
        showingRow(2)
        startBarTraining()
        $(`input.${startColumn}2`).attr('autofocus',true).focus();
    }

    const test = (repeat='') => {
        let startColumn = 'aa';
        $('.instruction').hide();
        $('#trial_test').html('');
        $('.trial_test').hide();
        $('.test').show();

        let answer_coordinate = $(`input#${startColumn}2`).attr('coordinate');
        let answer_value = $(`input#${startColumn}2`).val();
        let answer_column = $(`input#${startColumn}2`).attr('column');
        lock_column('table_test', 'answer', startColumn, 1)
        currentColumn = 0;
        nextColumn = 1;
        showingRow(2)
        startBar()
        showingColumn(0)
        $(`input#${startColumn}2`).attr('autofocus',true).focus();
    }

    const startBarTraining = (repeat='', nextRepeat='') => {
        let jumlah_kolom = column_training.length;
        if(nextRepeat!=''){
            $(`input.${nextRepeat}2`).focus().prop('readonly', false).css('background-color','#ffffff');
        }

        progress.style.width = null;
        if(repeat == 'repeat'){
            progress.innerHTML = 0;
            second_barTrain = 0;
            cntTrain = 0;
        }

        myVarTraining = setInterval(function(){
            if(currentColumnTrain < jumlah_kolom){ //mengkondisikan looping set interval ketika kolom progress blm yg terakhir

                //mengambil urutan kolom sekarang dan kolom yg selanjutnya pada kolom yg sedang aktif dikerjakan
                let currentColTrain = $($(':focus')[0]).attr('currentcoltraining'); 
                let nextCol = $($(':focus')[0]).attr('nextcoltraining');
                let thiscolumn = $($(':focus')[0]).attr('thiscolumn');

                if($(`:focus`).length == 0){
                    if(checkAnswerTraining==0){
                        let startColumn = 'xb';
                        $(`input.${startColumn}2`).focus();
                        checkAnswerTraining = 1;
                    }
                }

                if(parseInt(currentColTrain) < currentColumnTrain){
                    //jika kolom yg sedang dikerjkan berbeda dgn kolom yg seharusnya dikerjakan maka pindah paksa ke kolom yg seharusnya dikerjakan
                    $(`input.${column_training[currentColumnTrain]}2`).focus().prop('readonly', false).css('background-color','#ffffff');
                }

                if(second_barTrain == detik && currentColumnTrain < jumlah_kolom){
                    //jika sudah mencapai  variabel dari (let detik) maka pindah kolom sesuai kolom yg seharusnya dikerjakan
                    currentColumnTrain +=1;
                    nextColumn +=1;

                    if(currentColumnTrain == jumlah_kolom){
                        //jika sudah mencapai kolom terakhir maka detik berhenti dan semua inputan jadi readonly
                        second_barTrain = 0;
                        let input_answer = $("input.answer_train").attr('readonly', true).css({'background-color':'#aba6a6'});
                        $(".button_test").show().focus();
                    }

                    if(currentColumnTrain < jumlah_kolom){
                        //jika sudah mencapai  variabel dari (let detik) dan belum mencapai kolom terakhir maka detik akan reset ke 0 dan berjalan lagi
                        clearInterval(myVarTraining);
                        showingRow(2)
                        $("input.answer_train").attr('readonly', true).css({'background-color':'#aba6a6'});
                        $(`input.answer_train[thiscolumn=${column_training[parseInt(currentColTrain)+1]}]`).prop('readonly', false).css('background-color','#ffffff');
                        $(`input.${column_training[currentColumnTrain]}2`).focus().prop('readonly', false).css('background-color','#ffffff');
                        let nextRepeat = `${column_training[currentColumnTrain]}`;
                        startBarTraining('repeat', nextRepeat);  
                    }
                } else {
                    increaseBarTrain();  
                }
            } else {
                $(".button_test").show().focus();
                clearInterval(myVarTraining);return;
            }
        }, 1000); 
    }

    const startBar = (repeat='', nextRepeat='') => {
        let jumlah_kolom = column.length;
        if(nextRepeat!=''){
            $(`input#${nextRepeat}2`).focus().prop('readonly', false).css('background-color','#ffffff');
        }

        progress.style.width = null;
        if(repeat == 'repeat'){
            progress.innerHTML = 0;
            second_bar = 0;
            cnt = 0;
        }
        myVar = setInterval(function(){
            if(currentColumn < jumlah_kolom){ //mengkondisikan looping set interval ketika kolom progress blm yg terakhir

                //mengambil urutan kolom sekarang dan kolom yg selanjutnya pada kolom yg sedang aktif dikerjakan
                let currentCol = $($(':focus')[0]).attr('currentcol'); 
                let nextCol = $($(':focus')[0]).attr('nextcol');
                let thiscolumn = $($(':focus')[0]).attr('thiscolumn');
                
                if($(`:focus`).length == 0){
                    if(checkAnswer==0){
                        let startColumn = 'aa';
                        $(`input#${startColumn}2`).focus();
                        checkAnswer = 1;
                    }
                }

                if(parseInt(currentCol) < currentColumn){
                    //jika kolom yg sedang dikerjkan berbeda dgn kolom yg seharusnya dikerjakan maka pindah paksa ke kolom yg seharusnya dikerjakan
                    $(`#${column[currentColumn]}2`).focus().prop('readonly', false).css('background-color','#ffffff');;
                }

                if(second_bar == detik && currentColumn < jumlah_kolom){
                    //jika sudah mencapai  variabel dari (let detik) maka pindah kolom sesuai kolom yg seharusnya dikerjakan
                    currentColumn +=1;
                    nextColumn +=1;

                    if(currentColumn == jumlah_kolom){
                        //jika sudah mencapai kolom terakhir maka detik berhenti dan semua inputan jadi readonly
                        second_bar = 0;
                        let input_answer = $("input.answer").attr('readonly', true).css({'background-color':'#aba6a6'});
                        $(".button_submit").show();
                    }

                    if(currentColumn < jumlah_kolom){
                        //jika sudah mencapai  variabel dari (let detik) dan belum mencapai kolom terakhir maka detik akan reset ke 0 dan berjalan lagi
                        clearInterval(myVar);
                        showingRow(2)
                        showingColumn(currentColumn);

                        if(parseInt(currentColumn) % columnToShow == 0){
                            $(`.table_test._${column[parseInt(currentColumn)]}`).show();
                        }
                        let nextRepeat = `${column[currentColumn]}`;
                        $("input.answer").attr('readonly', true).css({'background-color':'#aba6a6'});
                        $(`input.answer[thiscolumn=${column[parseInt(currentCol)+1]}]`).prop('readonly', false).css('background-color','#ffffff');
                        $(`input#${column[currentColumn]}2`).focus().prop('readonly', false).css('background-color','#ffffff');
                        startBar('repeat', nextRepeat);  
                    }
                } else {
                    increaseBar();  
                }
            } else {
                $(".button_submit").show().focus();
                clearInterval(myVar);return;
            }
        }, 1000); 
    }

    const increaseBarTrain = () => {
        //styling progress bar 
        if(cntTrain > 100){clearInterval(myVarTraining);return}
        let add_progress = 100 / detik;
        cntTrain += add_progress;         
        second_barTrain += 1;
        progress.style.width = cntTrain+"%"; //utk menjadikan progress bar warna biru berjalan harus ada %
        progress.innerHTML = second_barTrain;
        $(".progress-bar").html('&nbsp;');
    }

    const increaseBar = () => {
        //styling progress bar 
        if(cnt > 100){clearInterval(myVar);return}
        let add_progress = 100 / detik;
        cnt += add_progress;         
        second_bar += 1;
        progress.style.width = cnt+"%"; //utk menjadikan progress bar warna biru berjalan harus ada %
        progress.innerHTML = second_bar;
        $(".progress-bar").html('&nbsp;');
    }

    const lock_column = (tableCLass, answerName, thiscoordinate=null, thisrow=null) => {
        thisrow = parseInt(thisrow)+1;
        $(`.${tableCLass}`).each(function () {
            let thisClass = $(this).attr('class').split(`${tableCLass} _`)[1];
            if(thiscoordinate == thisClass){
                $.each(row, function(k, val){
                    if(parseInt(thisrow) >= val){
                        $(`input.${answerName}[thiscolumn=${thiscoordinate}][thisrow=${thisrow}]`).prop('readonly', false).css('background-color','#ffffff');
                    } else {
                        $(`input.${answerName}[thiscolumn=${thiscoordinate}][thisrow=${thisrow}]`).prop('readonly', true).css('background-color','#aba6a6');
                    }
                });
            } else {
                $(`input.${answerName}[thiscolumn=${thisClass}]`).attr('readonly', true).css('background-color','#aba6a6');
            }
        });
    }

    const showingRow = (thisRow=2) => {
        $(`tr.coordinateSoal`).hide();
        $(`tr.coordinateJawab`).hide();
        let showSoal = answerToShow_[thisRow]['soal'];
        let showJawab = answerToShow_[thisRow]['jawab'];
        $.each(showSoal, function(i, item){
            $(`tr.soal_${item}`).show();
        });
        $.each(showJawab, function(i, item){
            $(`tr.jawab_${item}`).show();
        });
    }

    const showingColumn = (thisColumn) => {
        $(`.table_test`).parent().hide();
        let showColumn = columnToShow_[thisColumn];
        $.each(showColumn, function(i, item){
            let thisItem = parseInt(item);
            $(`.table_test._${column[thisItem]}`).parent().show();
        });
    }

    const saveAnswer = (answer_coordinate, answer_value, answer_column) => {
        let locaStorageKraeplin = [];
        let form = [];
        let arr_local = [];
        form.push({name:'participant_id', value: "{{ session('session_ympi_recruitment')['participant_id'] }}" });
        form.push({name:'date', value: "{{ date('Y-m-d') }}" });
        form.push({name:'coordinate', value: answer_coordinate});
        form.push({name:'answer', value: answer_value });
        form.push({name:'column_number', value: answer_column });

        let stringify = JSON.stringify(form);
        locaStorageKraeplin.push(stringify);
        arr_stringify.push(locaStorageKraeplin);

        let getAnswerKraeplin = localStorage.getItem("recruitment_ympi_kraepelin_answer");
        if(getAnswerKraeplin != null){
            arr_local = JSON.parse(getAnswerKraeplin);
            arr_local.push(locaStorageKraeplin);
            data_answer = JSON.stringify(arr_local);
            localStorage.setItem("recruitment_ympi_kraepelin_answer", data_answer);
        } else {
            data_answer = JSON.stringify(arr_stringify);
            localStorage.setItem("recruitment_ympi_kraepelin_answer", data_answer);
        }

        $.ajax({
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}", Accept: "application/json",},
            url: "{{ url('input/ympi_recruitment/kraepelin_answer') }}",
            type: "POST",
            data: {
                data_answer:data_answer,
            },
            success: function (resp) {
                // if(resp.status == true){
                    localStorage.removeItem("recruitment_ympi_kraepelin_answer");
                    arr_stringify = [];
                // } else {
                //     arr_stringify = [];
                // }
            },
            error: function (jqXHR, exception) {
                // saveAnswerKraepelinAll()
            },
        });
    } 

    const formTest = () => {
        $('.invalid').html('');
        $("#loading").show();
        let formData = new FormData($('#formTest')[0]);

        $.ajax({
            method: "POST",
            url: "{{ url('input/ympi_recruitment/kraepelin_participant') }}",
            cache: false,
            processData: false,
            contentType: false,
            data: formData,
            success: function(result) {
                if (result.status) {
                    $('#loading').hide();
                    $('#formTest').hide();

                    let cardId = $('#card_id').val(); 
                    let setDataTest = {'test_type':'kraepelin', 'test_date': "{{ date('Y-m-d') }}", 'card_id': cardId};
                    let identityThisTest = JSON.stringify(setDataTest);
                    localStorage.setItem("recruitment_ympi_kraepelin", identityThisTest);

                    training();
                    $('.instruction').hide();
                    $('.trial_test').show();
                    $('.progress').show();

                } else {
                    $('#loading').hide();
                    errorAjax(result.message);
                }
            },
            error: function(xhr, status, error) {
                $('#loading').hide();
                let message = xhr.responseJSON.message;
                errorAjax(message);
            }
        });
    }

    const checkOpeningTest = async (step=1) => {
        let result;
        result = await $.ajax({
            url: "{{ url('fetch/ympi_recruitment/check_opening_test') }}",
            type: "GET",
            success: function (res) {
            },
            error: function (err) {
                if(step < 5){
                    step += 1;
                    checkOpeningTest(step);
                }
            }
        });
        return result;
    }

    $('document').ready(function () {
        
    });

    $('input.answer_train').keypress(function (e) {    
        var charCode = (e.which) ? e.which : event.keyCode;
        let next = $(this).attr('nexttraining');
        let thiscolumn = $(this).attr('thiscolumn');
        let thisrow = $(this).attr('thisrow');
        if (String.fromCharCode(charCode).match(/[^0-9]/g) || (second_barTrain < 1 && thiscolumn=='yb')){
            return false;
        } 
        if (!(String.fromCharCode(charCode).match(/[^0-9]/g))){
            if(!$(this).is('[readonly]')){
                $(this).val('');
            }
            
            $(this).keyup(function (e) {    
                lock_column('table_training', 'answer_train', thiscolumn, thisrow)
                if(parseInt(thisrow) == count_row){
                    showingRow()
                } else {
                    showingRow(parseInt(thisrow) + 1);
                }
                $(`input.${next}`).focus();
            });   
        } 
    });  

    $('input.answer').keypress(function (e) {    
        var charCode = (e.which) ? e.which : event.keyCode;
        let next = $(this).attr('next');
        let thiscolumn = $(this).attr('thiscolumn');
        let thisrow = $(this).attr('thisrow');

        if (String.fromCharCode(charCode).match(/[^0-9]/g) || (second_bar < 1 && thiscolumn=='yb')){
            return false;
        } 
        if (!(String.fromCharCode(charCode).match(/[^0-9]/g))){
            if(!$(this).is('[readonly]')){
                $(this).val('');
            }
            
            $(this).keyup(function (e) {   
                let answer_coordinate = $(this).attr('coordinate');
                let answer_value = $(this).val();
                let answer_column = $(this).attr('column');
                let currentCol = $(this).attr('currentcol');
                showingColumn(currentCol);

                saveAnswer(answer_coordinate, answer_value, answer_column)
                lock_column('table_test', 'answer', thiscolumn, thisrow)
                if(parseInt(thisrow) == count_row){
                    showingRow()
                } else {
                    showingRow(parseInt(thisrow) + 1);
                }
                $(`input#${next}`).focus();
            });   
        } 
    });   

    $(document).on('click','#start_trial', function(e) {
        checkOpeningTest().then(res => {
            if(res=='open'){
                $(`#confirmLatihan`).modal('show');
            } else {
                errorAjax('Anda belum bisa mengakses');
            }
        })
    });

    $(document).on('click','#start_test', function(e) {
        checkOpeningTest().then(res => {
            if(res=='open'){
                $(`#confirmTest`).modal('show');
            } else {
                errorAjax('Anda belum bisa mengakses');
            }
        })
    });

    $(document).on('click','#startLatihan', function(e) {
        $(`#confirmLatihan`).modal('hide');
        $(`.header_test`).html('Latihan');
        formTest()
    });

    $(document).on('click','#startTest', function(e) {
        $(`#confirmTest`).modal('hide');
        test();
        $(`.header_test`).html('Tes');
        $('#formTest').hide();
        $('.instruction').hide();
        $('.test').show();
        $('.progress').show();
    });

    $(document).on('click','#submit_test', function(e) {
        saveAnswerKraepelinAll('redirect')
    });

</script>
@endsection