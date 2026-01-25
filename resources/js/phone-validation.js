// Reusable phone validation initialization function
window.initPhoneValidation = function (inputId, countryCodeInputId, options = {}) {
    const phoneInput = document.querySelector(`#${inputId}`);
    if (!phoneInput) return null;

    const defaultOptions = {
        initialCountry: options.initialCountry || "sa",
        separateDialCode: true,
        countrySearch: false,
        geoIpLookup: function (callback) {
            fetch("https://ipapi.co/json")
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback("sa"));
        },
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    };

    const iti = window.intlTelInput(phoneInput, defaultOptions);

    // Country-specific phone lengths (without country code)
    const phoneLengths = {
        'sa': 9,   // Saudi Arabia
        'eg': 11,  // Egypt
        'us': 10,  // USA
        'ae': 9,   // UAE
        'kw': 8,   // Kuwait
        'bh': 8,   // Bahrain
        'qa': 8,   // Qatar
        'om': 8,   // Oman
        'jo': 9,   // Jordan
        'lb': 8,   // Lebanon
        'sy': 9,   // Syria
        'iq': 10,  // Iraq
        'ye': 9,   // Yemen
        'ps': 9,   // Palestine
        'gb': 10,  // UK
        'de': 11,  // Germany
        'fr': 9,   // France
        'it': 10,  // Italy
        'es': 9,   // Spain
    };

    const updateMaxLength = function () {
        const countryCode = iti.getSelectedCountryData().iso2;
        const maxLength = phoneLengths[countryCode] || 15;
        phoneInput.setAttribute('maxlength', maxLength);
        phoneInput.setAttribute('minlength', maxLength); // Same as maxlength for exact length
    };

    // Set initial maxlength
    updateMaxLength();

    let lastToastTime = 0;
    const toastThrottle = 3000; // 3 seconds

    const validate = function (showError = false) {
        const rawValue = phoneInput.value.trim();
        const valueOnlyDigits = rawValue.replace(/[^0-9]/g, '');
        const minLength = parseInt(phoneInput.getAttribute('minlength'));
        const isValid = iti.isValidNumber();

        if (!rawValue) {
            phoneInput.setCustomValidity('');
            phoneInput.classList.remove('border-red-500');
            return true;
        }

        // Check if library says it's valid (trust the library primarily)
        if (isValid) {
            phoneInput.setCustomValidity('');
            phoneInput.classList.remove('border-red-500');
            return true;
        }

        // Fallback: check manual length if library isn't sure
        let nationalValue = valueOnlyDigits;
        const countryData = iti.getSelectedCountryData();
        if (countryData.dialCode && nationalValue.startsWith(countryData.dialCode)) {
            nationalValue = nationalValue.substring(countryData.dialCode.length);
        }

        if (nationalValue.length < minLength) {
            if (showError) {
                phoneInput.setCustomValidity(`رقم الهاتف يجب أن يكون ${minLength} أرقام بالضبط`);
                phoneInput.classList.add('border-red-500');

                const now = Date.now();
                if (window.showToast && now - lastToastTime > toastThrottle) {
                    window.showToast(`رقم الهاتف يجب أن يكون ${minLength} أرقام بالضبط`, "error");
                    lastToastTime = now;
                }
            }
            return false;
        } else {
            phoneInput.setCustomValidity('');
            phoneInput.classList.remove('border-red-500');
            return true;
        }
    };

    // Prevent typing beyond maxlength and filter non-numeric
    phoneInput.addEventListener('input', function (e) {
        let value = phoneInput.value.replace(/[^0-9]/g, '');
        phoneInput.value = value;

        const maxLength = parseInt(phoneInput.getAttribute('maxlength'));
        // Allow a bit more length in case they type the dial code
        if (phoneInput.value.length > maxLength + 5) {
            phoneInput.value = phoneInput.value.slice(0, maxLength + 5);
        }

        // Don't validate/block while typing to avoid annoying tooltips
        phoneInput.setCustomValidity('');
        phoneInput.classList.remove('border-red-500');
    });

    // Update country code and maxlength on country change
    phoneInput.addEventListener("countrychange", function () {
        const countryData = iti.getSelectedCountryData();
        const countryCodeInput = document.querySelector(`#${countryCodeInputId}`);
        if (countryCodeInput) {
            countryCodeInput.value = "+" + countryData.dialCode;
        }
        updateMaxLength();
        validate(false); // Silent validate
    });

    // Validate on blur
    phoneInput.addEventListener('blur', function () {
        if (!validate(true) && phoneInput.value.trim().length > 0) {
            phoneInput.reportValidity();
        }
    });

    // Clear validation message on focus to allow typing
    phoneInput.addEventListener('focus', function () {
        phoneInput.setCustomValidity('');
        phoneInput.classList.remove('border-red-500');
    });

    // Set initial country code
    const initialCountryData = iti.getSelectedCountryData();
    const countryCodeInput = document.querySelector(`#${countryCodeInputId}`);
    if (countryCodeInput) {
        countryCodeInput.value = "+" + initialCountryData.dialCode;
    }

    // Intercept form submission
    const form = phoneInput.closest('form');
    if (form) {
        const handleSubmit = function (e) {
            if (!validate(true)) {
                e.preventDefault();
                e.stopImmediatePropagation();
                phoneInput.reportValidity();
                return false;
            }
        };

        form.addEventListener('submit', handleSubmit, true);

        const submitBtns = form.querySelectorAll('button[type="submit"], button.submit-btn');
        submitBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                if (!validate(true)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    phoneInput.reportValidity();
                }
            }, true);
        });
    }

    return iti;
};
