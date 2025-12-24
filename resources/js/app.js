import './bootstrap';
import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.css';

window.intlTelInput = intlTelInput;

// Theme toggle: update DOM and persist user preference
function updateThemeIcon() {
	const isDark = document.documentElement.classList.contains('dark');

	// Theme icons (same for desktop and mobile now)
	const sun = document.getElementById('theme-sun');
	const moon = document.getElementById('theme-moon');
	if (sun && moon) {
		if (isDark) {
			sun.classList.add('hidden');
			moon.classList.remove('hidden');
		} else {
			sun.classList.remove('hidden');
			moon.classList.add('hidden');
		}
	}
}

function setTheme(isDark) {
	try {
		if (isDark) {
			document.documentElement.classList.add('dark');
			localStorage.setItem('theme', 'dark');
		} else {
			document.documentElement.classList.remove('dark');
			localStorage.setItem('theme', 'light');
		}
	} catch (e) {
		// ignore
	}
	updateThemeIcon();
}

document.addEventListener('DOMContentLoaded', function () {
	// Ensure icon reflects current theme
	updateThemeIcon();

	// Theme toggle (works for both desktop and mobile)
	document.getElementById('theme-toggle')?.addEventListener('click', function () {
		const isDark = document.documentElement.classList.contains('dark');
		setTheme(!isDark);
	});
});
