// LightPress Table of Contents Generator
// Scans h2/h3 in main content and builds a nested ToC in .toc

document.addEventListener('DOMContentLoaded', function () {
  const main = document.querySelector('main');
  const toc = document.querySelector('.toc');
  if (!main || !toc) return;

  // Find all h2, h3 (but not inside sidebar/toc)
  const headings = main.querySelectorAll('h2, h3');
  if (!headings.length) return;

  // Build TOC structure
  let tocHtml = '<div class="toc-title">On this page</div><ul class="toc-list">';
  let lastLevel = 2;
  headings.forEach(function (el, i) {
    const level = parseInt(el.tagName[1]);
    if (level < 2 || level > 3) return;
    // Generate anchor id
    let anchor = el.id;
    if (!anchor) {
      anchor = el.textContent.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
      let orig = anchor, n = 2;
      while (document.getElementById(anchor)) anchor = orig + '-' + (n++);
      el.id = anchor;
    }
    // Open new <ul> if going deeper
    if (level > lastLevel) tocHtml += '<ul>';
    // Close previous <li> if staying at same or going up a level
    if (level < lastLevel) tocHtml += '</li></ul>'.repeat(lastLevel - level) + '</li>';
    if (level === lastLevel && i !== 0) tocHtml += '</li>';
    tocHtml += `<li class="toc-level${level}"><a href="#${anchor}">${el.textContent}</a>`;
    lastLevel = level;
  });
  tocHtml += '</li></ul>'.repeat(lastLevel - 2 + 1);
  toc.innerHTML = tocHtml;

  // --- Scroll Spy ---
  const tocLinks = toc.querySelectorAll('a');
  const headingMap = {};
  headings.forEach((el) => { headingMap[el.id] = el; });

  function activateLink(id) {
    tocLinks.forEach(link => {
      link.classList.toggle('active', link.getAttribute('href') === '#' + id);
    });
  }

  // IntersectionObserver for performance
  if ('IntersectionObserver' in window) {
    let currentActive = null;
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          currentActive = entry.target.id;
        }
      });
      if (currentActive) activateLink(currentActive);
    }, {
      rootMargin: '-30% 0px -60% 0px', // triggers when heading is in top 30% of viewport
      threshold: 0
    });
    headings.forEach(h => observer.observe(h));
  } else {
    // Fallback: scroll event
    window.addEventListener('scroll', () => {
      let lastId = null;
      for (const h of headings) {
        if (window.scrollY + 80 >= h.offsetTop) {
          lastId = h.id;
        }
      }
      if (lastId) activateLink(lastId);
    });
  }
});
