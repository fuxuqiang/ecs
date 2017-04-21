<table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;">
  <tr>
    <td>
      <div id="wrapper">
        <h3>{$lang.system_environment}</h3>
        <div class="list">
          {$lang.php_os}..........................................................................................................................<?= PHP_OS ?><br>
          {$lang.php_ver}..........................................................................................................................<?= PHP_VERSION ?><br>
          {$lang.gd_version}..........................................................................................................................<?= $gd_ver ?><br>
        </div>
        <h3>{$lang.dir_priv_checking}</h3>
        <div class="list">
        @foreach($dirCheck as $value)
          {$value.dir}.......................................................................................................................
          @if($value['rst'] == $lang['can_write'])
            <span style="color:green;">
          @else
            <span style="color:red;">
          @endif
              {$value.rst}
            </span><br />
        @endforeach
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td>
      <div id="install-btn">
        <form action="/install/set">
          <input type="button" class="button" value="{$lang.prev_step}{$lang.welcome_page}" onclick="location.href='/install?lang=<?= lang() ?>'" />
          <input type="button" class="button" value="{$lang.recheck}" onclick="check()" />
          <input type="hidden" name="lang" value="<?= lang() ?>">
          <input type="submit" class="button" value="{$lang.next_step}{$lang.config_system}" {$disabled} />
        </form>
      </div>
    </td>
  </tr>
</table>