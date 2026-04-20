    </div>
    <script src="<?= $assetPath ?? '../assets' ?>/toc.js"></script>
    <script>window.Prism = window.Prism || {}; Prism.manual = true;</script>
    <script src="<?= $assetPath ?? '../assets' ?>/prism.min.js"></script>
    <script src="<?= $assetPath ?? '../assets' ?>/prism-markup-templating.min.js"></script>
    <script src="<?= $assetPath ?? '../assets' ?>/prism-php.min.js"></script>
    <script>Prism.highlightAll();</script>
    <script>
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
    </script>
</body>
</html>
