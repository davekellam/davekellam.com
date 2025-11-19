(function () {
	if (typeof window === 'undefined') {
		return;
	}

	var banner = document.querySelector('.dk-uc-banner');
	if (!banner) {
		return;
	}

	var settings = window.dkUcBanner || {};
	var storageKey = settings.storageKey || 'dk_uc_banner_dismissed';
	var storageTtlSeconds = parseInt(settings.storageTtl, 10);
	var storageTtlMs = Number.isNaN(storageTtlSeconds) ? 86400000 : storageTtlSeconds * 1000;
	var dismissButton = banner.querySelector('.dk-uc-dismiss');
	var storage;

	try {
		storage = window.localStorage;
	} catch (err) {
		storage = null;
	}

	function getStoredState() {
		if (!storage) {
			return null;
		}

		try {
			var raw = storage.getItem(storageKey);
			if (!raw) {
				return null;
			}

			var parsed = JSON.parse(raw);
			if (parsed.expires && Date.now() > parsed.expires) {
				storage.removeItem(storageKey);
				return null;
			}

			return parsed.value || null;
		} catch (err) {
			storage.removeItem(storageKey);
			return null;
		}
	}

	function persistDismissal() {
		if (!storage) {
			return;
		}

		var payload = {
			value: 'dismissed',
			expires: storageTtlMs > 0 ? Date.now() + storageTtlMs : null,
		};

		try {
			storage.setItem(storageKey, JSON.stringify(payload));
		} catch (err) {
			// Ignore storage errors (quota, private mode, etc.).
		}
	}

	function removeBanner(skipAnimation) {
		if (!banner || !banner.parentNode) {
			return;
		}

		banner.setAttribute('aria-hidden', 'true');

		if (skipAnimation) {
			banner.parentNode.removeChild(banner);
			return;
		}

		banner.classList.add('dk-uc-banner--hidden');
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

	if (getStoredState() === 'dismissed') {
		removeBanner(true);
		return;
	}

	if (dismissButton) {
		dismissButton.addEventListener('click', function () {
			persistDismissal();
			removeBanner(false);
		});
	}
})();