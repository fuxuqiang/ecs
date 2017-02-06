@extends('layout')

@section('body')
<body>
  <form action="/install/checking">
    <div id="logos">
      @include('logo')
      @include('select_lang')
    </div>
    <div>
      <table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;">
        <tr align="center" style="padding-top:10px;">
          <td>
            <span id="install-btn">
              <input class="button" type="submit" value="{$lang.next_step}{$lang.check_system_environment}" />
            </span>
          </td>
        </tr>
      </table>
    </div>
  </form>
</body>
<script type="text/javascript" src="/js/select_lang.js"></script>
@endsection