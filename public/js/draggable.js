var Draggable =
{
	bindDragNode: function(dragged, flagged)
	{
		var dragged = document.getElementById(dragged);
		var flagged = document.getElementById(flagged);

		flagged.addEventListener('mouseover', function(){
			this.style.cursor = 'move';
		});

		flagged.addEventListener('mousedown', function(event){
			event.preventDefault();
			var deltaX = event.clientX - dragged.offsetLeft;
			var deltaY = event.clientY - dragged.offsetTop;
			document.addEventListener('mousemove', moveHandler);
			document.addEventListener('mouseup', upHandler);

			function moveHandler(event) {
				var left = event.clientX - deltaX;
				var top = event.clientY - deltaY;
				dragged.style.left = left + 'px';
				dragged.style.top = top + 'px';
			}

			function upHandler() {
				document.removeEventListener('mousemove', moveHandler);
				document.removeEventListener('mouseup', upHandler);
			}
		});
	}
}