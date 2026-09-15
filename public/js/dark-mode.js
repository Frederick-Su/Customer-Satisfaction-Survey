// Dark Mode Manager
(function () {
  const STORAGE_KEY = 'theme-preference';
  const DARK_CLASS = 'dark';

  // Initialize dark mode on page load
  function initDarkMode() {
    const savedTheme = localStorage.getItem(STORAGE_KEY);
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Use saved preference, or fallback to system preference
    const shouldBeDark = savedTheme ? savedTheme === 'dark' : prefersDark;
    
    applyTheme(shouldBeDark);
  }

  // Apply theme to document
  function applyTheme(isDark) {
    if (isDark) {
      document.documentElement.classList.add(DARK_CLASS);
      document.documentElement.setAttribute('data-theme', 'dark');
      localStorage.setItem(STORAGE_KEY, 'dark');
    } else {
      document.documentElement.classList.remove(DARK_CLASS);
      document.documentElement.setAttribute('data-theme', 'light');
      localStorage.setItem(STORAGE_KEY, 'light');
    }
  }

  // Toggle theme with fade effect
  function toggleTheme() {
    const isDark = document.documentElement.classList.contains(DARK_CLASS);
    const html = document.documentElement;
    
    // Add fade-out class
    html.classList.add('theme-transitioning');
    html.style.opacity = '0';
    
    // Wait for fade-out to complete
    setTimeout(() => {
      applyTheme(!isDark);
      
      // Trigger fade-in
      setTimeout(() => {
        html.style.opacity = '1';
      }, 10);
    }, 150);
    
    // Remove transitioning class after animation completes
    setTimeout(() => {
      html.classList.remove('theme-transitioning');
    }, 300);
    
    // Dispatch event for other parts of the app to listen to
    window.dispatchEvent(new CustomEvent('themechange', {
      detail: { theme: isDark ? 'light' : 'dark' }
    }));
  }

  // Get current theme
  function getCurrentTheme() {
    return document.documentElement.classList.contains(DARK_CLASS) ? 'dark' : 'light';
  }

  // Expose API globally
  window.DarkMode = {
    toggle: toggleTheme,
    init: initDarkMode,
    getCurrent: getCurrentTheme,
    set: applyTheme
  };

  // Auto-initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDarkMode);
  } else {
    initDarkMode();
  }
})();
