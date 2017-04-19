(function () {
    document.getElementById(getLang()).setAttribute('checked', 'checked');
    var langs = document.getElementsByName('lang');
    for (var i = 0; i < langs.length; i++) {
        langs[i].onclick = function () {
         	location.href = '?lang='+this.getAttribute('id');
        }
    }
})();

function getLang()
{
	var lang = location.search.match(/lang=([-\w]+)/);
	return lang ? lang[1] : 'cmn-Hans';
}