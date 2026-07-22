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
   * Hero Word Cycling — Typewriter Effect
   * Types each word character by character, pauses, then deletes before typing the next.
   */
  const heroSection = document.querySelector('.dsd-hero');
  if (heroSection) {
    const wordsData = heroSection.getAttribute('data-words');
    const wordEl = heroSection.querySelector('.dsd-hero-word');

    if (wordsData && wordEl) {
      const words = wordsData.split(',').map(w => w.trim()).filter(Boolean);
      if (words.length > 1) {
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        const typeSpeed = 80;
        const deleteSpeed = 40;
        const pauseAfterType = 1500;
        const pauseAfterDelete = 300;

        function tick() {
          const currentWord = words[wordIndex];

          if (!isDeleting) {
            wordEl.textContent = currentWord.substring(0, charIndex + 1);
            charIndex++;

            if (charIndex === currentWord.length) {
              setTimeout(tick, pauseAfterType);
              isDeleting = true;
              return;
            }
            setTimeout(tick, typeSpeed);
          } else {
            wordEl.textContent = currentWord.substring(0, charIndex - 1);
            charIndex--;

            if (charIndex === 0) {
              isDeleting = false;
              wordIndex = (wordIndex + 1) % words.length;
              setTimeout(tick, pauseAfterDelete);
              return;
            }
            setTimeout(tick, deleteSpeed);
          }
        }

        setTimeout(tick, pauseAfterType);
      }
    }
  }
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);