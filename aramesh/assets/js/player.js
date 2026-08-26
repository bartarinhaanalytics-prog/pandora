/* Aramesh secure player
 * - URL امضاشده را از REST می‌گیرد (فقط برای مالک دوره یا پیش‌نمایش)
 * - پشتیبانی از HLS در صورت پشتیبانی مرورگر (m3u8 روی Safari)؛ در غیر این‌صورت پیام
 * - گزارش دوره‌ای پیشرفت و ذخیره موقعیت
 * - جابه‌جایی دوره‌ای واترمارک
 */
(function () {
	'use strict';
	var D = window.ArameshData || {};
	var root = document.querySelector('.aramesh-player');
	if (!root) { return; }

	var video = root.querySelector('.aramesh-player__video');
	var stateEl = root.querySelector('.aramesh-player__state');
	var watermark = root.querySelector('.aramesh-player__watermark');
	var lessonId = root.getAttribute('data-lesson');
	var endpoint = root.getAttribute('data-endpoint');

	function setState(text) {
		if (stateEl) { stateEl.textContent = text || ''; }
	}

	function loadSource() {
		setState('در حال آماده‌سازی ویدیو…');
		fetch(endpoint, {
			credentials: 'same-origin',
			headers: { 'X-WP-Nonce': D.restNonce || '' }
		}).then(function (r) { return r.json(); }).then(function (data) {
			if (!data || !data.ok || !data.url) {
				setState('ویدیوی این جلسه هنوز پیکربندی نشده است. (به مستند «امنیت ویدیو» مراجعه کنید.)');
				return;
			}
			var url = data.url;
			// HLS: در Safari مستقیم؛ در بقیه نیاز به hls.js دارد (اختیاری، توسط سایت اضافه می‌شود).
			if (/\.m3u8($|\?)/.test(url)) {
				if (video.canPlayType('application/vnd.apple.mpegurl')) {
					video.src = url;
				} else if (window.Hls && window.Hls.isSupported()) {
					var hls = new window.Hls();
					hls.loadSource(url);
					hls.attachMedia(video);
				} else {
					setState('برای پخش این ویدیو به hls.js نیاز است.');
					return;
				}
			} else {
				video.src = url;
			}
			setState('');
		}).catch(function () {
			setState('خطا در دریافت ویدیو.');
		});
	}

	/* گزارش پیشرفت */
	var lastReport = 0;
	function reportProgress(completed) {
		if (!D.isLoggedIn) { return; }
		var position = Math.floor(video.currentTime || 0);
		var body = { lesson_id: lessonId, position: position };
		if (completed) { body.completed = 'true'; }
		fetch(D.restUrl + 'progress', {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': D.restNonce || '' },
			body: JSON.stringify(body)
		}).catch(function () {});
	}

	if (video) {
		video.addEventListener('timeupdate', function () {
			var now = Date.now();
			if (now - lastReport > 15000) { // هر ۱۵ ثانیه
				lastReport = now;
				reportProgress(false);
			}
		});
		video.addEventListener('ended', function () { reportProgress(true); });
		video.addEventListener('pause', function () { reportProgress(false); });
	}

	/* جابه‌جایی واترمارک برای کاهش امکان حذف */
	if (watermark) {
		var positions = [
			{ top: '12px', bottom: 'auto', left: '12px', right: 'auto' },
			{ top: '12px', bottom: 'auto', left: 'auto', right: '12px' },
			{ top: 'auto', bottom: '12px', left: '12px', right: 'auto' },
			{ top: 'auto', bottom: '12px', left: 'auto', right: '12px' }
		];
		var pi = 0;
		setInterval(function () {
			pi = (pi + 1) % positions.length;
			var p = positions[pi];
			watermark.style.top = p.top; watermark.style.bottom = p.bottom;
			watermark.style.left = p.left; watermark.style.right = p.right;
		}, 20000);
	}

	loadSource();
})();
