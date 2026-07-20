/**
 * Mobile Menu Toggle
 */
document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.querySelector('.menu-toggle');
  const mainNav = document.querySelector('.main-navigation');

  if (menuToggle && mainNav) {
    menuToggle.addEventListener('click', () => {
      mainNav.classList.toggle('is-open');
      const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
      menuToggle.setAttribute('aria-expanded', !isExpanded);
    });
  }

  /**
   * Hero Word Cycling
   * Cycles through comma-separated words in the hero headline.
   */
  const heroSection = document.querySelector('.dsd-hero');
  if (heroSection) {
    const wordsData = heroSection.getAttribute('data-words');
    const wordEl = heroSection.querySelector('.dsd-hero-word');

    if (wordsData && wordEl) {
      const words = wordsData.split(',').map(w => w.trim()).filter(Boolean);
      if (words.length > 1) {
        let currentIndex = 0;

        setInterval(() => {
          wordEl.classList.add('dsd-hero-word--exiting');
          wordEl.classList.remove('dsd-hero-word--active');

          setTimeout(() => {
            currentIndex = (currentIndex + 1) % words.length;
            wordEl.textContent = words[currentIndex];
            wordEl.classList.remove('dsd-hero-word--exiting');
            wordEl.classList.add('dsd-hero-word--active');
          }, 400);
        }, 3000);
      }
    }
  }
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);