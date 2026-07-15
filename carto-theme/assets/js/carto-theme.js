/**
 * CARTO Theme — carto-theme.js
 * Navigation mobile, scroll reveal, utilitaires UI.
 */

(function () {
  'use strict';

  /* ─── Navigation mobile ─────────────────────────────────────────────── */
  var toggle = document.getElementById('nav-toggle');
  var nav    = document.getElementById('site-navigation');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var isOpen = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // Fermer au clic sur un lien
    nav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });

    // Fermer au clic dehors
    document.addEventListener('click', function (e) {
      if (!nav.contains(e.target) && !toggle.contains(e.target)) {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* ─── Scroll Reveal ─────────────────────────────────────────────────── */
  var revealEls = document.querySelectorAll('.reveal-on-scroll');

  if (revealEls.length && 'IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    revealEls.forEach(function (el) { observer.observe(el); });
  } else {
    // Fallback sans IntersectionObserver
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* ─── Topbar : ombre au scroll ──────────────────────────────────────── */
  var header = document.querySelector('.site-header');
  if (header) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 20) {
        header.style.boxShadow = '0 4px 32px rgba(0,0,0,0.5)';
      } else {
        header.style.boxShadow = 'none';
      }
    }, { passive: true });
  }

  /* ─── Smooth scroll ancres internes ────────────────────────────────── */
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var id = this.getAttribute('href').slice(1);
      var target = id ? document.getElementById(id) : null;
      if (target) {
        e.preventDefault();
        var offset = 80; // hauteur topbar
        var top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  /* ─── Mise en évidence nav active au scroll ─────────────────────────── */
  var sections = document.querySelectorAll('section[id]');
  var navLinks  = document.querySelectorAll('.main-navigation a');

  if (sections.length && navLinks.length) {
    var onScroll = function () {
      var scrollPos = window.scrollY + 100;
      sections.forEach(function (section) {
        if (
          section.offsetTop <= scrollPos &&
          section.offsetTop + section.offsetHeight > scrollPos
        ) {
          navLinks.forEach(function (link) {
            link.classList.remove('current-in-view');
            if (link.getAttribute('href') === '#' + section.id) {
              link.classList.add('current-in-view');
            }
          });
        }
      });
    };
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ─── Copier les blocs de code au clic ─────────────────────────────── */
  document.querySelectorAll('.entry-content pre').forEach(function (pre) {
    var btn = document.createElement('button');
    btn.className = 'btn btn--outline';
    btn.style.cssText = 'position:absolute;top:12px;right:12px;padding:6px 12px;font-size:11px';
    btn.textContent = 'Copier';
    pre.style.position = 'relative';
    pre.appendChild(btn);

    btn.addEventListener('click', function () {
      var code = pre.querySelector('code');
      if (code) {
        navigator.clipboard.writeText(code.textContent).then(function () {
          btn.textContent = '✓ Copié';
          setTimeout(function () { btn.textContent = 'Copier'; }, 2000);
        });
      }
    });
  });

})();
