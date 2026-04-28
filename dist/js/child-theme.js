/******/ (() => { // webpackBootstrap
/*!************************************!*\
  !*** ./src/scripts/child-theme.js ***!
  \************************************/
/**
 * Starter JS file for child theme.
 * wp.domReady is a useful JS hook.
*/

wp.domReady(() => {
  /**
   * Register styles associated with acf/alert
   */
  wp.blocks.registerBlockStyle('acf/project-sponsor', [{
    name: 'vertical',
    label: 'Vertical',
    isDefault: true
  }]);
  wp.blocks.registerBlockStyle('acf/project-sponsor', [{
    name: 'horizontal',
    label: 'Horizontal',
    isDefault: false
  }]);
});

/**
 * Front-end isotope initialization for Capstone Isotope block.
 * Includes retry logic in case isotope isn't immediately available.
 */
function initIsotopeBlocks() {
  if (typeof Isotope === 'undefined') {
    // Isotope not yet loaded, retry in 100ms
    setTimeout(initIsotopeBlocks, 100);
    return;
  }
  const isotopeContainers = document.querySelectorAll('.capstone-isotope .isotope-grid');
  isotopeContainers.forEach(container => {
    // Skip if already initialized
    if (container.dataset.isotopeInitialized) {
      return;
    }
    const iso = new Isotope(container, {
      itemSelector: '.project-card',
      layoutMode: 'fitRows'
    });
    container.dataset.isotopeInitialized = 'true';

    // Filter selects
    const filterSelects = container.parentNode.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
      select.addEventListener('change', function () {
        const filterValue = this.value;
        iso.arrange({
          filter: filterValue
        });
      });
    });

    // Search input
    const searchInput = container.parentNode.querySelector('.filter-search');
    if (searchInput) {
      let searchTimeout;
      searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
          const searchValue = this.value.toLowerCase();
          iso.arrange({
            filter: function (itemElem) {
              const title = itemElem.getAttribute('data-title').toLowerCase();
              const team = itemElem.getAttribute('data-team').toLowerCase();
              return title.includes(searchValue) || team.includes(searchValue);
            }
          });
        }, 300);
      });
    }
  });
}

// Initialize on DOMContentLoaded
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initIsotopeBlocks);
} else {
  // DOM already loaded
  initIsotopeBlocks();
}
/******/ })()
;
//# sourceMappingURL=child-theme.js.map