/* VietTalent — public job listing filters
 * Implements:
 *   #3 (sorting + pagination via AJAX),
 *   #4 (search results without page reload),
 *   #5 (cascading category/location selects).
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var sortSelect    = document.querySelector('[data-sort-select]');
        var filterForm    = document.getElementById('filterForm');
        var jobsListEl    = document.querySelector('[data-jobs-list]');
        var jobsCountEl   = document.querySelector('[data-jobs-count]');
        var paginationEl  = document.querySelector('[data-pagination]');
        var searchForm    = document.querySelector('[data-jobs-search-form]');
        var statusEl      = document.querySelector('[data-ajax-status]');
        var endpoint      = window.JOBS_SEARCH_ENDPOINT || '';
        var resultsLabel  = window.JOBS_RESULTS_LABEL || 'result(s)';
        var noResultsHtml = window.JOBS_NO_RESULTS_HTML || '<div class="card jobs-empty"><h2>No jobs found</h2></div>';

        /* ------------ Cascading country / city / district ------------ */
        var countrySelect  = document.querySelector('[data-filter-country]');
        var citySelect     = document.querySelector('[data-filter-city]');
        var districtSelect = document.querySelector('[data-filter-district]');

        function filterOptions(select, attr, value) {
            if (!select) return;
            var opts = select.querySelectorAll('option[' + attr + ']');
            for (var i = 0; i < opts.length; i++) {
                opts[i].style.display = !value || opts[i].getAttribute(attr) === value ? '' : 'none';
            }
        }

        if (countrySelect) {
            countrySelect.addEventListener('change', function () {
                filterOptions(citySelect, 'data-country', this.value);
                filterOptions(districtSelect, 'data-city', '');
                if (citySelect)     citySelect.value = '';
                if (districtSelect) districtSelect.value = '';
                triggerAjax(1);
            });
        }
        if (citySelect) {
            citySelect.addEventListener('change', function () {
                filterOptions(districtSelect, 'data-city', this.value);
                if (districtSelect) districtSelect.value = '';
                triggerAjax(1);
            });
        }
        if (districtSelect) {
            districtSelect.addEventListener('change', function () { triggerAjax(1); });
        }

        // Initialise cascading lists with current values.
        if (countrySelect && countrySelect.value) filterOptions(citySelect, 'data-country', countrySelect.value);
        if (citySelect    && citySelect.value)    filterOptions(districtSelect, 'data-city', citySelect.value);

        /* ------------ Sort dropdown ------------ */
        if (sortSelect) {
            sortSelect.addEventListener('change', function () { triggerAjax(1); });
        }

        /* ------------ Sidebar selects (categories, level, salary, etc.) ------------ */
        if (filterForm) {
            filterForm.addEventListener('submit', function (event) {
                event.preventDefault();
                triggerAjax(1);
            });
            // Auto-fire on every <select> change inside the sidebar.
            var selects = filterForm.querySelectorAll('select');
            for (var i = 0; i < selects.length; i++) {
                selects[i].addEventListener('change', function () { triggerAjax(1); });
            }
        }

        /* ------------ Top search bar (debounced AJAX) ------------ */
        var keywordInput;
        if (searchForm) {
            keywordInput = searchForm.querySelector('input[name="keyword"]');
            searchForm.addEventListener('submit', function (event) {
                event.preventDefault();
                triggerAjax(1);
            });
            if (keywordInput) {
                var t = null;
                keywordInput.addEventListener('input', function () {
                    clearTimeout(t);
                    t = setTimeout(function () { triggerAjax(1); }, 350);
                });
            }
        }

        /* ------------ Pagination (delegated click) ------------ */
        document.addEventListener('click', function (event) {
            var link = event.target.closest && event.target.closest('[data-pagination] [data-page]');
            if (!link) return;
            if (link.classList.contains('is-disabled') || link.classList.contains('is-current')) {
                event.preventDefault();
                return;
            }
            event.preventDefault();
            triggerAjax(parseInt(link.getAttribute('data-page'), 10) || 1);
        });

        /* ------------ Mobile filter toggle ------------ */
        var sidebar = document.getElementById('filterSidebar');
        if (sidebar && window.innerWidth <= 860) {
            var toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'filter-toggle-btn';
            toggleBtn.textContent = 'Show Filters';
            sidebar.parentNode.insertBefore(toggleBtn, sidebar);
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('sidebar-open');
                toggleBtn.textContent = sidebar.classList.contains('sidebar-open') ? 'Hide Filters' : 'Show Filters';
            });
        }

        /* ------------ AJAX core ------------ */

        function buildQuery(page) {
            var params = new URLSearchParams();
            params.set('page', 'jobs_search');
            if (keywordInput && keywordInput.value)             params.set('keyword', keywordInput.value);
            if (countrySelect && countrySelect.value)           params.set('country_id', countrySelect.value);
            if (citySelect && citySelect.value)                 params.set('city_id', citySelect.value);
            if (districtSelect && districtSelect.value)         params.set('district_id', districtSelect.value);
            if (filterForm) {
                var values = filterForm.querySelectorAll('select');
                for (var i = 0; i < values.length; i++) {
                    if (values[i].value) params.set(values[i].name, values[i].value);
                }
            }
            if (sortSelect && sortSelect.value)                 params.set('sort', sortSelect.value);
            if (page)                                           params.set('p', String(page));
            return params.toString();
        }

        function setStatus(msg) {
            if (statusEl) statusEl.textContent = msg || '';
        }

        function escapeHtml(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function renderItems(items) {
            if (!items.length) return noResultsHtml;
            var out = '<div class="jobs-list">';
            for (var i = 0; i < items.length; i++) {
                var j = items[i];
                var location = [j.district_name, j.city_name, j.country_name].filter(Boolean).join(', ');
                var skills = '';
                if (j.skills && j.skills.length) {
                    skills = '<ul class="job-card-skills">';
                    for (var s = 0; s < j.skills.length; s++) {
                        skills += '<li>' + escapeHtml(j.skills[s]) + '</li>';
                    }
                    skills += '</ul>';
                }
                out += ''
                    + '<article class="job-card card">'
                    + '  <header class="job-card-header">'
                    + '    <div>'
                    + '      <h3 class="job-card-title"><a href="' + escapeHtml(j.detail_url) + '">' + escapeHtml(j.job_title_name) + '</a></h3>'
                    + '      <p class="job-card-company">' + escapeHtml(j.company_name) + '</p>'
                    + '    </div>'
                    + '    <span class="job-card-salary">' + escapeHtml(j.salary_label || '') + '</span>'
                    + '  </header>'
                    + '  <ul class="job-card-meta">'
                    + '    <li>' + escapeHtml(j.category_name || '') + '</li>'
                    + '    <li>' + escapeHtml(location) + '</li>'
                    + '    <li>' + escapeHtml(j.employment_type || '') + '</li>'
                    + '    <li>' + escapeHtml(j.work_arrangement || '') + '</li>'
                    + '  </ul>'
                    + skills
                    + '  <footer class="job-card-footer">'
                    + '    <a class="btn btn-outline btn-sm" href="' + escapeHtml(j.detail_url) + '">View Detail</a>'
                    + '  </footer>'
                    + '</article>';
            }
            out += '</div>';
            return out;
        }

        function renderPagination(page, totalPages) {
            if (!paginationEl) return;
            if (totalPages <= 1) { paginationEl.innerHTML = ''; return; }
            var html = '';
            var prev = Math.max(1, page - 1);
            var next = Math.min(totalPages, page + 1);
            html += '<a class="page-link ' + (page <= 1 ? 'is-disabled' : '') + '" href="#" data-page="' + prev + '">&laquo; Prev</a>';

            var window_ = 2;
            var start = Math.max(1, page - window_);
            var end   = Math.min(totalPages, page + window_);
            if (start > 1) {
                html += '<a class="page-link" href="#" data-page="1">1</a>';
                if (start > 2) html += '<span class="page-ellipsis">…</span>';
            }
            for (var p = start; p <= end; p++) {
                html += '<a class="page-link ' + (p === page ? 'is-current' : '') + '" href="#" data-page="' + p + '">' + p + '</a>';
            }
            if (end < totalPages) {
                if (end < totalPages - 1) html += '<span class="page-ellipsis">…</span>';
                html += '<a class="page-link" href="#" data-page="' + totalPages + '">' + totalPages + '</a>';
            }
            html += '<a class="page-link ' + (page >= totalPages ? 'is-disabled' : '') + '" href="#" data-page="' + next + '">Next &raquo;</a>';
            paginationEl.innerHTML = html;
        }

        function pushHistory(query) {
            if (!window.history || !window.history.replaceState) return;
            // Convert "page=jobs_search" back to "page=jobs" for the browser URL.
            var pretty = query.replace(/^page=jobs_search/, 'page=jobs');
            window.history.replaceState({}, '', '?' + pretty);
        }

        function triggerAjax(page) {
            if (!endpoint || !jobsListEl) return;
            var query = buildQuery(page);
            setStatus('Loading…');
            fetch(endpoint.split('?')[0] + '?' + query, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            }).then(function (r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            }).then(function (data) {
                jobsListEl.innerHTML = renderItems(data.items || []);
                if (jobsCountEl) jobsCountEl.textContent = (data.total || 0) + ' ' + resultsLabel;
                renderPagination(data.page || 1, data.totalPages || 1);
                pushHistory(query);
                setStatus('');
                window.scrollTo({ top: jobsListEl.getBoundingClientRect().top + window.pageYOffset - 80, behavior: 'smooth' });
            }).catch(function (err) {
                setStatus('Search failed: ' + err.message);
            });
        }
    });
})();
