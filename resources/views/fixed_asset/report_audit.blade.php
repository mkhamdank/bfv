<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="{{ url('adminlte/dist/css/adminlte.min.css') }}" rel="stylesheet">
    <style type="text/css">
        td {
            padding: 3px;
        }

        .border1 {
            border: 1px solid black;
        }

        body {
            font-family: sans-serif;
        }

        thead {
            font-size: 9px;
            font-weight: bold;
            text-align: center;
        }

        thead>tr>td {
            background-color: #a7f2b0
        }

        tbody {
            font-size: 8px;
        }

        h3, h4 {
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div>
        <center>
            <h4>Fix Assets Check</h4>
        </center>
        <?php
        $total_aset = count($audit_data);
        $ada = 0;
        $tidak_ada = 0;
        $tidak_digunakan = 0;
        $asset_rusak = 0;
        $label_rusak = 0;
        $map = 0;
        ?>
        <table width="100%" style="border: 1px solid black">
            <tr>
                <td>
                    <h3>PT Yamaha Musical Products Indonesia</h3>
                </td>
                <td rowspan="2" style="font-size: 12px">
                    <center>
                        Tanggal :
                        <?php
                        if ($audit_data[0]->appr_manager_at) {
                            echo $audit_data[0]->appr_manager_at;
                        } else {
                            echo '___________ ';
                        }
                        ?>
                        <br>
                        Diketahui oleh
                        <br>
                        <?php
                        if ($audit_data[0]->appr_manager_by) {
                            echo $audit_data[0]->appr_manager_by;
                        } else {
                            echo '<br><br>';
                        }
                        ?>
                        <br>

                        Manager
                    </center>
                </td>
            </tr>
            <tr>
                <td style="font-size: 12px">Control Section : {{ $audit_data[0]->asset_section }}</td>
            </tr>
        </table>
        <table width="100%" style="border: 1px solid black">
            <thead>
                <tr>
                    <td>No</td>
                    <td style="border-bottom: 1px solid black" rowspan="2">SAP ID</td>
                    <td style="border-bottom: 1px solid black" rowspan="2">Gambar</td>
                    <td colspan="2" class="border1">Keberadaan</td>
                    <td colspan="5" class="border1">Kondisi Pengecualian</td>
                    <td colspan="3" class="border1">TTD</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black">Deskripsi</td>
                    <td class="border1" style="font-size: 8px">Ada</td>
                    <td class="border1" style="font-size: 8px">Tidak Ada</td>
                    <td class="border1" style="font-size: 8px">Tidak Digunakan</td>
                    <td class="border1" style="font-size: 8px">Asset Rusak</td>
                    <td class="border1" style="font-size: 8px">Label Tidak Ada/Rusak</td>
                    <td class="border1" style="font-size: 8px">Map Tidak Sesuai</td>
                    <td class="border1" style="font-size: 8px">Lain - lain</td>
                    <td class="border1" style="font-size: 8px">Cek I</td>
                    <td class="border1" style="font-size: 8px">Cek II</td>
                    @if ($category == 'Audit')
                        <td class="border1" style="font-size: 8px">Audit</td>
                    @endif

                </tr>
            </thead>
            <tbody>
                @foreach ($audit_data as $index => $audit)
                    <tr>
                        <td>{{ ($index+1) }}</td>
                        <td>{{ $audit->sap_number }}</td>
                        <td rowspan="3" style="border-bottom: 1px solid black">
                            <img src="{{ url('data_file/fixed_asset/master_picture/' . $audit->asset_images) }}"
                                style="max-width: 60px">
                        </td>
                        <td rowspan="3" class="border1">
                            <center style="font-weight : bold">
                                <?php
                                if ($audit->availability == 'Ada') {
                                    echo 'V';
                                    $ada += 1;
                                }
                                ?>
                            </center>
                        </td>
                        <td rowspan="3" class="border1">
                            <center style="font-weight : bold">
                                <?php
                                if ($audit->availability == 'Tidak Ada') {
                                    echo 'V';
                                    $tidak_ada += 1;
                                }
                                ?>
                            </center>
                        </td>
                        <td rowspan="3" class="border1">
                            <center style="font-weight : bold">
                                <?php
                                if ($audit->usable_condition == 'Tidak Digunakan') {
                                    echo 'V';
                                    $tidak_digunakan += 1;
                                }
                                ?>
                            </center>
                        </td>
                        <td rowspan="3" class="border1">
                            <center style="font-weight : bold">
                                <?php
                                if ($audit->asset_condition == 'Rusak') {
                                    echo 'V';
                                    $asset_rusak += 1;
                                }
                                ?>
                            </center>
                        </td>
                        <td rowspan="3" class="border1">
                            <center style="font-weight : bold">
                                <?php
                                if ($audit->label_condition == 'Rusak') {
                                    echo 'V';
                                    $label_rusak += 1;
                                }
                                ?>
                            </center>
                        </td>
                        <td rowspan="3" class="border1">
                            <center style="font-weight : bold">
                                <?php
                                if ($audit->map_condition == 'Rusak') {
                                    echo 'V';
                                    $map += 1;
                                }
                                ?>
                            </center>
                        </td>
                        <td rowspan="3" class="border1">{{ $audit->note }}</td>
                        <td rowspan="3" class="border1">
                            <?php
                            if ($audit->check_one_by) {
                                echo explode('/', $audit->check_one_by)[1] . '<br> (' . explode(' ', $audit->check_one_at)[0] . ')';
                            }
                            
                            ?>
                        </td>
                        <td rowspan="3" class="border1">
                            <?php
                            if ($audit->check_two_by) {
                                echo explode('/', $audit->check_two_by)[1] . '<br> {' . explode(' ', $audit->check_two_at)[0] . ')';
                            }
                            ?>
                        </td>
                        @if ($category == 'Audit')
                        <td rowspan="3" class="border1">
                            <?php
                            if (isset($audit->checked_date)) {
                                if ($audit->checked_date && $audit->remark) {
                                    echo $audit->checked_by . '<br> (' . explode(' ', $audit->checked_date)[0] . ')';
                                }
                            }
                            ?>
                        </td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">{{ $audit->asset_name }}
                            <?php if ($audit->audit_type == 'Remote'): ?>
                            <br> : <b>Remote Audit</b>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid black"></td>
                        <td style="border-bottom: 1px solid black">{{ $audit->location }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <table style="width: 100%;">
            <tr>
                <td style="width: 30%">
                    Summary Control Section : <br>
                    {{ $audit_data[0]->asset_section }}
                </td>
                <td style="width: 70%">
                    <table border="1">
                        <tr>
                            <td rowspan="2">
                                <center>Jumlah Asset</center>
                            </td>
                            <td colspan="2">
                                <center>Keberadaan</center>
                            </td>
                            <td colspan="4">
                                <center>Kondisi Pengecualian</center>
                            </td>
                        </tr>
                        <tr>
                            <td>Ada</td>
                            <td>Tidak Ada</td>
                            <td>Asset Tidak Digunakan</td>
                            <td>Asset Rusak</td>
                            <td>Label Rusak</td>
                            <td>Map Tidak Ada</td>
                        </tr>
                        <tr>
                            <td style="text-align: right">{{ $total_aset }}</td>
                            <td style="text-align: right">{{ $ada }}</td>
                            <td style="text-align: right">{{ $tidak_ada }}</td>
                            <td style="text-align: right">{{ $tidak_digunakan }}</td>
                            <td style="text-align: right">{{ $asset_rusak }}</td>
                            <td style="text-align: right">{{ $label_rusak }}</td>
                            <td style="text-align: right">{{ $map }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
