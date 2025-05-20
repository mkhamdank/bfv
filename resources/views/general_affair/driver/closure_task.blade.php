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
			Menyelesaikan Tugas
		</h1>
	</div>
</div>
<div class="row">
	<div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">
		<?php if ($status == 'error') { ?>
			<p style="font-size: 20px; font-weight: bold; color: red;">Error!</p>
			<span style="font-size: 18px; color: red;">{{$message}}</span>
		<?php } ?>

		<?php if ($status == 'success') { ?>
			<p style="font-size: 20px; font-weight: bold; color: green;">Success!</p>
			<span style="font-size: 18px; color: green;"><?php echo $message ?></span>
		<?php } ?>
	</div>
</div>
</section>
@endsection

@section('scripts')
<script>
	$(document).ready(function() {
		$('body').toggleClass("sidebar-collapse");
	});
</script>
@endsection
