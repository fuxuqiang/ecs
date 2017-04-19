@extends('layout')

@section('body')
<body style="background:#DDEEF2">
<div id="logos">
  @include('logo')
</div>
<div style="margin:10px;padding:20px;border: 1px solid #BBDDE5; background: #F4FAFB; ">
  <div style="font-size: 14px; text-align: center">
    <strong>{$lang.locked}</strong><br><br>
    {$lang.notice}
  </div>
</div>
</body>
@endsection