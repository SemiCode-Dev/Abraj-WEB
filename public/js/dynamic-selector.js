/**
 * DynamicSelector - A reusable class for handling dynamic dependent dropdowns
 * 
 * Features:
 * - AbortController to cancel stale requests
 * - Retry logic (1 retry) on failure
 * - LocalStorage caching with versioning
 * - Client-side deduplication
 */
class DynamicSelector {
    static CACHE_VERSION = 'v10'; // Bumping to match server

    constructor(options) {
        this.countrySelect = document.querySelector(options.countrySelector);
        this.citySelect = document.querySelector(options.citySelector);
        this.apiUrl = options.apiUrl;
        this.placeholder = options.placeholder || 'Select City';
        this.loadingText = options.loadingText || 'Loading...';
        this.errorText = options.errorText || 'Error loading cities';
        this.initialCityId = options.initialCityId || null;

        // Mutual Exclusion Settings
        this.excludeSelector = options.excludeSelector ? document.querySelector(options.excludeSelector) : null;
        this.companionCountrySelector = options.companionCountrySelector ? document.querySelector(options.companionCountrySelector) : null;

        this.abortController = null;

        if (!this.countrySelect || !this.citySelect) {
            console.warn('DynamicSelector: Elements not found', options);
            return;
        }

        // Auto-clear old caches on version change
        const currentVersion = localStorage.getItem('cities_cache_version');
        if (currentVersion !== DynamicSelector.CACHE_VERSION) {
            console.log(`DynamicSelector: Bumping cache from ${currentVersion} to ${DynamicSelector.CACHE_VERSION}`);
            this.clearOldCaches();
            localStorage.setItem('cities_cache_version', DynamicSelector.CACHE_VERSION);
        }

        this.init();
    }

    clearOldCaches() {
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key && key.startsWith('cities_cache_')) {
                localStorage.removeItem(key);
                i--; // Adjust index after removal
            }
        }
    }

    init() {
        if (!this.countrySelect.value) {
            this.citySelect.disabled = true;
        } else {
            this.loadCities(this.countrySelect.value, this.initialCityId);
        }

        this.countrySelect.addEventListener('change', (e) => {
            const countryId = e.target.value;
            this.resetCitySelect();

            if (countryId) {
                this.loadCities(countryId);
            }
        });

        // Re-evaluate exclusion when companion city or country changes
        if (this.excludeSelector) {
            this.excludeSelector.addEventListener('change', () => this.refreshExclusion());
        }
        if (this.companionCountrySelector) {
            this.companionCountrySelector.addEventListener('change', () => this.refreshExclusion());
        }
    }

    refreshExclusion() {
        const selectedCity = this.excludeSelector ? this.excludeSelector.value : null;
        const currentCountry = this.countrySelect.value;
        const companionCountry = this.companionCountrySelector ? this.companionCountrySelector.value : null;

        Array.from(this.citySelect.options).forEach(option => {
            // Only disable if BOTH city and country match
            if (selectedCity && String(option.value) === String(selectedCity) && currentCountry === companionCountry) {
                option.disabled = true;
                if (this.citySelect.value === option.value) {
                    this.citySelect.value = ""; // Deselect if it was selected
                }
            } else if (option.value !== "") {
                option.disabled = false;
            }
        });
    }

    resetCitySelect() {
        if (this.abortController) {
            this.abortController.abort();
            this.abortController = null;
        }

        this.citySelect.innerHTML = `<option value="">${this.placeholder}</option>`;
        this.citySelect.disabled = true;
    }

    setLoading(isLoading) {
        this.citySelect.disabled = isLoading;
        if (isLoading) {
            this.citySelect.innerHTML = `<option value="">${this.loadingText}</option>`;
        }
    }

    async loadCities(countryId, selectedCityId = null, retryCount = 0) {
        if (this.abortController) {
            this.abortController.abort();
        }
        this.abortController = new AbortController();
        const signal = this.abortController.signal;

        this.setLoading(true);

        try {
            // Append version for server-side cache busting + locale check
            const separator = this.apiUrl.includes('?') ? '&' : '?';
            const url = this.apiUrl.replace('{id}', countryId).replace('{country}', countryId)
                + `${separator}v=${DynamicSelector.CACHE_VERSION}`;

            const cacheKey = `cities_cache_${url}`;

            // Check Cache
            const cachedData = localStorage.getItem(cacheKey);
            if (cachedData) {
                try {
                    const cachedCities = JSON.parse(cachedData);
                    this.populateCities(cachedCities, selectedCityId);
                    return;
                } catch (e) {
                    localStorage.removeItem(cacheKey);
                }
            }

            // Fetch
            const response = await fetch(url, {
                signal: signal,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const cities = await response.json();

            // Cache and Populate
            if (cities.length > 0) {
                try {
                    localStorage.setItem(cacheKey, JSON.stringify(cities));
                } catch (e) {
                    console.warn('DynamicSelector: Failed to cache', e);
                }
            }

            this.populateCities(cities, selectedCityId);

        } catch (error) {
            if (error.name === 'AbortError') return;

            console.error('DynamicSelector error:', error);

            if (retryCount < 1) {
                setTimeout(() => {
                    this.loadCities(countryId, selectedCityId, retryCount + 1);
                }, 1000);
                return;
            }

            this.citySelect.innerHTML = `<option value="">${this.errorText}</option>`;
        } finally {
            this.abortController = null;
        }
    }

    populateCities(cities, selectedCityId) {
        this.citySelect.innerHTML = `<option value="">${this.placeholder}</option>`;

        if (!cities || cities.length === 0) {
            this.citySelect.innerHTML = `<option value="">${this.placeholder} (Empty)</option>`;
            this.citySelect.disabled = true;
        } else {
            const fragment = document.createDocumentFragment();

            // Client-side Deduplication (Safety Net)
            const seenNames = new Set();

            cities.forEach(city => {
                if (!city.name || seenNames.has(city.name)) return;
                seenNames.add(city.name);

                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;

                // Apply initial exclusion state
                const selectedCity = this.excludeSelector ? this.excludeSelector.value : null;
                const currentCountry = this.countrySelect.value;
                const companionCountry = this.companionCountrySelector ? this.companionCountrySelector.value : null;

                if (selectedCity && String(city.id) === String(selectedCity) && currentCountry === companionCountry) {
                    option.disabled = true;
                }

                if (selectedCityId && String(city.id) === String(selectedCityId)) {
                    option.selected = true;
                }
                fragment.appendChild(option);
            });

            this.citySelect.appendChild(fragment);
            this.citySelect.disabled = false;
        }
    }
}

window.DynamicSelector = DynamicSelector;
