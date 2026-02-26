(() => {
  const STORAGE_KEY = "openpdf_theme";
  const DARK_CLASS = "theme-dark";
  const root = document.body;

  if (!root) {
    return;
  }

  const prefersDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
  const stored = window.localStorage.getItem(STORAGE_KEY);
  const initial = stored || (prefersDark ? "dark" : "light");

  applyTheme(initial);

  document.addEventListener("click", (event) => {
    const button = event.target.closest("[data-theme-toggle]");
    if (!button) {
      return;
    }

    const next = root.classList.contains(DARK_CLASS) ? "light" : "dark";
    window.localStorage.setItem(STORAGE_KEY, next);
    applyTheme(next);
  });

  function applyTheme(theme) {
    root.classList.toggle(DARK_CLASS, theme === "dark");

    const icon = document.querySelector("[data-theme-toggle] i");
    if (icon) {
      icon.className = theme === "dark" ? "bi bi-sun-fill" : "bi bi-moon-stars-fill";
    }
  }
})();
