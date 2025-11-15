import './bootstrap';

// Theme toggle: update DOM and persist user preference
function updateThemeIcon() {
	const isDark = document.documentElement.classList.contains('dark');
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

document.addEventListener('DOMContentLoaded', function() {
	// Ensure icon reflects current theme
	updateThemeIcon();

	document.getElementById('theme-toggle')?.addEventListener('click', function() {
		const isDark = document.documentElement.classList.contains('dark');
		setTheme(!isDark);
	});
});
