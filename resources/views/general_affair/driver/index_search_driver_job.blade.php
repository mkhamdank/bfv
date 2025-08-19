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
		<h1 style="font-size: 18px; font-weight: bold;">
			{{ $title }}<br><small style="font-weight: bold; color: #605ca8;">{{$title_jp}}</small>
		</h1>
	</div>
</div>
<div class="row">
	<div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">
		<?php if ($status == 'error') { ?>
            <p style="font-size: 20px; font-weight: bold; color: red;">Error!<br><span style="color: #605ca8; font-size: 18px;">エラー！ </span></p>
			<span style="font-size: 18px; color: red;">{{$message}}</span>
            <br>
            <span style="font-size: 18px; color: #605ca8;">{{$message_jp}}</span>
		<?php } ?>

        <?php if ($status == 'success') {
            $redirect_link = "https://new.bridgeforvendor.com/public/index/confirmation/driver/job/".base64_encode($driver_task->task_id) ?>
            <div id="redirect-loading" style="margin-top: 30px;">
                <span style="font-size: 32px; color: #605ca8;">
                    <i class="fa fa-spinner fa-spin"></i>
                </span>
                <p style="font-size: 18px; color: black; font-weight: bold;">
                Redirecting, please wait...
                </p>
                <p style="font-size: 18px; color: #605ca8; font-weight: bold;">
                    リダイレクト中です。お待ちください...
                </p>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = "{{ $redirect_link }}";
                }, 1500);
            </script>
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
