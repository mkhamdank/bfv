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
    select[readonly].select2-hidden-accessible+.select2-container {
        pointer-events: none;
        touch-action: none;

        .select2-selection {
            background: #eee;
            box-shadow: none;
        }

        .select2-selection__arrow,
        select[readonly].select2-hidden-accessible+.select2-container .select2-selection__clear {
            display: none;
        }
    }

    .form-control[readonly] {
        background-color: #d3d3d3 !important;
        opacity: 1;
    }

    .bg-ympi {
        background-color: #605ca8;
    }

    /* Kompak untuk mobile */
    .col-kraepelin {
        display: inline-block;
        vertical-align: top;
        padding-right: 0px;
    }

    .kp-col-inner {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        position: relative;
        width: 36px;
    }

    /* Setiap pair: angka di kiri atas, kotak input di kanan tengah-bawah */
    .kp-pair {
        position: relative;
        width: 36px;
        height: 32px;
        margin-bottom: 0px;
    }

    .kp-num {
        font-size: 13px;
        font-weight: bold;
        line-height: 1;
        text-align: center;
        position: absolute;
        left: 0;
        top: 2px;
        width: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .kp-input {
        width: 20px !important;
        height: 20px !important;
        text-align: center;
        padding: 0 !important;
        margin: 0 !important;
        font-size: 12px !important;
        font-weight: bold;
        border: 1px solid #aaa;
        border-radius: 2px;
        color: #c00;
        background: #aba6a6;
        line-height: 1;
        position: absolute;
        right: 0;
        bottom: 0px;
    }

    .kp-input:focus {
        background: #fff !important;
        border-color: #605ca8;
        outline: none;
    }

    .kp-col-label {
        font-size: 11px;
        color: red;
        font-weight: bold;
        text-align: center;
        margin-top: 3px;
        width: 36px;
    }

    #test,
    #trial_test {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Progress bar sticky di atas */
    .progress {
        position: sticky !important;
        top: 0;
        z-index: 100;
        border-radius: 0 !important;
    }

    /* ===== NUMPAD STYLES ===== */
    #numpad-fixed {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 9999;
        display: none;
    }

    #numpad-toggle-btn {
        display: block;
        width: 100%;
        padding: 6px 0;
        background: #605ca8;
        color: #fff;
        font-size: 14px;
        font-weight: bold;
        border: none;
        border-radius: 0;
        text-align: center;
        cursor: pointer;
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
    }

    #numpad-body {
        background: #e8e8e8;
        border-top: 2px solid #605ca8;
        padding: 8px 8px 12px 8px;
    }

    .numpad-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 7px;
        max-width: 360px;
        margin: 0 auto;
    }

    .numpad-btn {
        height: 52px;
        font-size: 22px;
        font-weight: bold;
        border: 1px solid #ccc;
        border-radius: 10px;
        background: #fff;
        color: #222;
        box-shadow: 0 3px 0 #bbb;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        touch-action: manipulation;
        user-select: none;
        -webkit-user-select: none;
        -webkit-tap-highlight-color: transparent;
    }

    .numpad-btn:active {
        background: #605ca8;
        color: #fff;
        box-shadow: 0 1px 0 #3d3a7a;
        transform: translateY(2px);
    }

    .numpad-btn.numpad-0 {
        grid-column: span 3;
    }

    /* padding bawah agar konten tidak tertutup toggle button saja (~36px) */
    body { padding-bottom: 40px; }
    body.numpad-open { padding-bottom: 260px; }
</style>

<div class="container-fluid" style="">
    <section class="content" style="padding-right: 10px;padding-left: 10px;">
        <div class="row">
            <div class="mt-2 mb-2">
                <center>
                    <h4 class="bg-ympi rounded text-white header_test" style="padding:5px;">Tes Kraepelin</h4>
                </center>
            </div>

            <form class="form-horizontal" id="formTest" style="display: block;">
                <div class="col-xs-12 col-md-12 col-lg-12 mb-2">
                    <label>Nama</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Nama" name="name" id="name"
                        value="{{ session('session_ympi_recruitment')['name']}}" readonly>
                    <input type="hidden" name="test_type" value="kraepelin" readonly>
                    <div class="text-danger fw-bold invalid name"></div>
                </div>

                <div class="col-xs-12 col-md-12 col-lg-12 mb-2">
                    <label>Tanggal Tes</label>
                    <input type="text" class="form-control" placeholder="Tanggal Tes" name="test_date" id="test_date"
                        value="{{ date('Y-m-d')}}" readonly>
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
                <div class="col-sm-12 col-xs-12 mt-3 text-center">
                    <img class="img-fluid" src="{{ asset('images/success.png')}}" style="max-height: 180px;" />
                </div>
                <div class="col-sm-12 col-xs-12 mt-3 text-center">
                    <h3>Anda telah mengerjakan</h3>
                </div>
            </div>
        @else
            <div class="row instruction" style="display: block;margin-top: 10px;">
                <h5 class="text-danger font-weight-bold">INSTRUKSI</h5>
                <div class="mt-2 mb-2">
                    <span>Dalam tes ini Anda akan menemukan kolom-kolom yang terdiri dari angka. Tugas Anda adalah
                        :</span><br>
                </div>
                <div class="mt-2 mb-2">
                    <span>1. Menjumlahkan setiap angka dengan angka diatasnya. Penjumlahan dilakukan dari bawah ke
                        atas.</span><br>
                </div>
                <div class="mt-2 mb-2">
                    <span>2. Dari hasil penjumlahan dua angka tersebut, Anda hanya menuliskan angka satuannya saja. Angka
                        satuan tersebut diketik di sebelah kanan kolom, tepat di kedua angka yang Anda jumlahkan. Contoh
                        7+5= 12, Maka ditulis 2</span><br>
                </div>
                <div class="mt-2 mb-2">
                    <span>3. Dalam pengerjaannya dibatasi oleh waktu, kerjakan dengan cepat dan tepat. Sistem akan otomatis
                        berpindah ke kolom berikutnya walaupun baris tidak semuanya terisi</span><br>
                </div>
                <div class="mt-2 mb-2">
                    <span>4. Anda diberi kesempatan untuk melakukan latihan pengerjaan. Setelah Anda klik tombol "Mulai
                        Latihan" waktu akan langsung berjalan oleh karena itu pastikan Anda sudah siap </span><br>
                </div>
                <div class="mt-2 mb-2">
                    <span>5. Jika latihan sudah selesai akan muncul tombol "Mulai Tes" sehingga pastikan Anda sudah
                        siap</span><br>
                </div>
                <h5 class="text-danger font-weight-bold">Contoh Pengerjaan</h5>
                <div class="mt-2 mb-2">
                    <img src="{{ asset('images/recruitment/kraepelin.png')}}" class="img-fluid"><br>
                </div>

                <div class="col-sm-12 col-xs-12 mt-3">
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
                <div class="col-sm-12 col-md-12" id="test" style="overflow-x:auto;"></div>
                <div class="col-sm-12 col-xs-12 mt-3 button_submit" style="display: none;">
                    <a class="text-center btn btn-success" style="width:100%;" id="submit_test">Submit</a>
                </div>
            </div>
        @endif

    </section>
</div>

<div class="modal fade" id="confirmLatihan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">Tes Kraepelin</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body content_body">
                <h4>Anda yakin mulai latihan ?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tidak</button>
                <a id="startLatihan" style="margin-left:30px;" href="javascript:;" type="button"
                    class="btn btn-success">Ya</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmTest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">Tes Kraepelin</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body content_body">
                <span class="text-danger" style="font-size: 20px;">Saat tes berjalan tidak bisa kembali ke halaman
                    sebelumnya</span>
                <h4>Anda yakin mulai tes ?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tidak</button>
                <a id="startTest" style="margin-left:30px;" href="javascript:;" type="button"
                    class="btn btn-success">Ya</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
    <script>
        const column = ['aa', 'ab', 'ba', 'bb', 'ca', 'cb', 'da', 'db', 'ea', 'eb', 'fa', 'fb', 'ga', 'gb', 'ha', 'hb', 'ia', 'ib', 'ja', 'jb', 'ka', 'kb', 'la', 'lb', 'ma', 'mb', 'na', 'nb', 'oa', 'ob', 'pa', 'pb', 'qa', 'qb', 'ra', 'rb', 'sa', 'sb', 'ta', 'tb', 'ua', 'ub', 'va', 'vb', 'wa', 'wb', 'xa', 'xb', 'ya', 'yb'];

        const column_training = ['wb', 'xa', 'xb', 'ya', 'yb'];
        const row_old = [35, 34, 33, 32, 31, 30, 29, 28, 27, 26, 25, 24, 23, 22, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1];
        const row = [28, 27, 26, 25, 24, 23, 22, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1];
        const count_row = row.length;
        let count = parseInt("{{ count($listQuestions) }}");
        let list_questions = [];
        let column_questions = [];
        let all_train = '';
        let all_test = '';
        let t = '';
        let t_train = '';
        let t_test = '';
        let progress = document.querySelector('.progress-bar');
        let cnt = 0;
        let cntTrain = 0;
        let second_bar = 0;
        let second_barTrain = 0;
        let detik = 15;
        let myVar;
        let myVarTraining;
        let currentColumn;
        let nextColumn;
        let arr_stringify = [];
        let data_answer;
        let checkAnswerTraining = 0;
        let checkAnswer = 0;
        let answerToShow = 5;
        let answerToShow_ = {};
        let columnToShow = 3;
        let columnToShow_ = {};
        let jumlahArraySoal = answerToShow + 1;
        let jumlahArrayJawab = answerToShow;
        let stepIdentityCol = [];
        let groupIdentityCol = [];
        let stepIdentity = [];
        let groupIdentity = [];
        let arrSoal = [];
        let arrJawab = [];
        let finishTraining = '';

        for (let i = 2; i <= count_row; i++) {
            let soalTo = [];
            let jawabTo = [];
            let pengaliBatasArr = (groupIdentity.length == 0) ? 1 : groupIdentity.length + 1;
            let batasArr = (answerToShow * pengaliBatasArr) + 1;

            if (stepIdentity.length == answerToShow + 1) {
                stepIdentity = [];
                if (stepIdentity.length == 0) {
                    for (let s = i - 1; s <= batasArr; s++) {
                        arrSoal.push(s);
                    }
                    for (let j = i; j <= batasArr; j++) {
                        arrJawab.push(j);
                    }
                }
                stepIdentity.push(i);
            } else {
                if (stepIdentity.length == 0) {
                    for (let s = i - 1; s <= batasArr; s++) {
                        arrSoal.push(s);
                    }
                    for (let j = i; j <= batasArr; j++) {
                        arrJawab.push(j);
                    }
                }
                answerToShow_[i] = { 'soal': arrSoal, 'jawab': arrJawab };
                stepIdentity.push(i);
                if (stepIdentity.length == answerToShow) {
                    groupIdentity.push(i);
                    stepIdentity = [];
                    arrSoal = [];
                    arrJawab = [];
                }
            }
        }

        $.each(column, function (k_c, val) {
            if (k_c % columnToShow == 0) {
                stepIdentityCol = [];
                let batasArr = (k_c + columnToShow) - 1;
                for (let s = k_c; s <= batasArr; s++) {
                    stepIdentityCol.push(s);
                }
            }
            columnToShow_[k_c] = stepIdentityCol;
        });

        if (count > 0) {
            //LIST SOAL
            $.each({!! json_encode($listQuestions) !!}, function (i, item) {
                list_questions[i] = item;
            });

            // Helper: render satu kolom kraepelin (training)
            const renderColTrain = (item, i) => {
                let html = `<div class="col-kraepelin"><div class="kp-col-inner">`;
                let rowArr = row;
                // Baris paling bawah = row[0] = 28 (angka terbawah, tidak ada input di bawahnya)
                // Tiap pair: angka[k] di atas, input di tengah, angka[k+1] di bawah
                // Input ada di antara val dan val-1 (baris val adalah atas, val-1 adalah bawah)
                // row = [28,27,...,2,1] — soal di semua baris, jawab hanya val>1
                $.each(rowArr, function (k, val) {
                    let coordinate = item + val;
                    let angka = list_questions[coordinate] ?? '';
                    html += `<div class="kp-pair soal_${val}" data-val="${val}">`;
                    html += `<div class="kp-num">${angka}</div>`;
                    if (val > 1) {
                        let next_train = val == count_row ? 2 : val + 1;
                        let thiscol_train = val == count_row ? column_training[i + 1] : item;
                        html += `<input type="text" inputmode="none"
                            class="kp-input answer_train ${item + val}"
                            maxlength="1" size="1"
                            nexttraining="${thiscol_train + next_train}"
                            currentcoltraining="${i}" nextcoltraining="${i + 1}"
                            thiscolumn="${item}" thisrow="${val}" thiscoordinate="${item + val}"
                            readonly>`;
                    }
                    html += `</div>`;
                });
                html += `<div class="kp-col-label">${i + 1}</div>`;
                html += `</div></div>`;
                return html;
            };

            // Helper: render satu kolom kraepelin (test)
            const renderColTest = (item, i) => {
                let html = `<div class="col-kraepelin" id="col_${item}"><div class="kp-col-inner">`;
                $.each(row, function (k, val) {
                    let coordinate = item + val;
                    let angka = list_questions[coordinate] ?? '';
                    html += `<div class="kp-pair soal_${val}" data-val="${val}">`;
                    html += `<div class="kp-num">${angka}</div>`;
                    if (val > 1) {
                        let next = val == count_row ? 2 : val + 1;
                        let thiscol = val == count_row ? column[i + 1] : item;
                        let colNum = column_questions[coordinate] ?? '';
                        html += `<input type="text" inputmode="none"
                            class="kp-input answer ${item + val}"
                            maxlength="1" size="1"
                            id="${item + val}"
                            next="${thiscol + next}"
                            currentcol="${i}" nextcol="${i + 1}"
                            name="answer[]" column="${colNum}" coordinate="${item + val}"
                            thiscolumn="${item}" thisrow="${val}" thiscoordinate="${item + val}"
                            readonly>
                        <input hidden name="column_number[]" value="${colNum}">
                        <input hidden name="column[]" value="${item + val}">`;
                    }
                    html += `</div>`;
                });
                html += `<div class="kp-col-label">${i + 1}</div>`;
                html += `</div></div>`;
                return html;
            };

            //FOR TRAINING
            $.each(column_training, function (i, item) {
                t_train += renderColTrain(item, i);
            });
            all_train = `<div style="overflow-x:auto;display:flex;flex-wrap:nowrap;">${t_train}</div>`;
            $("#trial_test").html(all_train);

            // FOR TEST
            $.each({!! json_encode($columnQuestions) !!}, function (i, item) {
                column_questions[i] = item;
            });
            $.each(column, function (i, item) {
                t_test += renderColTest(item, i);
            });
            all_test = `<div style="overflow-x:auto;display:flex;flex-wrap:nowrap;">${t_test}</div>`;
            $("#test").html(all_test);
        }

        const training = (repeat = '') => {
            let startColumn = column_training[0];
            $('.instruction').hide();
            currentColumnTrain = 0;
            nextColumnTrain = 1;
            lock_column('table_training', 'answer_train', startColumn, 1)
            showingRow(2)
            startBarTraining()
            $(`input.${startColumn}2`).attr('autofocus', true).focus();
            window.kpActiveEl = $(`input.${startColumn}2`)[0];
            window.kpActiveType = 'train';
        }

        const test = (repeat = '') => {
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
            $(`input#${startColumn}2`).attr('autofocus', true).focus();
            window.kpActiveEl = $(`input#${startColumn}2`)[0];
            window.kpActiveType = 'test';
        }

        const startBarTraining = (repeat = '', nextRepeat = '') => {
            let jumlah_kolom = column_training.length;
            if (nextRepeat != '') {
                $(`input.${nextRepeat}2`).focus().prop('readonly', false).css('background-color', '#ffffff');
            }

            progress.style.width = null;
            if (repeat == 'repeat') {
                progress.innerHTML = 0;
                second_barTrain = 0;
                cntTrain = 0;
            }

            myVarTraining = setInterval(function () {
                if (currentColumnTrain < jumlah_kolom) { //mengkondisikan looping set interval ketika kolom progress blm yg terakhir

                    //mengambil urutan kolom sekarang dan kolom yg selanjutnya pada kolom yg sedang aktif dikerjakan
                    let currentColTrain = $($(':focus')[0]).attr('currentcoltraining');
                    let nextCol = $($(':focus')[0]).attr('nextcoltraining');
                    let thiscolumn = $($(':focus')[0]).attr('thiscolumn');

                    if ($(`:focus`).length == 0) {
                        if (checkAnswerTraining == 0) {
                            let startColumn = column_training[0];
                            $(`input.${startColumn}2`).focus();
                            checkAnswerTraining = 1;
                        }
                    }

                    if (parseInt(currentColTrain) < currentColumnTrain) {
                        //jika kolom yg sedang dikerjkan berbeda dgn kolom yg seharusnya dikerjakan maka pindah paksa ke kolom yg seharusnya dikerjakan
                        $(`input.${column_training[currentColumnTrain]}2`).focus().prop('readonly', false).css('background-color', '#ffffff');
                    }

                    if (second_barTrain == detik && currentColumnTrain < jumlah_kolom) {
                        //jika sudah mencapai  variabel dari (let detik) maka pindah kolom sesuai kolom yg seharusnya dikerjakan
                        currentColumnTrain += 1;
                        nextColumn += 1;

                        if (currentColumnTrain == jumlah_kolom) {
                            //jika sudah mencapai kolom terakhir maka detik berhenti dan semua inputan jadi readonly
                            finishTraining = 'yes';
                            second_barTrain = 0;
                            let input_answer = $("input.answer_train").attr('readonly', true).css({ 'background-color': '#aba6a6' });
                            $(".button_test").show().focus();
                            $.each(row, function (i, item) {
                                if (item > 11) {
                                    $(`.table_training tr.soal_${item}`).hide();
                                    $(`.table_training tr.jawab_${item}`).hide();
                                } else {
                                    $(`.table_training tr.soal_${item}`).show();
                                    $(`.table_training tr.jawab_${item}`).show();
                                }
                            });
                        }

                        if (currentColumnTrain < jumlah_kolom) {
                            //jika sudah mencapai  variabel dari (let detik) dan belum mencapai kolom terakhir maka detik akan reset ke 0 dan berjalan lagi
                            clearInterval(myVarTraining);
                            showingRow(2)
                            $("input.answer_train").attr('readonly', true).css({ 'background-color': '#aba6a6' });
                            $(`input.answer_train[thiscolumn=${column_training[parseInt(currentColTrain) + 1]}]`).prop('readonly', false).css('background-color', '#ffffff');
                            $(`input.${column_training[currentColumnTrain]}2`).focus().prop('readonly', false).css('background-color', '#ffffff');
                            window.kpActiveEl = $(`input.${column_training[currentColumnTrain]}2`)[0];
                            window.kpActiveType = 'train';
                            let nextRepeat = `${column_training[currentColumnTrain]}`;
                            startBarTraining('repeat', nextRepeat);
                        }
                    } else {
                        increaseBarTrain();
                    }
                } else {
                    $(".button_test").show().focus();
                    clearInterval(myVarTraining); return;
                }
            }, 1000);
        }

        const startBar = (repeat = '', nextRepeat = '') => {
            let jumlah_kolom = column.length;
            if (nextRepeat != '') {
                $(`input#${nextRepeat}2`).focus().prop('readonly', false).css('background-color', '#ffffff');
            }

            progress.style.width = null;
            if (repeat == 'repeat') {
                progress.innerHTML = 0;
                second_bar = 0;
                cnt = 0;
            }
            myVar = setInterval(function () {
                if (currentColumn < jumlah_kolom) { //mengkondisikan looping set interval ketika kolom progress blm yg terakhir

                    //mengambil urutan kolom sekarang dan kolom yg selanjutnya pada kolom yg sedang aktif dikerjakan
                    let currentCol = $($(':focus')[0]).attr('currentcol');
                    let nextCol = $($(':focus')[0]).attr('nextcol');
                    let thiscolumn = $($(':focus')[0]).attr('thiscolumn');

                    if ($(`:focus`).length == 0) {
                        if (checkAnswer == 0) {
                            let startColumn = 'aa';
                            $(`input#${startColumn}2`).focus();
                            checkAnswer = 1;
                        }
                    }

                    if (parseInt(currentCol) < currentColumn) {
                        //jika kolom yg sedang dikerjkan berbeda dgn kolom yg seharusnya dikerjakan maka pindah paksa ke kolom yg seharusnya dikerjakan
                        $(`#${column[currentColumn]}2`).focus().prop('readonly', false).css('background-color', '#ffffff');
                    }

                    if (second_bar == detik && currentColumn < jumlah_kolom) {
                        //jika sudah mencapai  variabel dari (let detik) maka pindah kolom sesuai kolom yg seharusnya dikerjakan
                        currentColumn += 1;
                        nextColumn += 1;

                        if (currentColumn == jumlah_kolom) {
                            //jika sudah mencapai kolom terakhir maka detik berhenti dan semua inputan jadi readonly
                            second_bar = 0;
                            let input_answer = $("input.answer").attr('readonly', true).css({ 'background-color': '#aba6a6' });
                            $(".button_submit").show();
                        }

                        if (currentColumn < jumlah_kolom) {
                            //jika sudah mencapai  variabel dari (let detik) dan belum mencapai kolom terakhir maka detik akan reset ke 0 dan berjalan lagi
                            clearInterval(myVar);
                            showingRow(2)
                            showingColumn(currentColumn);

                            let nextRepeat = `${column[currentColumn]}`;
                            $("input.answer").attr('readonly', true).css({ 'background-color': '#aba6a6' });
                            $(`input.answer[thiscolumn=${column[parseInt(currentCol) + 1]}]`).prop('readonly', false).css('background-color', '#ffffff');
                            $(`input#${column[currentColumn]}2`).focus().prop('readonly', false).css('background-color', '#ffffff');
                            window.kpActiveEl = $(`input#${column[currentColumn]}2`)[0];
                            window.kpActiveType = 'test';
                            startBar('repeat', nextRepeat);
                        }
                    } else {
                        increaseBar();
                    }
                } else {
                    $(".button_submit").show().focus();
                    clearInterval(myVar); return;
                }
            }, 1000);
        }

        const increaseBarTrain = () => {
            //styling progress bar 
            if (cntTrain > 100) { clearInterval(myVarTraining); return }
            let add_progress = 100 / detik;
            cntTrain += add_progress;
            second_barTrain += 1;
            progress.style.width = cntTrain + "%"; //utk menjadikan progress bar warna biru berjalan harus ada %
            progress.innerHTML = second_barTrain;
            $(".progress-bar").html('&nbsp;');
        }

        const increaseBar = () => {
            //styling progress bar 
            if (cnt > 100) { clearInterval(myVar); return }
            let add_progress = 100 / detik;
            cnt += add_progress;
            second_bar += 1;
            progress.style.width = cnt + "%"; //utk menjadikan progress bar warna biru berjalan harus ada %
            progress.innerHTML = second_bar;
            $(".progress-bar").html('&nbsp;');
        }

        const lock_column = (tableClass, answerName, thiscoordinate = null, thisrow = null) => {
            thisrow = parseInt(thisrow) + 1;
            // Lock semua input
            $(`.kp-input.${answerName}`).attr('readonly', true).css({ 'background-color': '#aba6a6' });
            // Unlock hanya baris aktif di kolom aktif
            $(`.kp-input.${answerName}[thiscolumn="${thiscoordinate}"][thisrow="${thisrow}"]`)
                .attr('readonly', false).css({ 'background-color': '#ffffff' });
        }

        const showingRow = (thisRow = 2) => {
            // Semua pair selalu tampil (compact vertikal — tidak perlu hide/show baris)
            $('.kp-pair').show();
        }

        const showingColumn = (thisColumn) => {
            // Semua kolom selalu tampil — scroll horizontal handle oleh overflow-x
            // Auto-scroll horizontal ke kolom aktif
            let activeCol = column[thisColumn];
            if (activeCol) {
                let $colEl = $(`#col_${activeCol}`);
                if ($colEl.length) {
                    let container = document.getElementById('test');
                    if (container) {
                        let elLeft = $colEl[0].offsetLeft;
                        container.scrollLeft = Math.max(0, elLeft - 10);
                    }
                }
            }
        }

        // Auto-scroll vertikal ke input yang sedang aktif (untuk mobile)
        const scrollToActiveInput = (inputEl) => {
            if (!inputEl) return;
            let rect = inputEl.getBoundingClientRect();
            let viewH = window.innerHeight || document.documentElement.clientHeight;
            // Kalau input tidak visible di viewport tengah, scroll ke sana
            if (rect.top < viewH * 0.25 || rect.top > viewH * 0.75) {
                inputEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        const saveAnswer = (answer_coordinate, answer_value, answer_column) => {
            let locaStorageKraeplin = [];
            let form = [];
            let arr_local = [];
            form.push({ name: 'participant_id', value: "{{ session('session_ympi_recruitment')['participant_id'] }}" });
            form.push({ name: 'date', value: "{{ date('Y-m-d') }}" });
            form.push({ name: 'coordinate', value: answer_coordinate });
            form.push({ name: 'answer', value: answer_value });
            form.push({ name: 'column_number', value: answer_column });

            let stringify = JSON.stringify(form);
            locaStorageKraeplin.push(stringify);
            arr_stringify.push(locaStorageKraeplin);

            let getAnswerKraeplin = localStorage.getItem("recruitment_ympi_kraepelin_answer");
            if (getAnswerKraeplin != null) {
                arr_local = JSON.parse(getAnswerKraeplin);
                arr_local.push(locaStorageKraeplin);
                data_answer = JSON.stringify(arr_local);
                localStorage.setItem("recruitment_ympi_kraepelin_answer", data_answer);
            } else {
                data_answer = JSON.stringify(arr_stringify);
                localStorage.setItem("recruitment_ympi_kraepelin_answer", data_answer);
            }

            $.ajax({
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}", Accept: "application/json", },
                url: "{{ url('input/ympi_recruitment/kraepelin_answer') }}",
                type: "POST",
                data: {
                    data_answer: data_answer,
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
                success: function (result) {
                    if (result.status) {
                        $('#loading').hide();
                        $('#formTest').hide();

                        let cardId = $('#card_id').val();
                        let setDataTest = { 'test_type': 'kraepelin', 'test_date': "{{ date('Y-m-d') }}", 'card_id': cardId };
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
                error: function (xhr, status, error) {
                    $('#loading').hide();
                    let message = xhr.responseJSON.message;
                    errorAjax(message);
                }
            });
        }

        const checkOpeningTest = async (step = 1) => {
            let result;
            result = await $.ajax({
                url: "{{ url('fetch/ympi_recruitment/check_opening_test') }}",
                type: "GET",
                success: function (res) {
                },
                error: function (err) {
                    if (step < 5) {
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
            if (String.fromCharCode(charCode).match(/[^0-9]/g) || (second_barTrain < 1 && thiscolumn == 'yb')) {
                return false;
            }
            if (!(String.fromCharCode(charCode).match(/[^0-9]/g))) {
                if (!$(this).is('[readonly]')) {
                    $(this).val('');
                }

                $(this).keyup(function (e) {
                    showingRow();
                    lock_column('table_training', 'answer_train', thiscolumn, thisrow)
                    $(`input.answer_train[thisrow="${thisrow}"]`).attr('readonly', true).css({ 'background-color': '#aba6a6' });
                    let nextEl = $(`input.${next}`)[0];
                    $(`input.${next}`).focus();
                    scrollToActiveInput(nextEl);
                });
            }
        });

        $('input.answer').keypress(function (e) {
            var charCode = (e.which) ? e.which : event.keyCode;
            let next = $(this).attr('next');
            let thiscolumn = $(this).attr('thiscolumn');
            let thisrow = $(this).attr('thisrow');

            if (String.fromCharCode(charCode).match(/[^0-9]/g) || (second_bar < 1 && thiscolumn == 'yb')) {
                return false;
            }
            if (!(String.fromCharCode(charCode).match(/[^0-9]/g))) {
                if (!$(this).is('[readonly]')) {
                    $(this).val('');
                }

                $(this).keyup(function (e) {
                    let answer_coordinate = $(this).attr('coordinate');
                    let answer_value = $(this).val();
                    let answer_column = $(this).attr('column');
                    let currentCol = $(this).attr('currentcol');
                    showingColumn(currentCol);

                    saveAnswer(answer_coordinate, answer_value, answer_column)
                    showingRow();
                    lock_column('table_test', 'answer', thiscolumn, thisrow);
                    $(`input.answer[thisrow="${thisrow}"]`).attr('readonly', true).css({ 'background-color': '#aba6a6' });
                    let nextEl = $(`input#${next}`)[0];
                    $(`input#${next}`).focus();
                    scrollToActiveInput(nextEl);
                });
            }
        });

        $(document).on('click', '#start_trial', function (e) {
            checkOpeningTest().then(res => {
                if (res == 'open') {
                    $(`#confirmLatihan`).modal('show');
                } else {
                    errorAjax('Anda belum bisa mengakses');
                }
            })
        });

        $(document).on('click', '#start_test', function (e) {
            checkOpeningTest().then(res => {
                if (res == 'open') {
                    $(`#confirmTest`).modal('show');
                } else {
                    errorAjax('Anda belum bisa mengakses');
                }
            })
        });

        $(document).on('click', '#startLatihan', function (e) {
            $(`#confirmLatihan`).modal('hide');
            $(`.header_test`).html('Latihan');
            formTest()
        });

        $(document).on('click', '#startTest', function (e) {
            $(`#confirmTest`).modal('hide');
            test();
            $(`.header_test`).html('Tes');
            $('#formTest').hide();
            $('.instruction').hide();
            $('.test').show();
            $('.progress').show();
        });

        $(document).on('click', '#submit_test', function (e) {
            saveAnswerKraepelinAll('redirect')
        });

        // ===== NUMPAD =====
        // Track input aktif di window level — tidak bergantung pada DOM focus
        window.kpActiveEl = null;
        window.kpActiveType = null; // 'train' atau 'test'

        function kpSetActive(el, type) {
            window.kpActiveEl = el;
            window.kpActiveType = type;
        }

        function kpInput(val) {
            var el = window.kpActiveEl;
            if (!el || $(el).is('[readonly]')) {
                // Coba ambil input pertama yang tidak readonly
                var $fallback = $('input.kp-input:not([readonly])').first();
                if (!$fallback.length) return;
                el = $fallback[0];
                window.kpActiveEl = el;
                window.kpActiveType = $(el).hasClass('answer_train') ? 'train' : 'test';
            }

            var $inp = $(el);
            var thiscolumn = $inp.attr('thiscolumn');
            var thisrow = $inp.attr('thisrow');

            if (window.kpActiveType === 'train') {
                if (second_barTrain < 1 && thiscolumn == 'yb') return;
                var next = $inp.attr('nexttraining');

                $inp.val(val);
                showingRow();
                lock_column('table_training', 'answer_train', thiscolumn, thisrow);
                $('input.answer_train[thisrow="' + thisrow + '"]').attr('readonly', true).css({'background-color': '#aba6a6'});

                var $next = $('input.' + next);
                if ($next.length) {
                    window.kpActiveEl = $next[0];
                    scrollToActiveInput($next[0]);
                } else {
                    window.kpActiveEl = null;
                }

            } else if (window.kpActiveType === 'test') {
                if (second_bar < 1 && thiscolumn == 'yb') return;
                var next = $inp.attr('next');
                var answer_coordinate = $inp.attr('coordinate');
                var answer_column = $inp.attr('column');
                var currentCol = $inp.attr('currentcol');

                $inp.val(val);
                showingColumn(currentCol);
                saveAnswer(answer_coordinate, val, answer_column);
                showingRow();
                lock_column('table_test', 'answer', thiscolumn, thisrow);
                $('input.answer[thisrow="' + thisrow + '"]').attr('readonly', true).css({'background-color': '#aba6a6'});

                var $next = $('input#' + next);
                if ($next.length) {
                    window.kpActiveEl = $next[0];
                    scrollToActiveInput($next[0]);
                } else {
                    window.kpActiveEl = null;
                }
            }
        }

        // Numpad sudah ada di HTML (inline di dalam .trial_test dan .test)

        // Pakai touchend bukan click — lebih responsif di mobile, hindari 300ms delay
        $(document).on('touchend mouseup', '.numpad-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var val = $(this).attr('data-n');
            kpInput(val);
        });

        // Inject satu numpad fixed dengan tombol toggle
        $('body').append(`
            <div id="numpad-fixed">
                <button type="button" id="numpad-toggle-btn">▲ Numpad</button>
                <div id="numpad-body" style="display:none;">
                    <div class="numpad-grid">
                        <button type="button" class="numpad-btn" data-n="1">1</button>
                        <button type="button" class="numpad-btn" data-n="2">2</button>
                        <button type="button" class="numpad-btn" data-n="3">3</button>
                        <button type="button" class="numpad-btn" data-n="4">4</button>
                        <button type="button" class="numpad-btn" data-n="5">5</button>
                        <button type="button" class="numpad-btn" data-n="6">6</button>
                        <button type="button" class="numpad-btn" data-n="7">7</button>
                        <button type="button" class="numpad-btn" data-n="8">8</button>
                        <button type="button" class="numpad-btn" data-n="9">9</button>
                        <button type="button" class="numpad-btn numpad-0" data-n="0">0</button>
                    </div>
                </div>
            </div>
        `);

        // Toggle buka/tutup numpad
        $(document).on('touchend click', '#numpad-toggle-btn', function(e) {
            e.preventDefault();
            var $body = $('#numpad-body');
            var isOpen = $body.is(':visible');
            if (isOpen) {
                $body.slideUp(150);
                $(this).text('▲ Numpad');
                $('body').removeClass('numpad-open');
            } else {
                $body.slideDown(150);
                $(this).text('▼ Tutup');
                $('body').addClass('numpad-open');
            }
        });

        // Set aktif saat training mulai
        $(document).on('click', '#startLatihan', function () {
            setTimeout(function() {
                $('#numpad-fixed').show();
                var $first = $('input.answer_train:not([readonly])').first();
                if ($first.length) { window.kpActiveEl = $first[0]; window.kpActiveType = 'train'; }
            }, 600);
        });

        // Set aktif saat test mulai
        $(document).on('click', '#startTest', function () {
            setTimeout(function() {
                $('#numpad-fixed').show();
                var $first = $('input.answer:not([readonly])').first();
                if ($first.length) { window.kpActiveEl = $first[0]; window.kpActiveType = 'test'; }
            }, 600);
        });

    </script>
@endsection