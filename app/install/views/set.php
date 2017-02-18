@extends('layout')

@section('body')
<body>
  <div id="logos">
    @include('logo')
    @include('select_lang')
  </div>
  <div id="content">
    <form action="javascript:install()">
      <table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;">
        <tr>
          <td valign="top">
            <div id="wrapper">
              <h3>{$lang.db_account}</h3>
              <table class="list">
                <tr>
                  <td width="90">{$lang.db_host}</td>
                  <td><input type="text" name="db-host" value="localhost" /></td>
                </tr>
                <tr>
                  <td width="90">{$lang.db_port}</td>
                  <td><input type="text" name="db-port" value="3306" /></td>
                </tr>
                <tr>
                  <td width="90">{$lang.db_user}</td>
                  <td><input type="text" name="db-user" value="root" /></td>
                </tr>
                <tr>
                  <td width="90">{$lang.db_pass}</td>
                  <td><input type="password" name="db-pass" /></td>
                </tr>
                <tr>
                  <td width="90">{$lang.db_name}</td>
                  <td>
                    <input type="text" name="db-name" onblur="getDbList(dbExists)" />
                    <select name="db-list">
                      <option>{$lang.db_list}</option>
                    </select>
                    <input type="button" name="go" class="button" value="{$lang.go}" onclick="getDbList(displayDbList)" />
                  </td>
                </tr>
                <tr>
                  <td width="90">{$lang.db_prefix}</td>
                  <td><input type="text" name="db-prefix"  value="ecs_" /><span class="comment">&nbsp; ({$lang.change_prefix})</span></td>
                </tr>
              </table>
              <div id="monitor" draggable="true">
                <h3 id="monitor-title">{$lang.monitor_title}</h3>
                <div style="background:#fff;padding-bottom:20px;">
                  <img id="monitor-loading" src='/img/loading.gif' /><br /><br />
                  <strong id="monitor-wait-please"></strong>
                  <span id="monitor-view-detail">{$lang.display_detail}</span>
                </div>
                <div id="monitor-notice" name="monitor-notice" style="display:none;">
                  <div id="notice"></div>
                  <a id="bottom"></a>
                </div>
                <img id="monitor-close" src='/img/close.gif' />
              </div>
              <h3>{$lang.admin_account}</h3>
              <table class="list">
                <tr>
                  <td width="90">{$lang.admin_name}</td>
                  <td><input type="text" name="admin-name" /></td>
                </tr>
                <tr>
                  <td width="90">{$lang.admin_password}</td>
                  <td>
                    <input type="password" name="admin-pwd" />
                    <span id="admin-pwd-result"></span>
                  </td>
                </tr>
                <tr>
                  <td width="90">{$lang.password_intensity}</td>
                  <td>
                    <table width="132" cellspacing="0" cellpadding="1" border="0">
                      <tr align="center">
                        <td width="33%" id="pwd_low" style="border-bottom: 2px solid red;">{$lang.pwd_low}</td>
                        <td width="33%" id="pwd_middle" style="border-bottom: 2px solid rgb(218, 218, 218);">{$lang.pwd_middle}</td>
                        <td width="33%" id="pwd_high" style="border-bottom: 2px solid rgb(218, 218, 218);">{$lang.pwd_high}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td width="90">{$lang.admin_password2}</td>
                  <td>
                    <input type="password" name="admin-pwd2" value="" />
                    <span id="confirm-pwd-result"></span>
                  </td>
                </tr>
                <tr>
                  <td width="90">{$lang.admin_email}</td>
                  <td><input type="text" name="admin-email" value="" /></td>
                </tr>
              </table>
              <h3>{$lang.mix_options}</h3>
              <table class="list">
                <tr>
                  <td width="90">{$lang.set_timezone}</td>
                  <td>
                    <input type="text" name="timezone" value="<?= date_default_timezone_get() ?>">
                  </td>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                    </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div id="install-btn">
              <input type="button" class="button" value="{$lang.prev_step}{$lang.check_system_environment}" onclick="location.href='/install/checking?lang=<?= lang() ?>'" />
              <input id="install-at-once" type="submit" class="button" value="{$lang.install_at_once}" />
            </div>
          </td>
        </tr>
      </table>
    </form>
  </div>
</body>
<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/select_lang.js"></script>
<script type="text/javascript" src="/js/drag.js"></script>
<script type="text/javascript">
  var form = document.getElementsByTagName('form')[0];
  var dbName = form['db-name'];
  var dbList = form['db-list'];
  var adminPwdResult = document.getElementById('admin-pwd-result');
  var installAtOnce = document.getElementById('install-at-once');
  var confirmPwdResult = document.getElementById('confirm-pwd-result');
  var yes = '<img src="/img/yes.gif">';
  var monitor = document.getElementById('monitor');
  var mn = document.getElementById('monitor-notice');
  var viewDetail = document.getElementById('monitor-view-detail');

  form['admin-pwd'].onkeyup = function() {
    var pwd = form['admin-pwd'].value;
    var mColor, lColor, hColor;
    var off = '2px solid #DADADA';
    var m = 0;
    var modes = 0;
    for (i=0; i<pwd.length; i++) {
      var charType;
      var t = pwd.charCodeAt(i);
      if (t>=48 && t <=57) {
        charType = 1;
      } else if (t>=65 && t <=90) {
        charType = 2;
      } else if (t>=97 && t <=122) {
        charType = 4;
      } else {
        charType = 4;
      }
      modes |= charType;
    }
    if (pwd.length <= 4) {
      m = 1;
    } else {
      while (modes) {
        if (modes & 1) m++;
        modes >>>= 1;
      }
    }
    switch(m) {
      case 1 :
        lColor = '2px solid red';
        mColor = hColor = off;
      break;
      case 2 :
        mColor = '2px solid #f90';
        lColor = hColor = off;
      break;
      case 3 :
        hColor = '2px solid #3c0';
        lColor = mColor = off;
      break;
    }
    document.getElementById('pwd_low').style.borderBottom  = lColor;
    document.getElementById('pwd_middle').style.borderBottom = mColor;
    document.getElementById('pwd_high').style.borderBottom   = hColor;
  }

  form['admin-pwd'].onblur = function(){
    var pwd = form['admin-pwd'].value;
    var pwd2 = form['admin-pwd2'].value;
    if (isPwdInvalid(pwd)) {
      adminPwdResult.innerHTML = yes;
      if (pwd == pwd2) {
        isDisabled(false);
        confirmPwdResult.innerHTML = yes;
      } else {
        isDisabled(true);
        if (pwd2 != '') {
          confirmPwdResult.innerHTML = htmlOfNo('{$lang.passwords_not_eq}');
        }
      }
    } else {
      isDisabled(true);
      if (pwd.length < 8) {
        var msg = '{$lang.password_short}';
      } else {
        var msg = '{$lang.password_invalid}';
      }
      adminPwdResult.innerHTML = htmlOfNo(msg);
    }
  }

  form['admin-pwd2'].onblur = function(){
    var pwd = form['admin-pwd'].value;
    var pwd2 = form['admin-pwd2'].value;
    if (isPwdInvalid(pwd) && pwd == pwd2) {
      isDisabled(false);
      confirmPwdResult.innerHTML = yes;
    } else {
      isDisabled(true);
      if (pwd != pwd2) {
        var msg = '{$lang.passwords_not_eq}';
      } else if (pwd2.length < 8){
        var msg = '{$lang.password_short}';
      } else {
        var msg = '{$lang.password_invalid}';
      }
      confirmPwdResult.innerHTML = htmlOfNo(msg);
    }
  }

  form['monitor-close'].onclick = function(){
      monitor.style.display = 'none';
      for (var i = 0; i < form.elements.length; i++) {
        form.elements[i].removeAttribute('disabled');
      }
  };

  viewDetail.onclick = function(){
    dispalyDetail(mn.style.display);
  }

  function getDbList(fn) {
    var result = Ajax.get('/install/getDbList', {
      host: form['db-host'].value, 
      port: form['db-port'].value,
      user: form['db-user'].value,
      pass: form['db-pass'].value
    }, function(msg){
      if (msg.status) {
        fn(msg.content);
      } else {
        alert(msg.content);
      }
    }, 'json');
  }

  function dbExists(databases) {
    for (var key in databases) {
      if (dbName.value === databases[key]) {
        if (!confirm('{$lang.db_exists}')) {
          dbName.value = '';
        }
      }
    }
  }

  function displayDbList(databases) {
    dbList.options.length = 1;
    dbList[0] = new Option('{$lang.databases_num}'.replace('%s',databases.length), '', false, false);
    for (var key in databases) {
      dbList[dbList.options.length] = new Option(databases[key], databases[key], false, false);
    }
    dbList.onchange = function () {
      dbName.value = dbList.options[dbList.selectedIndex].value;
      dbName.focus();
    };
  }

  function isPwdInvalid(pwd) {
    return pwd.length >= 8 && /\d+/.test(pwd) && /[a-zA-z]+/.test(pwd);
  }

  function htmlOfNo(msg) {
    return '<span class="comment"><img src="/img/no.gif">'+msg+'</span>';
  }

  function isDisabled(flag) {
    if (flag) {
      installAtOnce.setAttribute('disabled', true);
    } else {
      installAtOnce.removeAttribute('disabled');
    }
  }

  function install() {
    for (var i = 0; i < form.elements.length; i++) {
      form.elements[i].disabled = true;
    }
    document.getElementById('monitor-wait-please').innerHTML = '<strong style="color:blue">{$lang.wait_please}</strong>';
    monitor.style.display = 'block';
    var notice = document.getElementById('notice');
    notice.innerHTML = '{$lang.create_config_file}';
    Ajax.get('/install/createConfFile', {
      host: form['db-host'].value, 
      port: form['db-port'].value,
      user: form['db-user'].value,
      pass: form['db-pass'].value,
      name: dbName.value,
      prefix: form['db-prefix'].value,
      timezone: form['timezone'].value
    }, function(msg){
      handleMsg(msg, createDB);
    });
  }

  function createDB() {
    notice.innerHTML += '{$lang.create_database}';
    Ajax.get('/install/createDB', '', function(msg){
      handleMsg(msg, installBaseData);
    });
  }

  function installBaseData() {
    notice.innerHTML += '{$lang.install_data}';
    Ajax.get('/install/installBaseData', '', function(msg){
      handleMsg(msg, createAdminPassport);
    });
  }

  function createAdminPassport() {
    notice.innerHTML += '{$lang.create_admin_passport}';
    Ajax.get('/install/createAdminPassport', {
      name: form['admin-name'].value,
      pass: form['admin-pwd'].value,
      email: form['admin-email'].value
    }, function(msg){
      
    });
  }

  function handleMsg(msg, next) {
    if (msg == 'ok') {
      displayOKMsg();
      next();
    } else {
      displayErrorMsg(msg);
    }
  }

  function dispalyDetail(flag) {
    if (flag == 'block') {
        mn.style.display = 'none';
        viewDetail.innerHTML = '{$lang.display_detail}';
    } else {
        mn.style.display = 'block';
        viewDetail.innerHTML = '{$lang.hide_detail}';
    }
  }

  function displayOKMsg() {
      notice.innerHTML += '<span style="color:green;">{$lang.success}</span><br/>';
  }

  function displayErrorMsg(msg) {
    document.getElementById('monitor-loading').src = '/img/loaded.gif';
    document.getElementById('monitor-wait-please').innerHTML = '{$lang.has_been_stopped}';
    notice.innerHTML += '<span style="color:red;">{$lang.fail}</span><br/>';
    dispalyDetail();
    notice.innerHTML += '<strong style="color:red">'+msg+'</strong>';
  }

  Drag.bindDragNode('monitor', 'monitor-title');
</script>
@endsection