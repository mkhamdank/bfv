<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		td{
			padding-right: 5px;
			padding-left: 5px;
			padding-top: 0px;
			padding-bottom: 0px;
		}
		th{
			padding-right: 5px;
			padding-left: 5px;			
		}
	</style>
</head>
<body>
	<div>
		<center>
			
			<p style="font-size: 18px;">Vendor Covid Assessment</p>
			This is an automatic notification. Please do not reply to this address.

			<table style="border:1px solid black; border-collapse: collapse;" width="80%">
				<thead style="background-color: rgb(126,86,134);">
					<tr>
						<th style="width: 2%; border:1px solid black;">Point</th>
						<th style="width: 2%; border:1px solid black;">Content</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="width: 2%; border:1px solid black;">Tanggal</td>
						<td style="border:1px solid black; text-align: center;"><?php echo date('d F Y', strtotime($data[0]->tanggal)) ?></td></td>
					</tr>
					<tr>
						<td style="width: 2%; border:1px solid black;">Nama</td>
						<td style="border:1px solid black; text-align: center;"><?= $data[0]->name ?></td></td>
					</tr>
					<tr>
						<td style="width: 2%; border:1px solid black;">Perusahaan</td>
						<td style="border:1px solid black; text-align: center;"><?= $data[0]->company ?></td>
					</tr>
					<tr>
						<td style="width: 2%; border:1px solid black;">Hasil</td>
						@if($data[0]->result == "Positif / Reaktif")
						<td style="border:1px solid black; text-align: center;background-color: red;"><?= $data[0]->result ?></td>
						@elseif($data[0]->result == "Negatif / Non Reaktif")
						<td style="border:1px solid black; text-align: center;background-color: green;"><?= $data[0]->result ?></td>
						@endif
					</tr>
				</tbody>
			</table>
			<br>

			<span style="font-weight: bold; background-color: orange;">&#8650; <i>Klik disini untuk</i> &#8650;</span><br>
			<a href="10.109.52.4/mirai/public/index/vendor_assessment/report">Check Report Pengisian Assessment Vendor</a><br>

			<br>
			<p>
				<b>Thanks & Regards,</b>
			</p>
			<p>PT. Yamaha Musical Products Indonesia<br>
				Jl. Rembang Industri I / 36<br>
				Kawasan Industri PIER - Pasuruan<br>
				Phone   : 0343 – 740290<br>
				Fax.      : 0343 - 740291
			</p>
		</center>
	</div>
</body>
</html>