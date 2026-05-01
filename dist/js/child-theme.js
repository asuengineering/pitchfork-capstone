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
    setTimeout(initIsotopeBlocks, 100);
    return;
  }
  const isotopeContainers = document.querySelectorAll('.capstone-isotope .isotope-results');
  isotopeContainers.forEach(container => {
    if (container.dataset.isotopeInitialized) return;
    const iso = new Isotope(container, {
      itemSelector: '.project-card',
      layoutMode: 'fitRows'
    });
    container.dataset.isotopeInitialized = 'true';
    const block = container.closest('.capstone-isotope');

    // MULTI FILTER SUPPORT
    const filters = {};
    const filterSelects = block.querySelectorAll('.filter.form-select');
    filterSelects.forEach(select => {
      select.addEventListener('change', function () {
        const group = this.dataset.filterGroup;
        filters[group] = this.value;
        const filterValue = Object.values(filters).join('');
        iso.arrange({
          filter: filterValue
        });
      });
    });

    // SEARCH
    const searchInput = block.querySelector('#filter-search');
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
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initIsotopeBlocks);
} else {
  initIsotopeBlocks();
}
/******/ })()
;
//# sourceMappingURL=child-theme.js.map