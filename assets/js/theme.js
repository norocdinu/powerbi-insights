/**
 * PowerBI Insights — Main Theme JavaScript
 * Vanilla JS, no dependencies. Deferred.
 */

(function () {
  'use strict';

  /* ============================================================
     DARK / LIGHT MODE TOGGLE
     ============================================================ */

  const htmlEl      = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  const STORAGE_KEY = 'pbiins_theme';

  function setTheme(theme) {
    htmlEl.setAttribute('data-theme', theme);
    localStorage.setItem(STORAGE_KEY, theme);
  }

  function initTheme() {
    const saved   = localStorage.getItem(STORAGE_KEY);
    const sysDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    setTheme(saved || (sysDark ? 'dark' : 'light'));
  }

  if (themeToggle) {
    themeToggle.addEventListener('click', function () {
      const current = htmlEl.getAttribute('data-theme');
      setTheme(current === 'dark' ? 'light' : 'dark');
    });
  }

  // Listen for system preference changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
    if (!localStorage.getItem(STORAGE_KEY)) {
      setTheme(e.matches ? 'dark' : 'light');
    }
  });

  initTheme();

  /* ============================================================
     MOBILE NAVIGATION
     ============================================================ */

  const menuToggle  = document.getElementById('menuToggle');
  const mobileNav   = document.getElementById('mobileNav');
  const iconMenu    = menuToggle && menuToggle.querySelector('.icon-menu');
  const iconClose   = menuToggle && menuToggle.querySelector('.icon-close');

  if (menuToggle && mobileNav) {
    menuToggle.addEventListener('click', function () {
      const isOpen = mobileNav.classList.toggle('is-open');
      menuToggle.setAttribute('aria-expanded', isOpen.toString());
      mobileNav.setAttribute('aria-hidden', (!isOpen).toString());
      if (iconMenu)  iconMenu.style.display  = isOpen ? 'none'  : '';
      if (iconClose) iconClose.style.display = isOpen ? ''      : 'none';
    });
  }

  /* ============================================================
     SEARCH OVERLAY
     ============================================================ */

  const searchToggle  = document.getElementById('searchToggle');
  const searchOverlay = document.getElementById('searchOverlay');
  const searchClose   = document.getElementById('searchClose');

  function openSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.add('is-open');
    searchOverlay.setAttribute('aria-hidden', 'false');
    if (searchToggle) searchToggle.setAttribute('aria-expanded', 'true');
    const input = searchOverlay.querySelector('input[type="search"]');
    if (input) setTimeout(() => input.focus(), 50);
  }

  function closeSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.remove('is-open');
    searchOverlay.setAttribute('aria-hidden', 'true');
    if (searchToggle) searchToggle.setAttribute('aria-expanded', 'false');
  }

  if (searchToggle) searchToggle.addEventListener('click', openSearch);
  if (searchClose)  searchClose.addEventListener('click', closeSearch);

  if (searchOverlay) {
    searchOverlay.addEventListener('click', function (e) {
      if (e.target === searchOverlay) closeSearch();
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeSearch();
  });

  /* ============================================================
     READING PROGRESS BAR
     ============================================================ */

  const progressBar = document.getElementById('readingProgressBar');

  if (progressBar) {
    function updateProgress() {
      const doc     = document.documentElement;
      const scrolled = doc.scrollTop || document.body.scrollTop;
      const total   = doc.scrollHeight - doc.clientHeight;
      const pct     = total > 0 ? (scrolled / total) * 100 : 0;
      progressBar.style.width = pct.toFixed(2) + '%';
    }

    window.addEventListener('scroll', updateProgress, { passive: true });
  }

  /* ============================================================
     BACK TO TOP BUTTON
     ============================================================ */

  const backToTop = document.getElementById('backToTop');

  if (backToTop) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 400) {
        backToTop.classList.add('visible');
      } else {
        backToTop.classList.remove('visible');
      }
    }, { passive: true });

    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ============================================================
     COPY CODE BUTTONS
     ============================================================ */

  function initCopyButtons() {
    const btns = document.querySelectorAll('.copy-code-btn');
    btns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const code = btn.getAttribute('data-copy') || '';
        // Prefer pre sibling content if no data-copy attribute
        let textToCopy = code;
        if (!textToCopy) {
          const pre = btn.closest('.code-block-wrap')?.querySelector('pre code');
          if (pre) textToCopy = pre.textContent || '';
        }

        if (!navigator.clipboard) {
          // Fallback for older browsers
          const ta = document.createElement('textarea');
          ta.value = textToCopy;
          ta.style.position = 'absolute';
          ta.style.left = '-9999px';
          document.body.appendChild(ta);
          ta.select();
          document.execCommand('copy');
          document.body.removeChild(ta);
          showCopied(btn);
          return;
        }

        navigator.clipboard.writeText(textToCopy).then(function () {
          showCopied(btn);
        });
      });
    });
  }

  function showCopied(btn) {
    const original = btn.innerHTML;
    btn.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
      Copied!
    `;
    btn.style.color = '#16c75a';
    setTimeout(function () {
      btn.innerHTML = original;
      btn.style.color = '';
    }, 2000);
  }

  /* ============================================================
     NEWSLETTER FORM
     ============================================================ */

  const newsletterForm = document.getElementById('newsletterForm');

  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const email   = newsletterForm.querySelector('input[type="email"]');
      const msgEl   = newsletterForm.querySelector('.newsletter-msg');
      const btn     = newsletterForm.querySelector('button[type="submit"]');

      if (!email || !email.value) return;

      const data = new FormData();
      data.append('action', 'pbiins_newsletter');
      data.append('email', email.value);
      data.append('nonce', (window.pbiinsData && window.pbiinsData.nonce) || '');

      btn.disabled    = true;
      btn.textContent = 'Subscribing…';

      fetch((window.pbiinsData && window.pbiinsData.ajaxUrl) || '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: data,
      })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (msgEl) {
            msgEl.textContent = res.data?.message || 'Thank you!';
            msgEl.style.display = 'block';
            msgEl.style.color   = res.success ? 'var(--clr-success)' : '#e53e3e';
          }
          if (res.success) email.value = '';
        })
        .catch(function () {
          if (msgEl) {
            msgEl.textContent = 'Something went wrong. Please try again.';
            msgEl.style.display = 'block';
            msgEl.style.color   = '#e53e3e';
          }
        })
        .finally(function () {
          btn.disabled    = false;
          btn.textContent = 'Subscribe';
        });
    });
  }

  /* ============================================================
     SMOOTH SCROLL FOR ANCHOR LINKS
     ============================================================ */

  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      const target = document.querySelector(link.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = 80; // sticky header height
        const top    = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  /* ============================================================
     INIT ON DOM READY
     ============================================================ */

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    initCopyButtons();
  }

})();
