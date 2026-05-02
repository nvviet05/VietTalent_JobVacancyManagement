document.addEventListener('DOMContentLoaded', function () {
    var sortSelect = document.querySelector('[data-sort-select]');
    var filterSort = document.getElementById('filterSort');
    var filterForm = document.getElementById('filterForm');

    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            if (filterSort) {
                filterSort.value = this.value;
            }
            if (filterForm) {
                filterForm.submit();
            }
        });
    }

    var countrySelect = document.querySelector('[data-filter-country]');
    var citySelect = document.querySelector('[data-filter-city]');
    var districtSelect = document.querySelector('[data-filter-district]');

    if (countrySelect && citySelect) {
        countrySelect.addEventListener('change', function () {
            filterCities(this.value);
            filterDistricts('');
            if (citySelect) citySelect.value = '';
            if (districtSelect) districtSelect.value = '';
        });
    }

    if (citySelect && districtSelect) {
        citySelect.addEventListener('change', function () {
            filterDistricts(this.value);
            if (districtSelect) districtSelect.value = '';
        });
    }

    function filterCities(countryId) {
        if (!citySelect) return;
        var options = citySelect.querySelectorAll('option[data-country]');
        for (var i = 0; i < options.length; i++) {
            if (!countryId) {
                options[i].style.display = '';
            } else {
                options[i].style.display = options[i].getAttribute('data-country') === countryId ? '' : 'none';
            }
        }
    }

    function filterDistricts(cityId) {
        if (!districtSelect) return;
        var options = districtSelect.querySelectorAll('option[data-city]');
        for (var i = 0; i < options.length; i++) {
            if (!cityId) {
                options[i].style.display = '';
            } else {
                options[i].style.display = options[i].getAttribute('data-city') === cityId ? '' : 'none';
            }
        }
    }

    var selectedCountry = countrySelect ? countrySelect.value : '';
    var selectedCity = citySelect ? citySelect.value : '';
    if (selectedCountry) filterCities(selectedCountry);
    if (selectedCity) filterDistricts(selectedCity);

    var sidebar = document.getElementById('filterSidebar');
    if (sidebar && window.innerWidth <= 860) {
        var toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'filter-toggle-btn';
        toggleBtn.textContent = sidebar.classList.contains('sidebar-open') ? 'Hide Filters' : 'Show Filters';
        sidebar.parentNode.insertBefore(toggleBtn, sidebar);

        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('sidebar-open');
            toggleBtn.textContent = sidebar.classList.contains('sidebar-open') ? 'Hide Filters' : 'Show Filters';
        });
    }
});
