
var _BLOCK_COLLECTION_ = document.getElementsByClassName('block-button')

for (var i = 0; i < _BLOCK_COLLECTION_.length; i++ ){
	_BLOCK_COLLECTION_[i].addEventListener('click', function callbackCollBlock(event) {
		var block_el = event.target.parentElement.parentElement
		if (block_el.classList.contains('block-closed')) {

			animation (210, block_el.scrollHeight, block_el, false)

			block_el.classList.remove('block-closed');
			block_el.classList.add('block-opened');
			
			button_el = event.target
			button_el.innerText = "Свернуть текст" 

		} else {

			animation (block_el.scrollHeight, 210, block_el, true)

			block_el.classList.remove('block-opened');
			block_el.classList.add('block-closed');
			// изменить название кнопки
			button_el = event.target
			button_el.innerText = "Показать весь текст"
		}
		function animation (from, to, el, scroll) {
			
			var duration = 1000; // Длительность - 1 секунда
			var start = new Date().getTime(); // Время старта

			setTimeout(function() {
				var now = (new Date().getTime()) - start; // Текущее время
				var progress = now / duration; // Прогресс анимации

				var result = (to - from) * Math.pow(progress,3) + from;

				el.style.height = result + "px";

				if (progress < 1) // Если анимация не закончилась, продолжаем
					setTimeout(arguments.callee, 10);
				else if (scroll) el.scrollIntoView();
			}, 10);
		}
	})
}
