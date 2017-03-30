@extends('layout')

@section('body')
<body style="background:#DDEEF2">
	<div id="logos">
		@include('logo')
	</div>
	<div id="content">
		<div style="margin:10px;padding:20px;border: 1px solid #BBDDE5; background: #F4FAFB; ">
			<p style="font-size: 14px; text-align: center">{$lang.done}</p>
			<div align="center">
				<ul style="text-align:left; width: 260px">
					<li><a href="">{$lang.go_to_view_my_ecs}</a></li>
					<li><a href="">{$lang.go_to_view_control_panel}</a></li>
				</ul>
			</div>
		</div>
	</div>
</body>
@endsection