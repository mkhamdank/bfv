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
			<img src="data:image/png;base64,{{base64_encode(file_get_contents(public_path('mirai.jpg')))}}" alt=""><br>
			<p style="font-size: 18px;">Vendor Invoice Upload Information (登録情報)</p>
			This is an automatic notification. Please do not reply to this address.
			<table style="border:1px solid black; border-collapse: collapse;" width="50%">
				<thead>
					<tr>
						<th colspan="2" style="width: 100%; border:1px solid black;">
							Invoice Information
						</th>
					</tr>
					<tr>
						<th style="width: 80%; border:1px solid black;">
							Company
						</th>
						<th style="width: 20%; border:1px solid black;">
							Invoice
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $datas)

					@if($datas->open_invoice != "0")
					<tr>
						<td style="font-weight: bold; border:1px solid black;">{{ $datas->supplier_name }}</td>
						<td style="border:1px solid black;">{{ $datas->open_invoice }}</td>
					</tr>
					@endif

					@endforeach
				</tbody>
			</table>
			<br>
			<a href="{{ url('index/invoice') }}">&#10148; Click this link if you want to check invoice from vendors</a>
		</center>
	</div>
</body>
</html>