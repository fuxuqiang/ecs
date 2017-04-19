@extends('layout')

@section('body')
<body>
  <div id="logos">
    @include('logo')
    @include('select_lang')
  </div>
  <div id="content">
    <p style="font-size:30px;text-align: center;margin-top:50px;">
      {$lang.loading}
    </p>
    <img src='/img/loading.gif' style="margin:30px 0 50px 0;"/>
  </div>
</body>
<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/install/select_lang.js"></script>
<script type="text/javascript">
  function check() {
    Ajax.get('/install/checked', {lang: '<?= lang() ?>'}, function (content) {
      document.getElementById('content').innerHTML = content;
    });
  }
  check();
</script>
@endsection