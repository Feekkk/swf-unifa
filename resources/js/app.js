// UniKL RCMP Financial Aids Website JavaScript

// Import utility functions
import { DOM, Animation, Validation, A11y } from './utils.js';

// Import component modules
import Navigation from './navigation.js';
import Slideshow from './slideshow.js';
import FormHandler from './forms.js';
import AuthHandler from './auth.js';

// Make utilities available globally for debugging
window.DOM = DOM;
window.Animation = Animation;
window.Validation = Validation;
window.A11y = A11y;

// Initialize application when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  console.log('UniKL RCMP Financial Aids Website initialized');
  
  // Initialize components that aren't auto-initialized
  initializeComponents();
  
  // Setup global event listeners
  setupGlobalEvents();
  
  // Auto-dismiss success notifications after 5 seconds
  setupAutoDismissNotifications();
});

/**
 * Initialize components that need manual setup
 */
function initializeComponents() {
  // Initialize any additional slideshows beyond the hero
  const additionalSlideshows = DOM.selectAll('.slideshow:not(.hero-slideshow)');
  additionalSlideshows.forEach(slideshow => {
    new Slideshow(slideshow);
  });
  
  // Initialize forms that don't have data-validate attribute
  const contactForms = DOM.selectAll('.contact-form');
  contactForms.forEach(form => {
    new FormHandler(form, {
      submitCallback: handleContactFormSubmit
    });
  });
}

/**
 * Setup global event listeners
 */
function setupGlobalEvents() {
  // Handle external links only (links to different domains)
  const currentHost = window.location.host;
  DOM.selectAll('a[href^="http"]').forEach(link => {
    try {
      const linkUrl = new URL(link.href);
      // Only add target="_blank" if the link is to a different domain
      if (linkUrl.host !== currentHost) {
        link.setAttribute('target', '_blank');
        link.setAttribute('rel', 'noopener noreferrer');
      }
    } catch (e) {
      // If URL parsing fails, skip this link
      console.warn('Could not parse URL:', link.href);
    }
  });
  
  // Handle scroll-to-top functionality
  const scrollToTopBtn = DOM.select('.scroll-to-top');
  if (scrollToTopBtn) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 500) {
        scrollToTopBtn.style.display = 'block';
      } else {
        scrollToTopBtn.style.display = 'none';
      }
    });
    
    DOM.on(scrollToTopBtn, 'click', (e) => {
      e.preventDefault();
      Animation.scrollTo(document.body, 0);
    });
  }
  
  // Handle print functionality
  const printBtns = DOM.selectAll('.print-btn');
  printBtns.forEach(btn => {
    DOM.on(btn, 'click', (e) => {
      e.preventDefault();
      window.print();
    });
  });
}

/**
 * Setup auto-dismiss for success notifications
 */
function setupAutoDismissNotifications() {
  // Get all success notifications
  const successNotifications = DOM.selectAll('.notification.is-success');
  
  successNotifications.forEach(notification => {
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
      notification.style.transition = 'opacity 0.3s ease-out';
      notification.style.opacity = '0';
      setTimeout(() => {
        notification.remove();
      }, 300);
    }, 5000);
  });
}

/**
 * Handle contact form submission
 * @param {Object} data - Form data
 * @param {Element} form - Form element
 */
async function handleContactFormSubmit(data, form) {
  // Client-side validation before submission
  const validationErrors = validateContactForm(data);
  if (validationErrors.length > 0) {
    throw new Error(validationErrors[0]);
  }

  // Submit to Laravel route
  const formData = new FormData();
  Object.entries(data).forEach(([key, value]) => {
    if (value) formData.append(key, value);
  });

  // Add CSRF token
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  if (csrfToken) {
    formData.append('_token', csrfToken);
  }

  const response = await fetch('/contact', {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  });
  
  if (!response.ok) {
    const errorData = await response.json().catch(() => ({}));
    throw new Error(errorData.message || 'Failed to send message. Please try again.');
  }
  
  return response.json().catch(() => ({ success: true }));
}

/**
 * Validate contact form data
 * @param {Object} data - Form data to validate
 * @returns {Array} - Array of validation errors
 */
function validateContactForm(data) {
  const errors = [];
  
  // Name validation
  if (!data.name || data.name.trim().length < 2) {
    errors.push('Name must be at least 2 characters long');
  }
  if (data.name && data.name.length > 100) {
    errors.push('Name cannot exceed 100 characters');
  }
  
  // Email validation
  if (!data.email) {
    errors.push('Email address is required');
  } else if (!Validation.isValidEmail(data.email)) {
    errors.push('Please enter a valid email address');
  }
  
  // Student ID validation (optional but must be valid if provided)
  if (data.student_id && !Validation.isValidStudentId(data.student_id)) {
    errors.push('Student ID must be exactly 12 digits');
  }
  
  // Message validation
  if (!data.message || data.message.trim().length < 10) {
    errors.push('Message must be at least 10 characters long');
  }
  if (data.message && data.message.length > 1000) {
    errors.push('Message cannot exceed 1000 characters');
  }
  
  return errors;
}

// Export for use in other modules
export {
  DOM,
  Animation,
  Validation,
  A11y,
  Navigation,
  Slideshow,
  FormHandler,
  AuthHandler
};
