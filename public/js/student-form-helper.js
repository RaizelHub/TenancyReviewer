/**
 * Student Form Helper
 * 
 * This script helps ensure the student creation form works correctly
 * by handling form submission and providing proper error handling.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Find the student creation form
    const studentForm = document.querySelector('form[action*="students"][method="POST"]');
    
    if (studentForm) {
        console.log('Student form found, initializing helper...');
        
        // Add submit event listener
        studentForm.addEventListener('submit', function(e) {
            // Don't prevent default form submission yet
            // We'll only do that if we need to handle it via AJAX
            
            console.log('Form submitted, checking if AJAX is needed...');
            
            // Check if there are any validation errors
            const hasErrors = document.querySelectorAll('.text-red-500').length > 0;
            
            if (hasErrors) {
                console.log('Validation errors found, scrolling to first error...');
                const firstError = document.querySelector('.text-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            // Log form data for debugging
            const formData = new FormData(this);
            const formDataObj = {};
            formData.forEach((value, key) => {
                formDataObj[key] = value;
            });
            console.log('Form data:', formDataObj);
            
            // Check if we need to handle the form submission via AJAX
            // This is a fallback in case the normal form submission doesn't work
            const useAjax = localStorage.getItem('use_ajax_for_student_form') === 'true';
            
            if (useAjax) {
                e.preventDefault();
                console.log('Using AJAX for form submission...');
                
                // Get the CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                 document.querySelector('input[name="_token"]')?.value;
                
                if (!csrfToken) {
                    console.error('CSRF token not found!');
                    alert('CSRF token not found. Please refresh the page and try again.');
                    return;
                }
                
                // Show loading state
                const submitButton = studentForm.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = 'Adding Student...';
                
                // Submit the form via AJAX
                fetch(studentForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        // If the server redirected us, follow the redirect
                        window.location.href = response.url;
                        return;
                    }
                    
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'An error occurred while creating the student.');
                        });
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('Student created successfully:', data);
                    
                    // Redirect to the students index page
                    window.location.href = '/app/students';
                })
                .catch(error => {
                    console.error('Error creating student:', error);
                    
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                    
                    // Show error message
                    alert('Error creating student: ' + error.message);
                });
            } else {
                console.log('Using normal form submission...');
                // Let the form submit normally
            }
        });
        
        // Add a debug button
        const debugButton = document.createElement('button');
        debugButton.type = 'button';
        debugButton.className = 'text-xs text-gray-500 mt-2';
        debugButton.textContent = 'Toggle AJAX Mode';
        debugButton.style.marginTop = '10px';
        debugButton.onclick = function() {
            const currentMode = localStorage.getItem('use_ajax_for_student_form') === 'true';
            localStorage.setItem('use_ajax_for_student_form', (!currentMode).toString());
            alert('AJAX mode ' + (!currentMode ? 'enabled' : 'disabled') + '. The form will now use ' + 
                  (!currentMode ? 'AJAX' : 'normal') + ' submission.');
        };
        
        // Add the debug button after the form
        studentForm.parentNode.appendChild(debugButton);
    } else {
        console.log('Student form not found on this page.');
    }
});
