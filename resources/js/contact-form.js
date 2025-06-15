/**
 * Contact Form Handling
 * Handles form submission, validation, and user feedback
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    if (!form) return;

    const messageTextarea = document.getElementById('message');
    const messageCount = document.getElementById('message-count');
    const formStatus = document.getElementById('form-status');
    const submitButton = form.querySelector('button[type="submit"]');

    // Character counter for message textarea
    if (messageTextarea && messageCount) {
        messageTextarea.addEventListener('input', updateCharacterCount);
        updateCharacterCount.call(messageTextarea);
    }

    // Form submission handler
    form.addEventListener('submit', handleFormSubmit);

    function updateCharacterCount() {
        if (messageCount) {
            messageCount.textContent = this.value.length;
        }
    }

    async function handleFormSubmit(e) {
        e.preventDefault();
        
        // Disable submit button and show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = 'Sending...';
        if (formStatus) {
            formStatus.classList.add('hidden');
        }
        
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                showSuccess(data.message || 'Thank you for your message! We will get back to you soon.');
                form.reset();
            } else {
                showErrors(data.errors || { message: data.message || 'An error occurred. Please try again.' });
            }
        } catch (error) {
            console.error('Error:', error);
            showErrors({ message: 'An error occurred. Please try again.' });
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Send Message';
            if (formStatus) {
                formStatus.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    }

    function showSuccess(message) {
        if (!formStatus) return;
        
        formStatus.className = 'bg-green-50 border border-green-200 text-green-800 p-4 rounded-md mt-4';
        formStatus.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        `;
        formStatus.classList.remove('hidden');
    }

    function showErrors(errors) {
        if (!formStatus) return;
        
        let errorHtml = '<div class="text-red-700">';
        
        if (typeof errors === 'object' && !Array.isArray(errors)) {
            errorHtml += '<ul class="list-disc list-inside">';
            for (const [field, message] of Object.entries(errors)) {
                errorHtml += `<li>${message}</li>`;
            }
            errorHtml += '</ul>';
        } else {
            errorHtml += errors.message || 'An error occurred. Please try again.';
        }
        
        errorHtml += '</div>';
        
        formStatus.className = 'bg-red-50 border border-red-200 text-red-800 p-4 rounded-md mt-4';
        formStatus.innerHTML = errorHtml;
        formStatus.classList.remove('hidden');
    }
});
