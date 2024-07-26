<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css">
.bold {
    font-weight: bold;
}
.italic {
    font-style: italic;
}
.table {
    width: 100%;
    margin-bottom: 5px;
    color: #212529;
    background-color: transparent;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.soal {
    padding-left:20px;
    padding-bottom:10px;
}
.text-center {
    vertical-align: middle !important;
    text-align: center !important;
}
.border-answer-description {
    border:1px solid;padding:8px;width:97%;
}
.background-gray {
    background-color: #b8b8b8;
}
.background-green {
    background-color: #bef3cb;
}
th, td {
/*  padding: 5px;*/
  vertical-align: middle !important;
  text-align: left !important;
  border:1px solid;
/*  border-top: 1px solid #dee2e6;*/
}
tr {
/*    padding: 0.75rem;*/
    vertical-align: middle !important;
    border: 1px solid #dee2e6;
}
.result_kraepelin td {
    border:0px !important;
}

</style>
<body>
<table class="table table-hover datatable" >
    <tr>
        <td class="bold">Nama</td>
        <td class="bold" style="width:10px;">:</td>
        <td style="width:39%;">{{ @$result['participant']->name }}</td>
        <td class="bold">Tanggal Tes</td>
        <td class="bold" style="width:10px;">:</td>
        <td>{{ @$result['participant']->test_date }}</td>
    </tr>
    <tr>
        <td class="bold">Usia</td>
        <td class="bold" >:</td>
        <td>{{ @$result['participant']->age }}</td>
        <td class="bold">Pendidikan</td>
        <td class="bold" >:</td>
        <td>{{ @$result['participant']->education  }}</td>
    </tr>
</table>

<table class="table table-hover datatable">
    <tr class="background-gray">
        <td class="bold text-center" colspan="8">Hasil Tes Kraepelin</td>
    </tr>
    <?php 
        //$bg = ['Baik Sekali' => 'background-color:#b4fdb4;','Baik' => 'background-color:#53f353;','Sedang' => 'background-color:#dae56b;','Kurang' => 'background-color:#ffa6a6f5;','Kurang Sekali' => 'background-color:#fb5151f5;','' => ''];
        $bg = ['Baik Sekali' => 'background-color:#b4fdb4;','Baik' => 'background-color:#b4fdb4;','Sedang' => 'background-color:#f7ffa4;','Kurang' => 'background-color:#ffa6a6f5;','Kurang Sekali' => 'background-color:#ffa6a6f5;','' => ''];
    ?>
    @php
        $res_panker = $result['parameter']['panker']['category'];
        $res_tianker = $result['parameter']['tianker']['category'];
        $res_janker = $result['parameter']['janker']['category'];
        $res_hanker = $result['parameter']['hanker']['category'];
        $hasilTes = $result['summary']['hasil_tes'];
        $bgHasilTes = ($hasilTes=='Lolos') ? 'background-color:#b4fdb4;' : 'background-color:#dc3545;';
    @endphp
    <tr class="">
        <td class="bold" colspan="8" style="{{ $bgHasilTes }}">
            <div class="row">
                <div class="col-sm-12 text-center" >
                    <span id="kesimpulan" >{{ $hasilTes }}</span>
                </div>
            </div>
        </td>
    </tr>
    @if($type=='view')
    <tr class="">
        <td class="bold text-center" colspan="8">
            <div class="row">
                <div class="col-sm-2 col-md-2">
                    <table>
                        <tr class="">
                            <td class="bold" style="padding:5px!important;border: none!important;">Kecepatan</td>
                            <td class="bold" style="{{ $bg[$res_panker] }}"> {{ $res_panker}}</td>
                        </tr>
                        <tr>
                            <td class="bold" style="padding:5px!important;border: none!important;">Ketelitian</td>
                            <td class="bold" style="{{ $bg[$res_tianker] }}"> {{ $res_tianker}}</td>
                        </tr>
                        <tr>
                            <td class="bold" style="padding:5px!important;border: none!important;">Keajegan</td>
                            <td class="bold" style="{{ $bg[$res_janker] }}"> {{ $res_janker}}</td>
                        </tr>
                        <tr>
                            <td class="bold" style="padding:5px!important;border: none!important;">Ketahanan</td>
                            <td class="bold" style="{{ $bg[$res_hanker] }}"> {{ $res_hanker}}</td>
                        </tr>
                        <tr>
                            <td class="bold" style="padding:5px!important;border: none!important;">Jawaban Benar</td>
                            <td class="bold" style=""> {{ $result['summary']['benar'] }}</td>
                        </tr>
                        <tr>
                            <td class="bold" style="padding:5px!important;border: none!important;">Jawaban Salah</td>
                            <td class="bold" style=""> {{ $result['summary']['salah'] }}</td>
                        </tr>
                        <tr>
                            <td class="bold" style="padding:5px!important;border: none!important;">Jawaban Tidak diisi</td>
                            <td class="bold" style=""> {{ $result['summary']['lewat'] }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-10 col-md-10">
                    <img src="{!! $result['chart'] !!}" alt="change" style="width:100%;height:400px;"/>
                </div>
            </div>
        </td>
    </tr>
    @else
    <tr class="">
        <td class="bold text-center" colspan="3">
            <table>
                <tr class="">
                    <td class="bold" style="padding:5px!important;border: none!important;">Kecepatan</td>
                    <td class="bold" style="{{ $bg[$res_panker] }}"> {{ $res_panker}}</td>
                </tr>
                <tr>
                    <td class="bold" style="padding:5px!important;border: none!important;">Ketelitian</td>
                    <td class="bold" style="{{ $bg[$res_tianker] }}"> {{ $res_tianker}}</td>
                </tr>
                <tr>
                    <td class="bold" style="padding:5px!important;border: none!important;">Keajegan</td>
                    <td class="bold" style="{{ $bg[$res_janker] }}"> {{ $res_janker}}</td>
                </tr>
                <tr>
                    <td class="bold" style="padding:5px!important;border: none!important;">Ketahanan</td>
                    <td class="bold" style="{{ $bg[$res_hanker] }}"> {{ $res_hanker}}</td>
                </tr>
                <tr>
                    <td class="bold" style="padding:5px!important;border: none!important;">Jawaban Benar</td>
                    <td class="bold" style=""> {{ $result['summary']['benar'] }}</td>
                </tr>
                <tr>
                    <td class="bold" style="padding:5px!important;border: none!important;">Jawaban Salah</td>
                    <td class="bold" style=""> {{ $result['summary']['salah'] }}</td>
                </tr>
                <tr>
                    <td class="bold" style="padding:5px!important;border: none!important;">Jawaban Tidak diisi</td>
                    <td class="bold" style=""> {{ $result['summary']['lewat'] }}</td>
                </tr>
            </table>
        </td>
        <td class="bold text-center" colspan="5">
            <img src="{!! $result['chart'] !!}" alt="change" style="width:100%;height:300px;"/>
        </td>
    </tr>
    @endif
</table>

@if($type=='view')
<table>
@php
    $count_row = count($row);
    $list_questions = [];
    $column_questions = [];
    foreach($listQuestions as $i => $item){
        $list_questions[$i] = $item;
    }
    foreach($columnQuestions as $i => $item){
        $column_questions[$i] = $item;
    }
    $t_test = '';
@endphp
    <tr class="">
        <td class="bold" colspan="8">
            <div class="row">
                <div class="col-md-12" id="test" style="height:350px;width:1180px;overflow: auto;">
                <?php
                    $legendKolom = '';

                    foreach ($column as $i => $item) {
                        $legendBaris = '';
                        if($i==0){
                            $legendBaris.= '<td style="padding-right:25px;"><table>';
                            foreach ($row as $k => $val) {
                                $legendBaris.= '<tr><td><div style="height: 26px;color:white;"></div></td><td></td></tr>';
                                if($val > 1){
                                    $nomorBaris = $val-1;
                                    $legendBaris.= '<tr><td></td><td><b style="font-size:15px;color:red;">'.$nomorBaris.'</b></td></tr>';
                                } else {
                                    $legendBaris.= '<tr><td></td><td><b style="font-size:15px;color:white;">'.$val.'</b></td></tr>';
                                }
                            }
                            $legendBaris.= '</table><td>';
                        }

                        $t_test .= $legendBaris.'<td style="padding-right:25px;"><table class="table_test _'.$item.'" id="_'.$item.'" >';
                        foreach ($row as $k => $val) {
                            $coordinate = $item.$val;
                            $bgInput = '';
                            $answerParticipant = '';
                            $t_test .= '<tr class="soal_'.$val.' coordinateSoal"><td><b style="font-size:15px;">'.@$list_questions[$coordinate].'</b></td><td><input hidden name="column_number[]" value="'.@$column_questions[$coordinate].'"></td></tr>';

                            if($val > 1){
                                $next = $val==$count_row ? 2 : $val+1;
                                $thiscol = $val==$count_row ? @$column[$i+1] : $item;
                                if(array_key_exists($coordinate, $resultAnswer)){
                                    $bgInput = $resultAnswer[$coordinate]['status']=='benar' ? 'background-color:#d9fdd9;' : 'background-color:#ffc7c7;';
                                    $answerParticipant = $resultAnswer[$coordinate]['value'];
                                }

                                $t_test .= '<tr class="jawab_'.$val.' coordinateJawab">
                                        <td></td>
                                        <td><input type="text" pattern="[0-9]*" inputmode="numeric" class="answer text-danger '.$item.$val.'" style="font-size:15px;font-weight:bold;'.$bgInput.'" maxlength="1" size="1" id="'.$item.$val.'" value="'.$answerParticipant.'" readonly>
                                        </td>
                                    </tr>';
                            } else {
                                $nomorKolom = $i+1;
                                $t_test .= '<tr class="">
                                        <td colspan="2"><div class="text-center" style="margin-top:8px;color:red;"><b>'.$nomorKolom.'</b></div></td>
                                    </tr>';
                            }
                        }
                        $t_test .='</table></td>';
                    }
                ?>
                <center><table class="result_kraepelin"><tr>{!! $t_test !!}</tr></table></center>
                </div>
            </div>
        </td>
    </tr>
</table>
@endif


</body>
</html>
