/**
 * Icon Persistence
 * This script ensures that icon customizations persist across page navigation
 */

(function() {
    // Run immediately when script is loaded
    console.log('Icon persistence script loaded');
    
    // Function to apply icon customizations
    function applyIconCustomizations() {
        try {
            const customizations = localStorage.getItem('dashboardCustomizations');
            if (customizations) {
                const parsedCustomizations = JSON.parse(customizations);
                if (parsedCustomizations.icons) {
                    console.log('Applying icon customizations from localStorage');
                    Object.entries(parsedCustomizations.icons).forEach(([iconId, iconClass]) => {
                        const iconElements = document.querySelectorAll(`[data-icon-id="${iconId}"] i`);
                        if (iconElements.length > 0) {
                            iconElements.forEach(iconElement => {
                                // Remove existing fa-* classes
                                const classList = [...iconElement.classList];
                                classList.forEach(cls => {
                                    if (cls.startsWith('fa-')) {
                                        iconElement.classList.remove(cls);
                                    }
                                });
                                // Add saved icon class
                                iconElement.classList.add(iconClass);
                            });
                            console.log(`Applied custom icon ${iconClass} to ${iconId} (${iconElements.length} elements)`);
                        } else {
                            console.log(`No elements found for icon ID: ${iconId}`);
                        }
                    });
                }
            }
        } catch (e) {
            console.error('Error applying icon customizations:', e);
        }
    }

    // Apply customizations immediately
    applyIconCustomizations();

    // Apply customizations when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, applying icon customizations');
        applyIconCustomizations();
        
        // Set up a MutationObserver to watch for changes to the DOM
        const observer = new MutationObserver(function(mutations) {
            let shouldApply = false;
            
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Check if any added nodes contain customizable icons
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) {
                            if (node.querySelector('[data-icon-id]') || 
                                (node.hasAttribute && node.hasAttribute('data-icon-id'))) {
                                shouldApply = true;
                            }
                        }
                    });
                }
            });
            
            if (shouldApply) {
                console.log('DOM changed, reapplying icon customizations');
                applyIconCustomizations();
            }
        });
        
        // Start observing the entire document
        observer.observe(document.body, { 
            childList: true, 
            subtree: true 
        });
    });

    // Listen for Alpine.js navigation events if available
    document.addEventListener('alpine:navigated', function() {
        console.log('Alpine navigation detected, reapplying icon customizations');
        setTimeout(applyIconCustomizations, 50);
    });

    // Intercept link clicks to ensure customizations persist
    document.addEventListener('click', function(e) {
        // Check if the click was on a link or inside a link
        const link = e.target.closest('a');
        if (link && link.href && link.href.startsWith(window.location.origin)) {
            // It's an internal link, store a flag to indicate we're navigating
            sessionStorage.setItem('navigating', 'true');
        }
    });

    // Check if we're coming from a navigation
    if (sessionStorage.getItem('navigating') === 'true') {
        console.log('Page loaded after navigation, applying icon customizations');
        sessionStorage.removeItem('navigating');
        // Apply with a slight delay to ensure the DOM is ready
        setTimeout(applyIconCustomizations, 100);
    }

    // Expose the function globally for manual triggering if needed
    window.applyIconCustomizations = applyIconCustomizations;
})();
