/* ------------------------------------------------------------------
   منوی موبایل، اسلایدر نظرات و رفتار آکاردئون
------------------------------------------------------------------ */
(function () {
  'use strict';

  /* ---------- Mobile navigation ---------- */
  var toggle   = document.getElementById('navToggle');
  var nav      = document.getElementById('mainNav');
  var backdrop = document.getElementById('navBackdrop');

  function setNav(open) {
    nav.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'بستن منو' : 'باز کردن منو');
    backdrop.hidden = !open;
    document.body.style.overflow = open ? 'hidden' : '';
  }

  if (toggle && nav && backdrop) {
    toggle.addEventListener('click', function () {
      setNav(!nav.classList.contains('is-open'));
    });

    backdrop.addEventListener('click', function () { setNav(false); });

    nav.addEventListener('click', function (e) {
      if (e.target.closest('a')) setNav(false);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) {
        setNav(false);
        toggle.focus();
      }
    });

    // close the drawer if the viewport grows back to desktop
    var desktop = window.matchMedia('(min-width:1025px)');
    var onChange = function (e) { if (e.matches) setNav(false); };
    if (desktop.addEventListener) desktop.addEventListener('change', onChange);
    else desktop.addListener(onChange);
  }

  /* ---------- Testimonials slider ---------- */
  document.querySelectorAll('[data-slider]').forEach(function (slider) {
    var track = slider.querySelector('[data-slider-track]');
    var prev  = slider.querySelector('[data-slider-prev]');
    var next  = slider.querySelector('[data-slider-next]');
    if (!track) return;

    function step() {
      var card = track.querySelector('li');
      if (!card) return track.clientWidth;
      var gap = parseFloat(getComputedStyle(track).columnGap || '0') || 0;
      return card.getBoundingClientRect().width + gap;
    }

    // in RTL the "forward" direction is physically to the left
    function scrollByCards(dir) {
      var rtl = getComputedStyle(track).direction === 'rtl';
      track.scrollBy({ left: step() * dir * (rtl ? -1 : 1), behavior: 'smooth' });
    }

    // dim the arrows when there is nothing left to scroll toward
    function sync() {
      var max = track.scrollWidth - track.clientWidth - 1;
      var pos = Math.abs(track.scrollLeft);
      if (prev) prev.disabled = pos <= 1;
      if (next) next.disabled = pos >= max;
    }

    if (prev) prev.addEventListener('click', function () { scrollByCards(-1); });
    if (next) next.addEventListener('click', function () { scrollByCards(1); });

    track.addEventListener('scroll', sync, { passive: true });
    window.addEventListener('resize', sync);
    sync();
  });

  /* ---------- Accordion: keep one item open ---------- */
  var items = document.querySelectorAll('.accordion .acc-item');
  items.forEach(function (item) {
    item.addEventListener('toggle', function () {
      if (!item.open) return;
      items.forEach(function (other) {
        if (other !== item) other.open = false;
      });
    });
  });

  /* ---------- Highlight the section in view ---------- */
  var links = Array.prototype.slice.call(
    document.querySelectorAll('.main-nav ul a[href^="#"]')
  );
  var targets = links
    .map(function (a) { return document.querySelector(a.getAttribute('href')); })
    .filter(Boolean);

  if ('IntersectionObserver' in window && targets.length) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        links.forEach(function (a) {
          a.classList.toggle(
            'is-active',
            a.getAttribute('href') === '#' + entry.target.id
          );
        });
      });
    }, { rootMargin: '-45% 0px -50% 0px' });

    targets.forEach(function (t) { observer.observe(t); });
  }
})();
