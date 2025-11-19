(function () {
	if (typeof window === 'undefined') {
		return;
	}

	var banner = document.querySelector('.dk-uc-banner');
	if (!banner) {
		return;
	}

	var settings = window.dkUcBanner || {};
	var cookieName = settings.cookieName || 'dk_uc_banner_dismissed';
	var cookieMaxAge = parseInt(settings.cookieMaxAge, 10) || 86400;
	var dismissButton = banner.querySelector('.dk-uc-dismiss');

	function setCookie() {
		var cookieValue = cookieName + '=1; path=/; max-age=' + cookieMaxAge + '; SameSite=Lax';
		if (window.location.protocol === 'https:') {
			cookieValue += '; Secure';
		}
		document.cookie = cookieValue;
	}

	function hideBanner() {
		banner.classList.add('dk-uc-banner--hidden');
		banner.setAttribute('aria-hidden', 'true');
		banner.addEventListener(
			'transitionend',
			function handleTransitionEnd() {
				banner.removeEventListener('transitionend', handleTransitionEnd);
				if (banner && banner.parentNode) {
					banner.parentNode.removeChild(banner);
				}
			}
		);
	}

	if (dismissButton) {
		dismissButton.addEventListener('click', function () {
			hideBanner();
			setCookie();
		});
	}
})();