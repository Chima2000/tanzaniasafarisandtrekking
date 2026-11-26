(function () {
    'use strict';

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function initHeaderSearch(root) {
        var toggle = root.querySelector('[data-header-search-toggle]');
        var panel = root.querySelector('[data-header-search-panel]');
        var input = root.querySelector('[data-header-search-input]');
        var resultsWrap = root.querySelector('[data-header-search-results]');
        var statusEl = root.querySelector('[data-header-search-status]');
        var listEl = root.querySelector('[data-header-search-list]');
        var viewAll = root.querySelector('[data-header-search-view-all]');
        var suggestUrl = root.getAttribute('data-suggest-url');
        var indexUrl = root.getAttribute('data-index-url');
        var debounceTimer = null;
        var activeController = null;

        if (!toggle || !panel || !input || !suggestUrl) {
            return;
        }

        function setOpen(isOpen) {
            root.classList.toggle('is-open', isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            toggle.setAttribute('aria-label', isOpen ? 'Close site search' : 'Open site search');

            if (isOpen) {
                panel.hidden = false;
                window.setTimeout(function () {
                    input.focus();
                }, 10);
            } else {
                panel.hidden = true;
                clearResults();
            }
        }

        function clearResults() {
            if (resultsWrap) {
                resultsWrap.hidden = true;
            }
            if (statusEl) {
                statusEl.textContent = '';
            }
            if (listEl) {
                listEl.innerHTML = '';
            }
            if (viewAll) {
                viewAll.hidden = true;
                viewAll.setAttribute('href', indexUrl);
            }
        }

        function renderResults(payload) {
            if (!resultsWrap || !listEl || !statusEl || !viewAll) {
                return;
            }

            var query = payload.query || '';
            var results = Array.isArray(payload.results) ? payload.results : [];

            resultsWrap.hidden = false;
            listEl.innerHTML = '';

            if (query.length < 2) {
                statusEl.textContent = 'Type at least 2 characters to search.';
                viewAll.hidden = true;
                return;
            }

            if (results.length === 0) {
                statusEl.textContent = 'No matches for “' + query + '”.';
                viewAll.hidden = false;
                viewAll.setAttribute('href', payload.view_all_url || (indexUrl + '?q=' + encodeURIComponent(query)));
                viewAll.textContent = 'View all search results';
                return;
            }

            statusEl.textContent = '';
            results.forEach(function (item) {
                var imageHtml = item.image
                    ? '<img src="' + escapeHtml(item.image) + '" alt="">'
                    : '<span class="header-site-search__thumb-fallback" aria-hidden="true"><i class="flaticon-zoom"></i></span>';

                var description = item.description
                    ? '<p>' + escapeHtml(item.description) + '</p>'
                    : '';

                var link = document.createElement('a');
                link.className = 'header-site-search__item';
                link.href = item.url;
                link.innerHTML =
                    '<div class="header-site-search__thumb">' + imageHtml + '</div>' +
                    '<div class="header-site-search__body">' +
                        '<span class="header-site-search__type">' + escapeHtml(item.type_label || '') + '</span>' +
                        '<strong>' + escapeHtml(item.title || '') + '</strong>' +
                        description +
                    '</div>';
                listEl.appendChild(link);
            });

            viewAll.hidden = false;
            viewAll.setAttribute('href', payload.view_all_url || (indexUrl + '?q=' + encodeURIComponent(query)));
            viewAll.textContent = 'View all search results';
        }

        function fetchSuggestions(query) {
            if (activeController) {
                activeController.abort();
            }

            if (query.length < 2) {
                renderResults({ query: query, results: [], view_all_url: indexUrl + '?q=' + encodeURIComponent(query) });
                return;
            }

            if (statusEl && resultsWrap) {
                resultsWrap.hidden = false;
                statusEl.textContent = 'Searching…';
                if (listEl) {
                    listEl.innerHTML = '';
                }
                if (viewAll) {
                    viewAll.hidden = true;
                }
            }

            activeController = new AbortController();

            fetch(suggestUrl + '?q=' + encodeURIComponent(query), {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: activeController.signal,
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Search request failed');
                    }
                    return response.json();
                })
                .then(function (payload) {
                    renderResults(payload);
                })
                .catch(function (error) {
                    if (error && error.name === 'AbortError') {
                        return;
                    }
                    if (statusEl && resultsWrap) {
                        resultsWrap.hidden = false;
                        statusEl.textContent = 'Unable to load suggestions. Try View all search.';
                        if (viewAll) {
                            viewAll.hidden = false;
                            viewAll.setAttribute('href', indexUrl + '?q=' + encodeURIComponent(query));
                        }
                    }
                });
        }

        toggle.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            setOpen(!root.classList.contains('is-open'));
        });

        input.addEventListener('input', function () {
            var query = input.value.trim();
            window.clearTimeout(debounceTimer);
            debounceTimer = window.setTimeout(function () {
                fetchSuggestions(query);
            }, 220);
        });

        document.addEventListener('click', function (event) {
            if (!root.contains(event.target)) {
                setOpen(false);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setOpen(false);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-header-search]').forEach(initHeaderSearch);
    });
})();
