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

// Simple scroll to top button
document.addEventListener('DOMContentLoaded', () => {
    const scrollToTopBtn = document.createElement('button');
    scrollToTopBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    `;
    scrollToTopBtn.className = 'fixed bottom-8 right-8 bg-indigo-600 text-white p-3 rounded-full shadow-lg hover:bg-indigo-700 transition-colors duration-200 opacity-0 invisible transition-all';
    scrollToTopBtn.setAttribute('aria-label', 'Scroll to top');
    document.body.appendChild(scrollToTopBtn);

    // Show/hide button based on scroll position
    const handleScroll = () => {
        if (window.scrollY > 300) {
            scrollToTopBtn.classList.remove('opacity-0', 'invisible');
            scrollToTopBtn.classList.add('opacity-100', 'visible');
        } else {
            scrollToTopBtn.classList.remove('opacity-100', 'visible');
            scrollToTopBtn.classList.add('opacity-0', 'invisible');
        }
    };

    // Scroll to top functionality
    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Add scroll event listener
    window.addEventListener('scroll', handleScroll);
});
