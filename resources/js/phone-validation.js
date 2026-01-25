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
    };

    // Set initial maxlength
    updateMaxLength();

    // Prevent typing beyond maxlength
    phoneInput.addEventListener('input', function (e) {
        // Remove any non-numeric characters
        let value = phoneInput.value.replace(/[^0-9]/g, '');
        phoneInput.value = value;

        const maxLength = parseInt(phoneInput.getAttribute('maxlength'));
        if (phoneInput.value.length > maxLength) {
            phoneInput.value = phoneInput.value.slice(0, maxLength);
        }
    });

    // Update country code and maxlength on country change
    phoneInput.addEventListener("countrychange", function () {
        const countryData = iti.getSelectedCountryData();
        const countryCodeInput = document.querySelector(`#${countryCodeInputId}`);
        if (countryCodeInput) {
            countryCodeInput.value = "+" + countryData.dialCode;
        }
        updateMaxLength();
    });

    // Set initial country code
    const initialCountryData = iti.getSelectedCountryData();
    const countryCodeInput = document.querySelector(`#${countryCodeInputId}`);
    if (countryCodeInput) {
        countryCodeInput.value = "+" + initialCountryData.dialCode;
    }

    return iti;
};
