/**
 * Emergency fix for stuck icon selector
 * This script can be included on any page to fix a stuck icon selector
 */

(function() {
    // Try to close the icon selector as soon as the script loads
    function emergencyCloseIconSelector() {
        try {
            // Try multiple methods to close the selector
            var iconSelector = document.getElementById('iconSelector');
            if (iconSelector) {
                iconSelector.classList.add('hidden');
                iconSelector.style.display = 'none';
                iconSelector.style.visibility = 'hidden';
                iconSelector.style.opacity = '0';
                iconSelector.style.pointerEvents = 'none';
                console.log('Icon selector closed via direct ID');
            }
            
            // Try by class
            var selectors = document.querySelectorAll('.icon-selector');
            if (selectors.length > 0) {
                selectors.forEach(function(selector) {
                    selector.classList.add('hidden');
                    selector.style.display = 'none';
                    selector.style.visibility = 'hidden';
                    selector.style.opacity = '0';
                    selector.style.pointerEvents = 'none';
                });
                console.log('Icon selector(s) closed via class');
            }
            
            // Add emergency CSS
            var style = document.createElement('style');
            style.textContent = `
                .icon-selector, #iconSelector {
                    display: none !important;
                    visibility: hidden !important;
                    opacity: 0 !important;
                    pointer-events: none !important;
                }
                .hidden {
                    display: none !important;
                }
            `;
            document.head.appendChild(style);
            console.log('Emergency CSS added');
            
            return true;
        } catch (e) {
            console.error('Error in emergencyCloseIconSelector:', e);
            return false;
        }
    }
    
    // Make the function available globally
    window.emergencyCloseIconSelector = emergencyCloseIconSelector;
    
    // Run immediately
    emergencyCloseIconSelector();
    
    // Also run when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', emergencyCloseIconSelector);
    
    // And run again after a short delay
    setTimeout(emergencyCloseIconSelector, 500);
    setTimeout(emergencyCloseIconSelector, 1000);
    setTimeout(emergencyCloseIconSelector, 2000);
})();
