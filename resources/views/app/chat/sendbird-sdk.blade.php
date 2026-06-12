{{-- Sendbird SDK Loader --}}
<script src="/tenancy/assets/js/vendor/sendbird.min.js"></script>
<script>
    // Log that we're initializing Sendbird
    console.log('Sendbird SDK loaded directly');

    // Override Sendbird's default URL handling to prevent it from taking over navigation
    window.sendbirdPreventNavigation = true;

    // Intercept any attempts to change the URL to a Sendbird URL
    const originalPushState = history.pushState;
    history.pushState = function() {
        // Check if the URL contains sendbird_group_channel
        if (arguments[2] && typeof arguments[2] === 'string' && arguments[2].includes('sendbird_group_channel')) {
            console.warn('Prevented navigation to Sendbird URL:', arguments[2]);
            // Don't change the URL
            return;
        }
        // Otherwise, proceed with the original pushState
        return originalPushState.apply(this, arguments);
    };

    // Also intercept replaceState
    const originalReplaceState = history.replaceState;
    history.replaceState = function() {
        // Check if the URL contains sendbird_group_channel
        if (arguments[2] && typeof arguments[2] === 'string' && arguments[2].includes('sendbird_group_channel')) {
            console.warn('Prevented URL replacement with Sendbird URL:', arguments[2]);
            // Don't change the URL
            return;
        }
        // Otherwise, proceed with the original replaceState
        return originalReplaceState.apply(this, arguments);
    };

    // Dispatch an event to notify that Sendbird is ready
    document.addEventListener('DOMContentLoaded', function() {
        if (window.SendBird) {
            console.log('SendBird SDK ready to use');
            document.dispatchEvent(new CustomEvent('sendbird-sdk-ready', { detail: { SendBird } }));
        } else {
            console.error('SendBird object not found after loading script');
        }
    });
</script>
