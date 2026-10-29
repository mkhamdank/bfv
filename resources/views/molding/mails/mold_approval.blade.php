<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Diagnosa Molding</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; margin:0; padding:0; background-color:#f3f4f6; border-collapse:collapse;">
    <tr>
        <td align="center" style="padding:20px 10px;">

            <!--[if mso]>
            <table role="presentation" width="700" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
            <![endif]-->

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="width:100%; max-width:700px; background-color:#ffffff; border-collapse:separate; border-spacing:0; border-radius:12px; overflow:hidden;">

                <tr>
                    <td align="center"
                        style="padding:18px 24px; background-color:#f9fafb; border-bottom:1px solid #e5e7eb; color:#6b7280; font-size:14px; line-height:21px;">
                        Ini adalah email otomatis dari sistem YMPI's MIRAI. Mohon untuk tidak membalas ke alamat ini.
                    </td>
                </tr>

                <tr>
                    <td style="padding:30px 24px; background-color:#ffffff;">

                        <!-- Judul -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="width:100%; border-collapse:separate; border-spacing:0; background-color:#4b1e78; border-radius:8px;">
                            <tr>
                                <td align="center" style="padding:25px 16px; color:#ffffff;">
                                    <div style="font-size:28px; line-height:35px; font-weight:bold; margin:0 0 7px 0;">
                                        Laporan Hasil Diagnosa Molding
                                    </div>
                                    <div style="font-size:18px; line-height:24px; font-weight:bold; margin:0;">
                                        PT Yamaha Musical Products Indonesia
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- Status Hold -->
                        @if($data['status'] == 'holded')
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                   style="width:100%; margin-top:24px; border-collapse:collapse; border:1px solid #4475ef;">
                                <tr>
                                    <td colspan="2" align="center"
                                        style="padding:16px; background-color:#eff6ff; border-bottom:1px solid #4475ef; color:#4475ef; font-size:20px; line-height:26px; font-weight:bold;">
                                        APPROVAL HAS BEEN HOLDED
                                    </td>
                                </tr>
                                <tr>
                                    <td width="32%" style="padding:13px 15px; border-bottom:1px solid #dbeafe; font-size:14px; font-weight:bold;">Hold by</td>
                                    <td width="68%" style="padding:13px 15px; border-bottom:1px solid #dbeafe; font-size:14px;">: {{ $data['approval'] }}</td>
                                </tr>
                                <tr>
                                    <td width="32%" style="padding:13px 15px; font-size:14px; font-weight:bold;">Comment</td>
                                    <td width="68%" style="padding:13px 15px; font-size:14px;">: {{ $data['comment'] }}</td>
                                </tr>
                            </table>

                        <!-- Status Reject -->
                        @elseif($data['status'] == 'rejected')
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                   style="width:100%; margin-top:24px; border-collapse:collapse; border:1px solid #ef4444;">
                                <tr>
                                    <td colspan="2" align="center"
                                        style="padding:16px; background-color:#fef2f2; border-bottom:1px solid #ef4444; color:#ef4444; font-size:20px; line-height:26px; font-weight:bold;">
                                        APPROVAL HAS BEEN REJECTED
                                    </td>
                                </tr>
                                <tr>
                                    <td width="32%" style="padding:13px 15px; border-bottom:1px solid #fee2e2; font-size:14px; font-weight:bold;">Rejected by</td>
                                    <td width="68%" style="padding:13px 15px; border-bottom:1px solid #fee2e2; font-size:14px;">: {{ $data['approval'] }}</td>
                                </tr>
                                <tr>
                                    <td width="32%" style="padding:13px 15px; font-size:14px; font-weight:bold;">Comment</td>
                                    <td width="68%" style="padding:13px 15px; font-size:14px;">: {{ $data['comment'] }}</td>
                                </tr>
                            </table>

                        <!-- Status Approval Penuh -->
                        @elseif($data['status'] == 'approval_5')
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                   style="width:100%; margin-top:24px; border-collapse:collapse;">
                                <tr>
                                    <td align="center" style="padding:14px; color:#16a34a; font-size:22px; line-height:28px; font-weight:bold;">
                                        &#9989; Approval Sudah Disetujui Penuh
                                    </td>
                                </tr>
                            </table>
                        @endif

                        <!-- Detail Data -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="width:100%; margin-top:24px; border-collapse:collapse; border:1px solid #d1d5db;">
                            <tr>
                                <td width="32%" valign="top"
                                    style="padding:15px; background-color:#eff6ff; border-bottom:1px solid #d1d5db; color:#374151; font-size:14px; line-height:20px; font-weight:bold;">
                                    Nama Mold
                                </td>
                                <td width="68%" valign="top"
                                    style="padding:15px; background-color:#eff6ff; border-bottom:1px solid #d1d5db; color:#111827; font-size:15px; line-height:21px; word-break:break-word;">
                                    : {{ $data['data_form_molding']->fixed_asset_name }}
                                </td>
                            </tr>
                            <tr>
                                <td width="32%" valign="top"
                                    style="padding:15px; background-color:#ffffff; border-bottom:1px solid #d1d5db; color:#374151; font-size:14px; line-height:20px; font-weight:bold;">
                                    Jenis Produk
                                </td>
                                <td width="68%" valign="top"
                                    style="padding:15px; background-color:#ffffff; border-bottom:1px solid #d1d5db; color:#111827; font-size:15px; line-height:21px; word-break:break-word;">
                                    : {{ $data['data_form_molding']->product_category ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="32%" valign="top"
                                    style="padding:15px; background-color:#eff6ff; border-bottom:1px solid #d1d5db; color:#374151; font-size:14px; line-height:20px; font-weight:bold;">
                                    Jumlah Produksi
                                </td>
                                <td width="68%" valign="top"
                                    style="padding:15px; background-color:#eff6ff; border-bottom:1px solid #d1d5db; color:#111827; font-size:15px; line-height:21px;">
                                    : {{ number_format($data['data_form_molding']->production_qty ?? 0, 0, ',', '.') }} Shot
                                </td>
                            </tr>
                            <tr>
                                <td width="32%" valign="top"
                                    style="padding:15px; background-color:#ffffff; color:#374151; font-size:14px; line-height:20px; font-weight:bold;">
                                    Periode Cek
                                </td>
                                <td width="68%" valign="top"
                                    style="padding:15px; background-color:#ffffff; color:#111827; font-size:15px; line-height:21px;">
                                    : {{ $data['data_form_molding']->period ? date('m/Y', strtotime($data['data_form_molding']->period)) : '-' }}
                                </td>
                            </tr>
                        </table>

                        <!-- Heading Penilaian -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="width:100%; margin-top:34px; border-collapse:collapse;">
                            <tr>
                                <td style="padding:0 0 9px 0; border-bottom:1px solid #9ca3af; color:#1f2937; font-size:20px; line-height:26px; font-weight:bold;">
                                    Hasil Penilaian Akhir
                                </td>
                            </tr>
                        </table>

                        <!-- Score Cards: table agar kompatibel dengan Outlook -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="width:100%; margin-top:16px; border-collapse:collapse;">
                            <tr>
                                <td width="50%" valign="top" style="padding:0 6px 12px 0;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                           style="width:100%; border-collapse:separate; border-spacing:0; background-color:#ffffff; border:1px solid #e5e7eb; border-bottom:4px solid #c69fec; border-radius:8px;">
                                        <tr>
                                            <td align="center" style="padding:20px 8px;">
                                                <div style="color:#4f11c1; font-size:13px; line-height:18px; font-weight:bold;">Penilaian Molding</div>
                                                <div style="margin-top:8px; color:#210b37; font-size:26px; line-height:32px; font-weight:bold;">{{ $data['data_form_molding']->mold_score ?? '-' }}</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="50%" valign="top" style="padding:0 0 12px 6px;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                           style="width:100%; border-collapse:separate; border-spacing:0; background-color:#ffffff; border:1px solid #e5e7eb; border-bottom:4px solid #c69fec; border-radius:8px;">
                                        <tr>
                                            <td align="center" style="padding:20px 8px;">
                                                <div style="color:#4f11c1; font-size:13px; line-height:18px; font-weight:bold;">Penilaian Produk</div>
                                                <div style="margin-top:8px; color:#210b37; font-size:26px; line-height:32px; font-weight:bold;">{{ $data['data_form_molding']->product_score ?? '-' }}</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" valign="top" style="padding:0 6px 0 0;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                           style="width:100%; border-collapse:separate; border-spacing:0; background-color:#b7a5c9; border-bottom:4px solid #c69fec; border-radius:8px;">
                                        <tr>
                                            <td align="center" style="padding:20px 8px; color:#ffffff;">
                                                <div style="font-size:13px; line-height:18px; font-weight:bold;">Penilaian Evaluasi</div>
                                                <div style="margin-top:8px; font-size:32px; line-height:38px; font-weight:bold;">{{ $data['data_form_molding']->total_score ?? '-' }}</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="50%" valign="top" style="padding:0 0 0 6px;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                           style="width:100%; border-collapse:separate; border-spacing:0; background-color:#b7a5c9; border-bottom:4px solid #c69fec; border-radius:8px;">
                                        <tr>
                                            <td align="center" style="padding:20px 8px; color:#ffffff;">
                                                <div style="font-size:13px; line-height:18px; font-weight:bold;">Ranking</div>
                                                <div style="margin-top:8px; font-size:32px; line-height:38px; font-weight:bold;">{{ $data['data_form_molding']->rank ?? '-' }}</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <!-- Tombol Report -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="width:100%; margin-top:28px; border-collapse:collapse;">
                            <tr>
                                <td align="center">
                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate; border-spacing:0;">
                                        <tr>
                                            <td align="center" bgcolor="#dbeafe" style="border:1px solid #93c5fd; border-radius:7px;">
                                                <a href="{{ url('index/diagnose_molding/report_web/'.$data['data_form_molding']->form_number) }}"
                                                   target="_blank"
                                                   style="display:inline-block; padding:13px 25px; color:#1e3a8a; font-size:15px; line-height:20px; font-weight:bold; text-decoration:none; border-radius:7px;">
                                                    Report Diagnosa Molding
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <!-- Tombol Aksi -->
                        @if($data['status'] != 'approval_5' && $data['status'] != 'holded' && $data['status'] != 'rejected')
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                   style="width:100%; margin-top:24px; border-collapse:collapse; border-top:1px solid #e5e7eb;">
                                <tr>
                                    <td align="center" style="padding-top:22px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                                            <tr>
                                                <td align="center" style="padding:5px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate; border-spacing:0;">
                                                        <tr>
                                                            <td align="center" bgcolor="#10b981" style="border:1px solid #059669; border-radius:7px;">
                                                                <a href="{{ url('approval/diagnose_molding/approve/'.$data['status'].'/'.$data['data_form_molding']->form_number) }}"
                                                                   style="display:inline-block; min-width:105px; padding:12px 18px; color:#ffffff; font-size:15px; font-weight:bold; text-decoration:none; border-radius:7px;">
                                                                    Approve
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <td align="center" style="padding:5px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate; border-spacing:0;">
                                                        <tr>
                                                            <td align="center" bgcolor="#f59e0b" style="border:1px solid #d97706; border-radius:7px;">
                                                                <a href="{{ url('approval/diagnose_molding/hold/'.$data['status'].'/'.$data['data_form_molding']->form_number) }}"
                                                                   style="display:inline-block; min-width:105px; padding:12px 18px; color:#ffffff; font-size:15px; font-weight:bold; text-decoration:none; border-radius:7px;">
                                                                    Hold
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                @if(($data['data_form_molding']->remark ?? null) !== 'periodic')
                                                    <td align="center" style="padding:5px;">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate; border-spacing:0;">
                                                            <tr>
                                                                <td align="center" bgcolor="#ef4444" style="border:1px solid #dc2626; border-radius:7px;">
                                                                    <a href="{{ url('approval/diagnose_molding/reject/'.$data['status'].'/'.$data['data_form_molding']->form_number) }}"
                                                                       style="display:inline-block; min-width:105px; padding:12px 18px; color:#ffffff; font-size:15px; font-weight:bold; text-decoration:none; border-radius:7px;">
                                                                        Reject
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                @endif
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        @endif

                        <!-- Footer -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="width:100%; margin-top:32px; border-collapse:collapse; border-top:1px solid #e5e7eb;">
                            <tr>
                                <td align="center" style="padding-top:18px; color:#6b7280; font-size:13px; line-height:19px;">
                                    Dokumen ini dihasilkan berdasarkan data terbaru dari sistem MIRAI.
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>

            <!--[if mso]>
                    </td>
                </tr>
            </table>
            <![endif]-->

        </td>
    </tr>
</table>

</body>
</html>
