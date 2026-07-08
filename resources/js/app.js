import 'bootstrap';

// Auto-select text in input fields on click/focus
document.addEventListener('focus', function(e) {
    if (e.target && (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA')) {
        const supportedTypes = ['text', 'number', 'password', 'search', 'email', 'url', 'tel'];
        if (e.target.tagName === 'TEXTAREA' || supportedTypes.includes(e.target.type)) {
            // Use setTimeout to ensure it happens after the default click/focus behavior
            setTimeout(() => {
                e.target.select();
            }, 10);
        }
    }
}, true);

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
