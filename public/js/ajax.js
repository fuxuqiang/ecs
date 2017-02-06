var Ajax = 
{
	get: function (url, data, callback, responseType)  
	{
		url = url + '?' + this.formatData(data);
		this.send(url, null, 'GET', callback, responseType);
	},

	post: function (url, data, callback, responseType)
	{
		this.send(url, this.formatParam(data), 'POST', callback, responseType);
	},

	formatData: function (data)
	{
		if (data) {
			var param = [];
			for (var key in data) {
				param.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
			}
			return param.join('&');
		} else {
			return '';
		}
	},

	send: function (url, data, method, callback, responseType)
	{
		var xhr = new XMLHttpRequest;
		xhr.open(method, url, true);
		if (method == 'POST') {
			xhr.setRequestHeader('Content-type', 'application/x-www-urlencoded');
		}
		xhr.onreadystatechange = function () {
			if (xhr.readyState == 4 && xhr.status == 200) {
				response = (responseType == 'json')? JSON.parse(xhr.responseText):xhr.responseText;
				callback(response);
			}
		}
		xhr.send(data);
	}
}