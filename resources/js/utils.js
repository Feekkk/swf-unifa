// Utility functions for the UniKL RCMP Financial Aids Website

/**
 * DOM utility functions
 */
export const DOM = {
  /**
   * Select a single element
   * @param {string} selector - CSS selector
   * @param {Element} context - Context element (default: document)
   * @returns {Element|null}
   */
  select(selector, context = document) {
    return context.querySelector(selector);
  },

  /**
   * Select multiple elements
   * @param {string} selector - CSS selector
   * @param {Element} context - Context element (default: document)
   * @returns {NodeList}
   */
  selectAll(selector, context = document) {
    return context.querySelectorAll(selector);
  },

  /**
   * Create an element with attributes and content
   * @param {string} tag - HTML tag name
   * @param {Object} attributes - Element attributes
   * @param {string} content - Inner HTML content
   * @returns {Element}
   */
  create(tag, attributes = {}, content = '') {
    const element = document.createElement(tag);
    
    Object.entries(attributes).forEach(([key, value]) => {
      if (key === 'className') {
        element.className = value;
      } else if (key === 'dataset') {
        Object.entries(value).forEach(([dataKey, dataValue]) => {
          element.dataset[dataKey] = dataValue;
        });
      } else {
        element.setAttribute(key, value);
      }
    });
    
    if (content) {
      element.innerHTML = content;
    }
    
    return element;
  },

  /**
   * Add event listener with optional delegation
   * @param {Element|string} target - Element or selector
   * @param {string} event - Event type
   * @param {Function} handler - Event handler
   * @param {string} delegate - Delegation selector (optional)
   */
  on(target, event, handler, delegate = null) {
    const element = typeof target === 'string' ? this.select(target) : target;
    
    if (!element) return;
    
    if (delegate) {
      element.addEventListener(event, (e) => {
        if (e.target.matches(delegate)) {
          handler.call(e.target, e);
        }
      });
    } else {
      element.addEventListener(event, handler);
    }
  },

  /**
   * Toggle class on element
   * @param {Element|string} target - Element or selector
   * @param {string} className - Class name to toggle
   */
  toggleClass(target, className) {
    const element = typeof target === 'string' ? this.select(target) : target;
    if (element) {
      element.classList.toggle(className);
    }
  },

  /**
   * Add class to element
   * @param {Element|string} target - Element or selector
   * @param {string} className - Class name to add
   */
  addClass(target, className) {
    const element = typeof target === 'string' ? this.select(target) : target;
    if (element) {
      element.classList.add(className);
    }
  },

  /**
   * Remove class from element
   * @param {Element|string} target - Element or selector
   * @param {string} className - Class name to remove
   */
  removeClass(target, className) {
    const element = typeof target === 'string' ? this.select(target) : target;
    if (element) {
      element.classList.remove(className);
    }
  }
};

/**
 * Animation utilities
 */
export const Animation = {
  /**
   * Smooth scroll to element
   * @param {Element|string} target - Element or selector
   * @param {number} offset - Offset from top (default: 80px for header)
   */
  scrollTo(target, offset = 80) {
    const element = typeof target === 'string' ? DOM.select(target) : target;
    if (!element) return;
    
    const elementPosition = element.getBoundingClientRect().top;
    const offsetPosition = elementPosition + window.pageYOffset - offset;
    
    window.scrollTo({
      top: offsetPosition,
      behavior: 'smooth'
    });
  },

  /**
   * Fade in element
   * @param {Element} element - Element to fade in
   * @param {number} duration - Animation duration in ms
   */
  fadeIn(element, duration = 300) {
    element.style.opacity = '0';
    element.style.display = 'block';
    
    const start = performance.now();
    
    const animate = (currentTime) => {
      const elapsed = currentTime - start;
      const progress = Math.min(elapsed / duration, 1);
      
      element.style.opacity = progress;
      
      if (progress < 1) {
        requestAnimationFrame(animate);
      }
    };
    
    requestAnimationFrame(animate);
  },

  /**
   * Fade out element
   * @param {Element} element - Element to fade out
   * @param {number} duration - Animation duration in ms
   */
  fadeOut(element, duration = 300) {
    const start = performance.now();
    const startOpacity = parseFloat(getComputedStyle(element).opacity);
    
    const animate = (currentTime) => {
      const elapsed = currentTime - start;
      const progress = Math.min(elapsed / duration, 1);
      
      element.style.opacity = startOpacity * (1 - progress);
      
      if (progress >= 1) {
        element.style.display = 'none';
      } else {
        requestAnimationFrame(animate);
      }
    };
    
    requestAnimationFrame(animate);
  }
};

/**
 * Form validation utilities
 */
export const Validation = {
  /**
   * Email validation
   * @param {string} email - Email to validate
   * @returns {boolean}
   */
  isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  },

  /**
   * Student ID validation (assuming format: YYYYMMDDXXXX)
   * @param {string} studentId - Student ID to validate
   * @returns {boolean}
   */
  isValidStudentId(studentId) {
    const studentIdRegex = /^\d{12}$/;
    return studentIdRegex.test(studentId);
  },

  /**
   * Phone number validation (Malaysian format)
   * @param {string} phone - Phone number to validate
   * @returns {boolean}
   */
  isValidPhone(phone) {
    const phoneRegex = /^(\+?6?01)[0-46-9]-*[0-9]{7,8}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
  },

  /**
   * Password strength validation
   * @param {string} password - Password to validate
   * @returns {Object} - Validation result with score and feedback
   */
  validatePassword(password) {
    const result = {
      score: 0,
      feedback: [],
      isValid: false
    };

    if (password.length >= 8) {
      result.score += 1;
    } else {
      result.feedback.push('Password must be at least 8 characters long');
    }

    if (/[a-z]/.test(password)) {
      result.score += 1;
    } else {
      result.feedback.push('Password must contain lowercase letters');
    }

    if (/[A-Z]/.test(password)) {
      result.score += 1;
    } else {
      result.feedback.push('Password must contain uppercase letters');
    }

    if (/\d/.test(password)) {
      result.score += 1;
    } else {
      result.feedback.push('Password must contain numbers');
    }

    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
      result.score += 1;
    } else {
      result.feedback.push('Password must contain special characters');
    }

    result.isValid = result.score >= 4;
    return result;
  }
};

/**
 * Accessibility utilities
 */
export const A11y = {
  /**
   * Announce message to screen readers
   * @param {string} message - Message to announce
   * @param {string} priority - Priority level (polite|assertive)
   */
  announce(message, priority = 'polite') {
    const announcer = DOM.create('div', {
      'aria-live': priority,
      'aria-atomic': 'true',
      className: 'sr-only'
    });
    
    document.body.appendChild(announcer);
    announcer.textContent = message;
    
    setTimeout(() => {
      document.body.removeChild(announcer);
    }, 1000);
  },

  /**
   * Trap focus within an element
   * @param {Element} element - Element to trap focus within
   */
  trapFocus(element) {
    const focusableElements = element.querySelectorAll(
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];
    
    element.addEventListener('keydown', (e) => {
      if (e.key === 'Tab') {
        if (e.shiftKey) {
          if (document.activeElement === firstElement) {
            lastElement.focus();
            e.preventDefault();
          }
        } else {
          if (document.activeElement === lastElement) {
            firstElement.focus();
            e.preventDefault();
          }
        }
      }
    });
  }
};

/**
 * Debounce function
 * @param {Function} func - Function to debounce
 * @param {number} wait - Wait time in milliseconds
 * @returns {Function}
 */
export function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

/**
 * Throttle function
 * @param {Function} func - Function to throttle
 * @param {number} limit - Limit in milliseconds
 * @returns {Function}
 */
export function throttle(func, limit) {
  let inThrottle;
  return function executedFunction(...args) {
    if (!inThrottle) {
      func.apply(this, args);
      inThrottle = true;
      setTimeout(() => inThrottle = false, limit);
    }
  };
}