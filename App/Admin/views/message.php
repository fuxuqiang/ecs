<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>{$lang.cp_home} - {$lang.system_message}</title>
  <link rel="stylesheet" type="text/css" href="/css/admin/general.css">
  <link rel="stylesheet" type="text/css" href="/css/admin/message.css">
</head>
<body>
<h1>
  <span>
    <a href="">{$lang.cp_home}</a>
  </span>
  <span>
    &nbsp;&nbsp;&nbsp;&nbsp;{$lang.system_message}
  </span>
  <div style="clear:both"></div>
</h1>
<div class="list-div message">
  <div>
    <table align="center">
      <tr>
        <td width="50" valign="top">
          @if($type == 0)
          <img src="/img/admin/information.gif" width="32" height="32" border="0" alt="information" />
          @elseif($type == 1)
          <img src="/img/admin/warning.gif" width="32" height="32" border="0" alt="warning" />
          @else
          <img src="/img/admin/confirm.gif" width="32" height="32" border="0" alt="confirm" />
          @endif
        </td>
        <td style="font-size: 14px; font-weight: bold">{$msg}</td>
      </tr>
      <tr>
        <td></td>
        <td id="redirectionMsg">
          {$lang.auto_redirection}
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <ul style="margin:0; padding:0 10px" class="msg-link">
            <li><a href="javascript:history.back()">{$lang.go_back}</a></li>
          </ul>
        </td>
      </tr>
    </table>
  </div>
</div>
@if(isset($auto_redirect))
<script language="JavaScript">
var seconds = 3;
var defaultUrl = "{$default_url}";

onload = function()
{
  if (defaultUrl == 'javascript:history.go(-1)' && window.history.length == 0)
  {
    document.getElementById('redirectionMsg').innerHTML = '';
    return;
  }

//  window.setInterval(redirection, 1000);
}
function redirection()
{
  if (seconds <= 0)
  {
    window.clearInterval();
    return;
  }

  seconds --;
  document.getElementById('spanSeconds').innerHTML = seconds;

  if (seconds == 0)
  {
    window.clearInterval();
    location.href = defaultUrl;
  }
}
//-->
</script>
@endif
<div id="footer">
{$query_info}{$memory_info}<br />
</div>
</body>
</html>