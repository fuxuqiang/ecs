(function () {
    var lang = location.search.match(/lang=([-\w]+)/);
    lang = lang ? lang[1] : 'cmn-Hans';
    document.getElementById(lang).setAttribute('checked', 'checked');
    var langs = document.getElementsByName('lang');
    for (var i = 0; i < langs.length; i++) {
        langs[i].onclick = function () {
         	location.href = '?lang='+this.getAttribute('id');
        }
    }
})();