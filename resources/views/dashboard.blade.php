@extends('layouts.master')

@section('content')
<section id="vfi-container">
	<div id="loading"
	style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
	<p style="position: absolute; color: white; top: 20%; left: 10%;">
		<span style="font-size: 40px"><i class="fa fa-spin fa-refresh"></i></span>
	</p>
</div>

<div class="row">
	<div class="col-md-12" style="text-align: center;">
		<h1>
			Dashboard
		</h1>
	</div>
</div>
<div class="row">
	<div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">

		<?php if (in_array($username, $all_username)) { ?>
		<a href="{{url('index/driver/attendance/report')}}" class="btn btn-info" style="width: 100%; font-weight: bold; font-size: 20px; margin-bottom: 10px;">
			Rekam Kehadiran
		</a>
		<a href="{{url('index/driver/job')}}" class="btn btn-warning" style="width: 100%; font-weight: bold; font-size: 20px;">
			Tugas Driver
		</a>
		<?php } ?>
	</div>
</div>
</section>
@endsection

@section('scripts')
<script>
	$(document).ready(function() {
		$('#side_dashboard').addClass('menu-open');
	});
</script>
@endsection
