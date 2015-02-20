var id;
var autoScrollDisabled = true;
var body = document.body;

function startAutoScroll() {
	if (autoScrollDisabled) {
		autoScrollDisabled = false;
		clearInterval(id);
		id = setInterval(function () {
			window.scrollTo(0, document.body.scrollHeight);
		}, 50);
	}
}

function stopAutoScroll() {
	autoScrollDisabled = true;
	clearInterval(id);
}

function wheelDirection(evt) {
	if (!evt) evt = event;
	return (evt.detail < 0) ? 1 : (evt.wheelDelta > 0) ? 1 : -1;
}

function toggleAutoScroll(evt) {
	var direction = wheelDirection(evt);
	if (direction === -1) {
		startAutoScroll();
	} else {
		stopAutoScroll();
	}
}

if (body.addEventListener) {
	body.addEventListener('mousewheel', toggleAutoScroll, false);
	body.addEventListener('DOMMouseScroll', toggleAutoScroll, false);
} else if (body.attachEvent) {
	body.attachEvent('onmousewheel', toggleAutoScroll);
}

startAutoScroll();
