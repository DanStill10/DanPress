/**
 * Mobile Menu Toggle
 */
document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.querySelector('.menu-toggle');
  const mainNav = document.querySelector('.main-navigation');

  if (menuToggle && mainNav) {
    menuToggle.addEventListener('click', () => {
      // Toggle the .is-open class on the navigation menu
      mainNav.classList.toggle('is-open');

      // Toggle the aria-expanded attribute for accessibility
      const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
      menuToggle.setAttribute('aria-expanded', !isExpanded);
    });
  }
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);