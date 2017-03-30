function isPwdInvalid(pwd) {
  return pwd.length >= 8 && /\d+/.test(pwd) && /[a-zA-z]+/.test(pwd);
}

function htmlOfNo(msg) {
  return '<span class="comment"><img src="/img/no.gif">'+msg+'</span>';
}