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

  /**
   * About Section — Image Gallery Crossfade Carousel
   */
  const carousel = document.querySelector('.dsd-ab-carousel');
  if (carousel) {
    const slides = carousel.querySelectorAll('.dsd-ab-carousel__slide');
    const dots = carousel.querySelectorAll('.dsd-ab-carousel__dot');
    const prevBtn = carousel.querySelector('.dsd-ab-carousel__btn--prev');
    const nextBtn = carousel.querySelector('.dsd-ab-carousel__btn--next');
    let currentIndex = 0;
    let autoplayInterval = null;
    const autoplayDelay = 4000;

    function goTo(index) {
      slides.forEach(s => s.classList.remove('is-active'));
      dots.forEach(d => d.classList.remove('is-active'));

      slides[index].classList.add('is-active');
      dots[index].classList.add('is-active');
      dots[index].setAttribute('aria-selected', 'true');

      const prevDot = dots[currentIndex];
      if (prevDot) prevDot.setAttribute('aria-selected', 'false');

      currentIndex = index;
    }

    function next() {
      goTo((currentIndex + 1) % slides.length);
    }

    function prev() {
      goTo((currentIndex - 1 + slides.length) % slides.length);
    }

    function startAutoplay() {
      stopAutoplay();
      autoplayInterval = setInterval(next, autoplayDelay);
    }

    function stopAutoplay() {
      if (autoplayInterval) {
        clearInterval(autoplayInterval);
        autoplayInterval = null;
      }
    }

    if (nextBtn) nextBtn.addEventListener('click', () => { next(); startAutoplay(); });
    if (prevBtn) prevBtn.addEventListener('click', () => { prev(); startAutoplay(); });

    dots.forEach(dot => {
      dot.addEventListener('click', () => {
        const idx = parseInt(dot.getAttribute('data-index'), 10);
        if (!isNaN(idx)) {
          goTo(idx);
          startAutoplay();
        }
      });
    });

    carousel.addEventListener('mouseenter', stopAutoplay);
    carousel.addEventListener('mouseleave', startAutoplay);

    if (slides.length > 1) {
      startAutoplay();
    }
  }

  /**
   * FAQ Accordion
   * Toggles FAQ items open/closed with aria attributes.
   */
  document.querySelectorAll('[data-accordion-trigger]').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const expanded = trigger.getAttribute('aria-expanded') === 'true';
      trigger.setAttribute('aria-expanded', !expanded);
    });
  });
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);