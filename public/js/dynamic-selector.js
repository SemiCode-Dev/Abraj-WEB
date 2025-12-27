/**
 * DynamicSelector - A reusable class for handling dynamic dependent dropdowns
 * 
 * Features:
 * - AbortController to cancel stale requests
 * - Retry logic (1 retry) on failure
 * - LocalStorage caching
 * - Better error states
 */
class DynamicSelector {
    constructor(options) {
        this.countrySelect = document.querySelector(options.countrySelector);
        this.citySelect = document.querySelector(options.citySelector);
        this.apiUrl = options.apiUrl;
        this.placeholder = options.placeholder || 'Select City';
        this.loadingText = options.loadingText || 'Loading...';
        this.errorText = options.errorText || 'Error loading cities';
        this.initialCityId = options.initialCityId || null;

        this.abortController = null; // To cancel processing requests

        if (!this.countrySelect || !this.citySelect) {
            console.warn('DynamicSelector: Elements not found', options);
            return;
        }

        this.init();
    }

    init() {
        // Disable city select initially if no country selected
        if (!this.countrySelect.value) {
            this.citySelect.disabled = true;
        } else {
            // If country is already selected (e.g. validation error), load cities
            this.loadCities(this.countrySelect.value, this.initialCityId);
        }

        // Listen for changes
        this.countrySelect.addEventListener('change', (e) => {
            const countryId = e.target.value;
            this.resetCitySelect();

            if (countryId) {
                this.loadCities(countryId);
            }
        });
    }

    resetCitySelect() {
        // Cancel any pending request
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
        // 1. Cancel previous request if exists
        if (this.abortController) {
            this.abortController.abort();
        }
        this.abortController = new AbortController();
        const signal = this.abortController.signal;

        this.setLoading(true);
        console.log(`DynamicSelector: Loading cities for country ${countryId} (Attempt ${retryCount + 1})`);

        try {
            // Replace {id} placeholder in URL
            const url = this.apiUrl.replace('{id}', countryId).replace('{country}', countryId);
            const cacheKey = `cities_cache_${url}`;

            // 2. Local Cache Check (Instant Return)
            const cachedData = localStorage.getItem(cacheKey);
            if (cachedData) {
                try {
                    const cachedCities = JSON.parse(cachedData);
                    console.log(`DynamicSelector: Cache hit for ${url}`);
                    this.populateCities(cachedCities, selectedCityId);
                    return;
                } catch (e) {
                    localStorage.removeItem(cacheKey);
                }
            }

            // 3. Network Request
            const response = await fetch(url, {
                signal: signal, // Attach signal
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const cities = await response.json();

            // 4. Cache and Populate
            if (cities.length > 0) {
                try {
                    localStorage.setItem(cacheKey, JSON.stringify(cities));
                } catch (e) {
                    console.warn('DynamicSelector: Failed to cache', e);
                }
            }

            this.populateCities(cities, selectedCityId);

        } catch (error) {
            if (error.name === 'AbortError') {
                console.log('DynamicSelector: Request aborted');
                return; // User changed country, ignore
            }

            console.error('DynamicSelector error:', error);

            // 5. Retry Logic (One retry)
            if (retryCount < 1) {
                console.log('DynamicSelector: Retrying...');
                setTimeout(() => {
                    this.loadCities(countryId, selectedCityId, retryCount + 1);
                }, 1000); // Wait 1s before retry
                return;
            }

            this.citySelect.innerHTML = `<option value="">${this.errorText}</option>`;
        } finally {
            this.abortController = null;
        }
    }

    populateCities(cities, selectedCityId) {
        this.citySelect.innerHTML = `<option value="">${this.placeholder}</option>`;

        if (cities.length === 0) {
            this.citySelect.innerHTML = `<option value="">${this.placeholder} (Empty)</option>`;
            this.citySelect.disabled = true;
        } else {
            // Create Localized List
            const fragment = document.createDocumentFragment();

            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;

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

// Make available globally
window.DynamicSelector = DynamicSelector;
