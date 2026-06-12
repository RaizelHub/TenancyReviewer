/**
 * Dashboard Customizer
 * Enables drag-and-drop functionality for dashboard components and custom icons
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard customizer loaded');

    // Make sure the buttons exist before attaching event listeners
    const toggleBtn = document.getElementById('toggleCustomizeMode');
    const saveBtn = document.getElementById('saveCustomizations');
    const resetBtn = document.getElementById('resetCustomizations');

    if (toggleBtn && saveBtn && resetBtn) {
        console.log('Customization buttons found');
        toggleBtn.addEventListener('click', toggleCustomizeMode);
        saveBtn.addEventListener('click', saveCustomizations);
        resetBtn.addEventListener('click', resetCustomizations);
    } else {
        console.error('Customization buttons not found', {
            toggleBtn: !!toggleBtn,
            saveBtn: !!saveBtn,
            resetBtn: !!resetBtn
        });
    }

    initDashboardCustomizer();
    initIconCustomizer();

    // Load customizations
    loadCustomizations();

    // Trigger global icon customization if available
    if (window.applyIconCustomizations) {
        console.log('Triggering global icon customizations from dashboard-customizer');
        window.applyIconCustomizations();
    }
});

/**
 * Initialize the dashboard customizer
 */
function initDashboardCustomizer() {
    const dashboardSections = document.querySelectorAll('.dashboard-section');

    // Make sections draggable
    dashboardSections.forEach(section => {
        // Add draggable attribute and class
        section.setAttribute('draggable', 'true');
        section.classList.add('draggable-section');

        // Add drag handle
        const dragHandle = document.createElement('div');
        dragHandle.className = 'drag-handle';
        dragHandle.innerHTML = '<i class="fas fa-grip-vertical text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"></i>';
        section.querySelector('.section-header').prepend(dragHandle);

        // Add event listeners
        section.addEventListener('dragstart', handleDragStart);
        section.addEventListener('dragover', handleDragOver);
        section.addEventListener('dragenter', handleDragEnter);
        section.addEventListener('dragleave', handleDragLeave);
        section.addEventListener('drop', handleDrop);
        section.addEventListener('dragend', handleDragEnd);
    });

    // Event listeners are now attached in the DOMContentLoaded event
}

/**
 * Initialize the icon customizer
 */
function initIconCustomizer() {
    const customizableIcons = document.querySelectorAll('.customizable-icon');

    customizableIcons.forEach(icon => {
        icon.addEventListener('click', function(e) {
            if (document.body.classList.contains('customizing-mode')) {
                e.preventDefault();
                e.stopPropagation();
                openIconSelector(icon);
            }
        });
    });
}



/**
 * Toggle customization mode
 */
function toggleCustomizeMode() {
    console.log('Toggle customize mode called');

    const body = document.body;
    const saveBtn = document.getElementById('saveCustomizations');
    const resetBtn = document.getElementById('resetCustomizations');
    const toggleBtn = document.getElementById('toggleCustomizeMode');

    if (!saveBtn || !resetBtn || !toggleBtn) {
        console.error('Could not find all required buttons', {
            saveBtn: !!saveBtn,
            resetBtn: !!resetBtn,
            toggleBtn: !!toggleBtn
        });
        return;
    }

    if (body.classList.contains('customizing-mode')) {
        console.log('Turning off customizing mode');
        // Turn off customizing mode
        body.classList.remove('customizing-mode');
        saveBtn.classList.add('hidden');
        resetBtn.classList.add('hidden');
        toggleBtn.innerHTML = '<i class="fas fa-paint-brush mr-2"></i><span>Customize</span>';

        // Hide all drag handles
        document.querySelectorAll('.drag-handle').forEach(handle => {
            handle.style.display = 'none';
        });
    } else {
        console.log('Turning on customizing mode');
        // Turn on customizing mode
        body.classList.add('customizing-mode');
        saveBtn.classList.remove('hidden');
        resetBtn.classList.remove('hidden');
        toggleBtn.innerHTML = '<i class="fas fa-times mr-2"></i><span>Exit</span>';

        // Show all drag handles
        document.querySelectorAll('.drag-handle').forEach(handle => {
            handle.style.display = 'flex';
        });
    }
}

/**
 * Open the icon selector modal
 */
function openIconSelector(iconElement) {
    // Create modal for icon selection
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.id = 'iconSelectorModal';

    const iconOptions = [
        // Basic navigation
        'fa-home', 'fa-book', 'fa-user-graduate', 'fa-tasks', 'fa-clipboard-check',
        'fa-question-circle', 'fa-comments', 'fa-chart-bar', 'fa-calendar', 'fa-bell',

        // Settings & Admin icons
        'fa-cog', 'fa-sliders-h', 'fa-tools', 'fa-wrench', 'fa-user-cog',
        'fa-users-cog', 'fa-shield-alt', 'fa-lock', 'fa-key', 'fa-user-shield',
        'fa-cogs', 'fa-server', 'fa-database', 'fa-sitemap', 'fa-tachometer-alt',
        'fa-clipboard-list', 'fa-list-ul', 'fa-th-large', 'fa-columns', 'fa-layer-group',

        // Education specific
        'fa-users', 'fa-graduation-cap', 'fa-chalkboard-teacher', 'fa-award',
        'fa-user-tie', 'fa-school', 'fa-university', 'fa-book-open', 'fa-book-reader',
        'fa-pencil-alt', 'fa-pen', 'fa-highlighter', 'fa-edit', 'fa-chalkboard',

        // Content & Media
        'fa-star', 'fa-heart', 'fa-bookmark', 'fa-file', 'fa-folder', 'fa-image',
        'fa-video', 'fa-music', 'fa-globe', 'fa-link', 'fa-search', 'fa-envelope',
        'fa-phone', 'fa-map-marker', 'fa-clock', 'fa-chart-line', 'fa-chart-pie',

        // Actions & Feedback
        'fa-check', 'fa-check-circle', 'fa-times', 'fa-times-circle', 'fa-exclamation-circle',
        'fa-info-circle', 'fa-question', 'fa-plus', 'fa-minus', 'fa-trash', 'fa-download',
        'fa-upload', 'fa-sync', 'fa-redo', 'fa-undo', 'fa-save', 'fa-print'
    ];

    // Define icon categories
    const iconCategories = {
        'Navigation': iconOptions.slice(0, 10),
        'Settings & Admin': iconOptions.slice(10, 30),
        'Education': iconOptions.slice(30, 45),
        'Content & Media': iconOptions.slice(45, 62),
        'Actions & Feedback': iconOptions.slice(62)
    };

    // Generate HTML for each category
    let categoriesHtml = '';
    Object.entries(iconCategories).forEach(([category, icons]) => {
        let iconsHtml = '';
        icons.forEach(icon => {
            iconsHtml += `<div class="icon-option p-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-center" data-icon="${icon}">
                <i class="fas ${icon} text-xl"></i>
            </div>`;
        });

        categoriesHtml += `
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 px-2">${category}</h4>
                <div class="grid grid-cols-6 gap-2">
                    ${iconsHtml}
                </div>
            </div>
        `;
    });

    modal.innerHTML = `
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Select Icon</h3>
                <button id="closeIconSelector" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            ${categoriesHtml}
        </div>
    `;

    document.body.appendChild(modal);

    // Add event listeners
    document.getElementById('closeIconSelector').addEventListener('click', () => {
        document.body.removeChild(modal);
    });

    document.querySelectorAll('.icon-option').forEach(option => {
        option.addEventListener('click', () => {
            const newIcon = option.getAttribute('data-icon');
            updateIcon(iconElement, newIcon);
            document.body.removeChild(modal);
        });
    });
}

/**
 * Update an icon
 */
function updateIcon(iconElement, newIconClass) {
    // Remove all fa-* classes
    const classList = [...iconElement.classList];
    classList.forEach(cls => {
        if (cls.startsWith('fa-')) {
            iconElement.classList.remove(cls);
        }
    });

    // Add new icon class
    iconElement.classList.add(newIconClass);

    // Store the customization
    const iconId = iconElement.closest('[data-icon-id]')?.getAttribute('data-icon-id');
    if (iconId) {
        const customizations = getCustomizations();
        if (!customizations.icons) customizations.icons = {};
        customizations.icons[iconId] = newIconClass;
        localStorage.setItem('dashboardCustomizations', JSON.stringify(customizations));

        // Apply to all matching elements on the page
        const allMatchingElements = document.querySelectorAll(`[data-icon-id="${iconId}"] i`);
        if (allMatchingElements.length > 1) {
            console.log(`Updating all ${allMatchingElements.length} instances of icon ${iconId}`);
            allMatchingElements.forEach(el => {
                if (el !== iconElement) {
                    // Remove existing fa-* classes
                    const elClassList = [...el.classList];
                    elClassList.forEach(cls => {
                        if (cls.startsWith('fa-')) {
                            el.classList.remove(cls);
                        }
                    });
                    // Add new icon class
                    el.classList.add(newIconClass);
                }
            });
        }

        // Trigger global icon customization if available
        if (window.applyIconCustomizations) {
            console.log('Triggering global icon customizations after update');
            window.applyIconCustomizations();
        }
    }
}

/**
 * Drag and drop event handlers
 */
let draggedItem = null;

function handleDragStart(e) {
    draggedItem = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.outerHTML);
    this.classList.add('dragging');
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    this.classList.add('drag-over');
}

function handleDragLeave(e) {
    this.classList.remove('drag-over');
}

function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }

    if (draggedItem !== this) {
        const container = this.parentNode;
        const children = Array.from(container.children);
        const draggedIndex = children.indexOf(draggedItem);
        const targetIndex = children.indexOf(this);

        if (draggedIndex < targetIndex) {
            container.insertBefore(draggedItem, this.nextSibling);
        } else {
            container.insertBefore(draggedItem, this);
        }

        // Save the new order
        saveComponentOrder();
    }

    this.classList.remove('drag-over');
    return false;
}

function handleDragEnd(e) {
    this.classList.remove('dragging');
    document.querySelectorAll('.drag-over').forEach(item => {
        item.classList.remove('drag-over');
    });
}

/**
 * Save the current component order
 */
function saveComponentOrder() {
    const sections = document.querySelectorAll('.dashboard-section');
    const order = Array.from(sections).map(section => section.getAttribute('data-section-id'));

    const customizations = getCustomizations();
    customizations.sectionOrder = order;
    localStorage.setItem('dashboardCustomizations', JSON.stringify(customizations));
}

/**
 * Save all customizations
 */
function saveCustomizations() {
    // Already saved in real-time, just show confirmation
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-20 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    toast.innerHTML = '<i class="fas fa-check mr-2"></i> Customizations saved!';
    document.body.appendChild(toast);

    setTimeout(() => {
        document.body.removeChild(toast);
    }, 3000);

    // Trigger global icon customization if available
    if (window.applyIconCustomizations) {
        console.log('Triggering global icon customizations after save');
        window.applyIconCustomizations();
    }

    // Exit customization mode
    toggleCustomizeMode();
}

/**
 * Reset all customizations
 */
function resetCustomizations() {
    if (confirm('Are you sure you want to reset all customizations?')) {
        localStorage.removeItem('dashboardCustomizations');
        window.location.reload();
    }
}

/**
 * Get stored customizations
 */
function getCustomizations() {
    const stored = localStorage.getItem('dashboardCustomizations');
    return stored ? JSON.parse(stored) : {};
}

/**
 * Load saved customizations
 */
function loadCustomizations() {
    try {
        console.log('Loading customizations...');
        const customizations = getCustomizations();

        // Check if we have any customizations to load
        if (!customizations || Object.keys(customizations).length === 0) {
            console.log('No customizations found in localStorage');
            return;
        }

        // Load section order (only on dashboard page)
        if (customizations.sectionOrder && customizations.sectionOrder.length > 0) {
            const container = document.querySelector('.dashboard-container');
            if (container) {
                const sections = document.querySelectorAll('.dashboard-section');
                const sectionsArray = Array.from(sections);

                customizations.sectionOrder.forEach(sectionId => {
                    const section = sectionsArray.find(s => s.getAttribute('data-section-id') === sectionId);
                    if (section) {
                        container.appendChild(section);
                    }
                });
            }
        }

        // Load icon customizations (works on all pages)
        if (customizations.icons) {
            console.log('Found icon customizations:', Object.keys(customizations.icons).length);
            Object.entries(customizations.icons).forEach(([iconId, iconClass]) => {
                const iconElement = document.querySelector(`[data-icon-id="${iconId}"] i`);
                if (iconElement) {
                    // Remove existing fa-* classes
                    const classList = [...iconElement.classList];
                    classList.forEach(cls => {
                        if (cls.startsWith('fa-')) {
                            iconElement.classList.remove(cls);
                        }
                    });

                    // Add saved icon class
                    iconElement.classList.add(iconClass);
                    console.log(`Applied icon ${iconClass} to ${iconId}`);
                } else {
                    console.log(`Icon element with data-icon-id="${iconId}" not found`);
                }
            });
        }
    } catch (error) {
        console.error('Error loading customizations:', error);
    }
}
