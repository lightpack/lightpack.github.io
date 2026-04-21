    </div>
    <script src="<?= $assetPath ?? '../assets' ?>/toc.js"></script>
    <script>
      window.Prism = window.Prism || {};
      Prism.manual = true;
    </script>
    <script src="<?= $assetPath ?? '../assets' ?>/prism.min.js"></script>
    <script src="<?= $assetPath ?? '../assets' ?>/prism-markup-templating.min.js"></script>
    <script src="<?= $assetPath ?? '../assets' ?>/prism-php.min.js"></script>
    <script>
      function init() {
        // Collapsible sidebar sections
        document.querySelectorAll('.sidebar-section-title').forEach(title => {
          title.addEventListener('click', () => {
            const content = title.nextElementSibling;
            const icon = title.querySelector('.collapse-icon');
            const isOpen = content.style.display !== 'none';
            content.style.display = isOpen ? 'none' : 'block';
            icon.textContent = isOpen ? '▶' : '▼';
          });

          // Auto-expand section with active page
          const content = title.nextElementSibling;
          if (content.querySelector('.active, [aria-current="page"]')) {
            content.style.display = 'block';
            title.querySelector('.collapse-icon').textContent = '▼';
          }
        });

        // Add copy icon to code blocks
        document.querySelectorAll('pre').forEach(pre => {
          const btn = document.createElement('button');
          btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>';
          btn.style.cssText = 'position:absolute;top:8px;right:8px;padding:6px;background:rgba(255,255,255,0.1);border:none;border-radius:4px;cursor:pointer;transition:opacity 0.2s;display:flex;align-items:center;justify-content:center;color:rgb(255,255,255);opacity:0';
          btn.onclick = () => {
            navigator.clipboard.writeText(pre.textContent);
            btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
            setTimeout(() => btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>', 2000);
          };
          pre.style.position = 'relative';
          pre.onmouseenter = () => btn.style.opacity = '1';
          pre.onmouseleave = () => btn.style.opacity = '0';
          pre.appendChild(btn);
        });

        Prism.highlightAll();
      }
    </script>

    <script src="https://unpkg.com/swup@4"></script>
    <script>
      const swup = new Swup();

      // Run once when page loads
      if (document.readyState === 'complete') {
        init();
      } else {
        document.addEventListener('DOMContentLoaded', () => init());
      }

      swup.hooks.on('page:view', () => {
        init()
      });
    </script>

    </body>

    </html>