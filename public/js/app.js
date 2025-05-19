/**
 * Eheca Framework - Main JavaScript
 * 
 * This is the main JavaScript file for the Eheca Framework.
 * It provides basic functionality and can be extended as needed.
 */

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Eheca Framework initialized');
    
    // Add any initialization code here
    initializeComponents();
});

/**
 * Initialize UI components
 */
function initializeComponents() {
    // Add any component initialization code here
    
    // Example: Add active class to current navigation item
    const currentPath = window.location.pathname;
    document.querySelectorAll('nav a').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
}

/**
 * Make an AJAX request
 * 
 * @param {string} url - The URL to make the request to
 * @param {string} method - The HTTP method (GET, POST, etc.)
 * @param {Object} data - The data to send with the request
 * @param {Function} callback - The callback function to execute on success
 * @param {Function} errorCallback - The callback function to execute on error
 */
function makeRequest(url, method = 'GET', data = null, callback = null, errorCallback = null) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (callback) callback(response);
            } catch (e) {
                console.error('Error parsing JSON response', e);
                if (errorCallback) errorCallback(e);
            }
        } else {
            console.error('Request failed with status:', xhr.status);
            if (errorCallback) errorCallback(xhr.statusText);
        }
    };
    
    xhr.onerror = function() {
        console.error('Request failed');
        if (errorCallback) errorCallback('Request failed');
    };
    
    xhr.send(data ? JSON.stringify(data) : null);
}

// Example: Add a simple toast notification system
const Toast = {
    show: function(message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Add show class after a small delay to allow for CSS transition
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Remove toast after duration
        setTimeout(() => {
            toast.classList.remove('show');
            
            // Remove from DOM after transition
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, duration);
    }
};

// Add toast styles if they don't exist
if (!document.getElementById('toast-styles')) {
    const style = document.createElement('style');
    style.id = 'toast-styles';
    style.textContent = `
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 24px;
            border-radius: 4px;
            color: white;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s, transform 0.3s;
            z-index: 1000;
        }
        
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .toast-info { background-color: var(--info-color); }
        .toast-success { background-color: var(--success-color); }
        .toast-warning { background-color: var(--warning-color); }
        .toast-error { background-color: var(--error-color); }
    `;
    document.head.appendChild(style);
}

// Example: Show a welcome message on first visit
if (!localStorage.getItem('eheca_welcome_shown')) {
    Toast.show('Welcome to Eheca Framework!', 'success');
    localStorage.setItem('eheca_welcome_shown', 'true');
}
