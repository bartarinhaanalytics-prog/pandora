/* Aramesh theme JS
 * - جریان OTP (درخواست/بررسی) با nonce و شمارش معکوس ارسال مجدد
 * - checkout آزمایشی/انتقال به درگاه
 * - تعاملات کوچک UI
 */
(function () {
	'use strict';

	var D = window.ArameshData || {};

	function qs(sel, ctx) { return (ctx || document).querySelector(sel); }
	function qsa(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }

	function postForm(action, data) {
		var body = new URLSearchParams();
		body.append('action', action);
		body.append('nonce', D.nonce || '');
		Object.keys(data || {}).forEach(function (k) { body.append(k, data[k]); });
		return fetch(D.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: body.toString()
		}).then(function (r) { return r.json().then(function (j) { return { ok: r.ok, status: r.status, json: j }; }); });
	}

	function showMessage(el, text, type) {
		if (!el) { return; }
		el.textContent = text;
		el.className = 'form-message is-' + (type || 'error');
	}

	/* ---------- OTP flow ---------- */
	function initOtp(form) {
		var step1 = qs('[data-otp-step="mobile"]', form);
		var step2 = qs('[data-otp-step="code"]', form);
		var mobileInput = qs('[name="mobile"]', form);
		var codeInput = qs('[name="code"]', form);
		var msg = qs('[data-otp-message]', form);
		var sendBtn = qs('[data-otp-send]', form);
		var verifyBtn = qs('[data-otp-verify]', form);
		var resendBtn = qs('[data-otp-resend]', form);
		var region = form.getAttribute('data-region') || 'iran';
		var redirect = form.getAttribute('data-redirect') || '';
		var timer = null;

		function startCooldown(seconds) {
			if (!resendBtn) { return; }
			var remaining = seconds;
			resendBtn.disabled = true;
			resendBtn.textContent = (D.i18n.resendIn || 'ارسال مجدد تا %s ثانیه').replace('%s', remaining);
			clearInterval(timer);
			timer = setInterval(function () {
				remaining--;
				if (remaining <= 0) {
					clearInterval(timer);
					resendBtn.disabled = false;
					resendBtn.textContent = D.i18n.resend || 'ارسال مجدد کد';
				} else {
					resendBtn.textContent = (D.i18n.resendIn || 'ارسال مجدد تا %s ثانیه').replace('%s', remaining);
				}
			}, 1000);
		}

		function requestCode() {
			var mobile = mobileInput ? mobileInput.value.trim() : '';
			if (!mobile) { showMessage(msg, 'شماره موبایل را وارد کنید.', 'error'); return; }
			sendBtn.disabled = true;
			var oldText = sendBtn.textContent;
			sendBtn.textContent = D.i18n.sending || 'در حال ارسال…';
			postForm('aramesh_request_otp', { mobile: mobile }).then(function (res) {
				sendBtn.disabled = false;
				sendBtn.textContent = oldText;
				if (res.json && res.json.success) {
					if (step1) { step1.classList.add('is-hidden'); }
					if (step2) { step2.classList.remove('is-hidden'); }
					if (codeInput) { codeInput.focus(); }
					var extra = (res.json.data && res.json.data.dev_code) ? ' (کد تست: ' + res.json.data.dev_code + ')' : '';
					showMessage(msg, (res.json.data && res.json.data.message ? res.json.data.message : 'کد ارسال شد.') + extra, 'success');
					startCooldown((res.json.data && res.json.data.cooldown) || 60);
				} else {
					showMessage(msg, (res.json.data && res.json.data.message) || D.i18n.genericErr, 'error');
				}
			}).catch(function () {
				sendBtn.disabled = false; sendBtn.textContent = oldText;
				showMessage(msg, D.i18n.genericErr, 'error');
			});
		}

		function verifyCode() {
			var mobile = mobileInput ? mobileInput.value.trim() : '';
			var code = codeInput ? codeInput.value.trim() : '';
			if (!code) { showMessage(msg, 'کد تایید را وارد کنید.', 'error'); return; }
			verifyBtn.disabled = true;
			postForm('aramesh_verify_otp', { mobile: mobile, code: code, region: region, redirect: redirect }).then(function (res) {
				verifyBtn.disabled = false;
				if (res.json && res.json.success) {
					showMessage(msg, res.json.data.message || 'ورود موفق.', 'success');
					window.location.href = res.json.data.redirect || (D.restUrl ? '/' : '/');
				} else {
					showMessage(msg, (res.json.data && res.json.data.message) || D.i18n.invalidCode, 'error');
				}
			}).catch(function () {
				verifyBtn.disabled = false;
				showMessage(msg, D.i18n.genericErr, 'error');
			});
		}

		if (sendBtn) { sendBtn.addEventListener('click', function (e) { e.preventDefault(); requestCode(); }); }
		if (resendBtn) { resendBtn.addEventListener('click', function (e) { e.preventDefault(); requestCode(); }); }
		if (verifyBtn) { verifyBtn.addEventListener('click', function (e) { e.preventDefault(); verifyCode(); }); }
		if (mobileInput) { mobileInput.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); requestCode(); } }); }
		if (codeInput) { codeInput.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); verifyCode(); } }); }
	}

	/* ---------- Buy / checkout ---------- */
	function initBuyButtons() {
		qsa('[data-buy-course]').forEach(function (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				var courseId = btn.getAttribute('data-buy-course');
				if (!D.isLoggedIn) {
					var loginUrl = btn.getAttribute('data-login-url') || '/login';
					window.location.href = loginUrl;
					return;
				}
				btn.disabled = true;
				var old = btn.textContent;
				btn.textContent = D.i18n.sending || 'در حال پردازش…';
				postForm('aramesh_mock_checkout', { course_id: courseId }).then(function (res) {
					btn.disabled = false; btn.textContent = old;
					if (res.json && res.json.success && res.json.data.redirect) {
						window.location.href = res.json.data.redirect;
					} else {
						alert((res.json.data && res.json.data.message) || D.i18n.genericErr);
					}
				}).catch(function () { btn.disabled = false; btn.textContent = old; });
			});
		});
	}

	/* ---------- FAQ search filter ---------- */
	function initFaqSearch() {
		var input = qs('[data-faq-search]');
		if (!input) { return; }
		input.addEventListener('input', function () {
			var q = input.value.trim().toLowerCase();
			qsa('[data-faq-item]').forEach(function (item) {
				var text = item.textContent.toLowerCase();
				item.style.display = (!q || text.indexOf(q) !== -1) ? '' : 'none';
			});
		});
	}

	/* ---------- Lead capture ---------- */
	function initLead() {
		qsa('[data-lead-form]').forEach(function (form) {
			var msg = qs('[data-lead-message]', form);
			var btn = qs('[data-lead-submit]', form);
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				var mobile = (qs('[name="mobile"]', form) || {}).value || '';
				if (btn) { btn.disabled = true; }
				postForm('aramesh_lead', { mobile: mobile.trim() }).then(function (res) {
					if (btn) { btn.disabled = false; }
					if (res.json && res.json.success) {
						showMessage(msg, res.json.data.message, 'success');
						form.reset();
					} else {
						showMessage(msg, (res.json.data && res.json.data.message) || D.i18n.genericErr, 'error');
					}
				}).catch(function () { if (btn) { btn.disabled = false; } showMessage(msg, D.i18n.genericErr, 'error'); });
			});
		});
	}

	/* ---------- Contact form ---------- */
	function initContact() {
		var form = qs('[data-contact-form]');
		if (!form) { return; }
		var msg = qs('[data-contact-message]', form);
		var btn = qs('[type="submit"]', form);
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var data = {
				name: (qs('[name="name"]', form) || {}).value || '',
				email: (qs('[name="email"]', form) || {}).value || '',
				phone: (qs('[name="phone"]', form) || {}).value || '',
				message: (qs('[name="message"]', form) || {}).value || ''
			};
			if (btn) { btn.disabled = true; }
			postForm('aramesh_contact', data).then(function (res) {
				if (btn) { btn.disabled = false; }
				if (res.json && res.json.success) {
					showMessage(msg, res.json.data.message, 'success');
					form.reset();
				} else {
					showMessage(msg, (res.json.data && res.json.data.message) || D.i18n.genericErr, 'error');
				}
			}).catch(function () { if (btn) { btn.disabled = false; } showMessage(msg, D.i18n.genericErr, 'error'); });
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		qsa('[data-otp-form]').forEach(initOtp);
		initBuyButtons();
		initFaqSearch();
		initLead();
		initContact();

		// انتخاب منطقه در صفحه مسیر ثبت‌نام: ذخیره برای ادامه.
		qsa('[data-region-choice]').forEach(function (a) {
			a.addEventListener('click', function () {
				try { localStorage.setItem('aramesh_region', a.getAttribute('data-region-choice')); } catch (e) {}
			});
		});
	});
})();
