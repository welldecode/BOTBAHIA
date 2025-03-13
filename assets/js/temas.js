const themeSwitch = document.getElementById('themeSwitch');

window.addEventListener('load', () => {
  if (themeSwitch) {
    initTheme();
    themeSwitch.addEventListener('change', () => {
      resetTheme(themeSwitch.value);
    });
  }
});

function initTheme() {
  const savedTheme = localStorage.getItem('themeSwitch') || '';
  document.body.setAttribute('data-theme', savedTheme);
  themeSwitch.value = savedTheme;
}

function resetTheme(theme) {
  document.body.setAttribute('data-theme', theme);
  localStorage.setItem('themeSwitch', theme);
}