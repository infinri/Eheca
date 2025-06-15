// Import Alpine.js
import Alpine from 'alpinejs';
import './contact-form.js';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Contact form handling
document.addEventListener('alpine:init', () => {
    Alpine.data('contactForm', () => ({
        loading: false,
        success: false,
        error: '',
        
        async submitForm() {
            this.loading = true;
            this.error = '';
            
            const form = this.$el;
            const formData = new FormData(form);
            
            try {
                const response = await fetch(form.action || '/contact', {
                    method: form.method || 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    this.success = true;
                    form.reset();
                    // Scroll to top to show success message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    throw new Error(data.message || 'Failed to send message');
                }
            } catch (error) {
                this.error = error.message;
                console.error('Error:', error);
            } finally {
                this.loading = false;
            }
        }
    }));
});
