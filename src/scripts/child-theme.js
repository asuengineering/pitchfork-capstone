/**
 * Starter JS file for child theme.
 * wp.domReady is a useful JS hook.
*/

wp.domReady(() => {

	wp.blocks.registerBlockStyle('acf/project-sponsor', [{
		name: 'vertical',
		label: 'Vertical',
		isDefault: true,
	}]);

	wp.blocks.registerBlockStyle('acf/project-sponsor', [{
		name: 'horizontal',
		label: 'Horizontal',
		isDefault: false,
	}]);

});


// -------------------------
// Isotope init and filter handling
// -------------------------

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
			layoutMode: 'fitRows',
		});

		container.dataset.isotopeInitialized = 'true';

		const block = container.closest('.capstone-isotope');

		const filterSelects = block.querySelectorAll('.filter.form-select');
		const searchInput = block.querySelector('#filter-search');

		const countVisible = block.querySelector('.count-visible');
		const countTotal = block.querySelector('.count-total');

		const controlSearch = block.querySelector('.control-search');
		const controlFilters = block.querySelector('.control-filters');

		const clearSearchBtn = block.querySelector('.clear-search');
		const resetFiltersBtn = block.querySelector('.reset-filters');
		const toggleBtn = block.querySelector('.toggle-filters');

		let currentFilter = '';
		let currentSearch = '';

		// -------------------------
		// COUNT FUNCTION
		// -------------------------
		function updateCount() {
			const allItems = container.querySelectorAll('.project-card');

			let visibleCount = 0;

			allItems.forEach(item => {
				if (item.offsetParent !== null) {
					visibleCount++;
				}
			});

			if (countVisible) countVisible.textContent = visibleCount;
			if (countTotal) countTotal.textContent = allItems.length;
		}

		// hook count updates to isotope
		iso.on('arrangeComplete', updateCount);

		// initial count
		updateCount();

		// -------------------------
		// TOGGLE BUTTON (Search <-> Filters)
		// -------------------------
		if (toggleBtn) {

			let showingFilters = false;

			toggleBtn.addEventListener('click', function () {

				showingFilters = !showingFilters;

				if (showingFilters) {
					controlSearch.classList.add('d-none');
					controlFilters.classList.remove('d-none');

					if (searchInput) {
						searchInput.value = '';
						currentSearch = '';
					}

					toggleBtn.textContent = 'Show search';

				} else {
					controlSearch.classList.remove('d-none');
					controlFilters.classList.add('d-none');

					filterSelects.forEach(s => {
						s.value = '';
						s.disabled = false;
					});

					currentFilter = '';
					toggleBtn.textContent = 'Show filters';
				}

				iso.arrange({ filter: '*' });
			});
		}

		// -------------------------
		// FILTER HANDLING
		// -------------------------
		filterSelects.forEach(select => {
			select.addEventListener('change', function () {

				const selectedValue = this.value;
				const isActive = selectedValue !== '';

				filterSelects.forEach(s => {
					if (s !== this) {
						s.value = '';
						s.disabled = isActive;
					} else {
						s.disabled = false;
					}
				});

				currentFilter = selectedValue;

				iso.arrange({
					filter: selectedValue || '*'
				});
			});
		});

		// -------------------------
		// SEARCH
		// -------------------------
		if (searchInput) {
			let searchTimeout;

			searchInput.addEventListener('input', function () {
				clearTimeout(searchTimeout);

				searchTimeout = setTimeout(() => {

					currentSearch = this.value.toLowerCase().trim();

					if (!currentSearch) {
						iso.arrange({ filter: '*' });
						return;
					}

					iso.arrange({
						filter: function () {
							const text = (this.innerText || '').toLowerCase();
							return text.indexOf(currentSearch) > -1;
						}
					});

				}, 200);
			});
		}

		// -------------------------
		// CLEAR SEARCH
		// -------------------------
		if (clearSearchBtn && searchInput) {
			clearSearchBtn.addEventListener('click', function () {
				searchInput.value = '';
				currentSearch = '';

				iso.arrange({ filter: '*' });
			});
		}

		// -------------------------
		// RESET FILTERS
		// -------------------------
		if (resetFiltersBtn) {
			resetFiltersBtn.addEventListener('click', function () {
				filterSelects.forEach(s => {
					s.value = '';
					s.disabled = false;
				});

				currentFilter = '';

				iso.arrange({ filter: '*' });
			});
		}
	});
}


// -------------------------
// INIT
// -------------------------
window.addEventListener('load', initIsotopeBlocks);
