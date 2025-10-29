// Authentication JavaScript Module
// Handles login, registration, and password reset functionality

import { DOM, Validation, A11y } from './utils.js';

/**
 * Authentication Handler Class
 */
class AuthHandler {
  constructor() {
    this.init();
  }

  /**
   * Initialize authentication functionality
   */
  init() {
    this.setupLoginForm();
    this.setupRegisterForm();
    this.setupPasswordResetForm();
    this.setupRealTimeValidation();
    this.setupLoadingStates();
  }

  /**
   * Setup login form functionality
   */
  setupLoginForm() {
    const loginForm = DOM.select('#login-form, form[action*="login"]');
    if (!loginForm) return;

    DOM.on(loginForm, 'submit', async (e) => {
      e.preventDefault();
      
      const formData = new FormData(loginForm);
      const submitBtn = DOM.select('button[type="submit"]', loginForm);
      
      try {
        this.setLoadingState(submitBtn, true, 'Signing In...');
        
        // Client-side validation
        if (!this.validateLoginForm(loginForm)) {
          throw new Error('Please correct the errors in the form');
        }

        // Submit form
        const response = await fetch(loginForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const result = await response.json().catch(() => ({}));

        if (response.ok) {
          this.showSuccess('Login successful! Redirecting...', loginForm);
          
          // Redirect after short delay
          setTimeout(() => {
            window.location.href = result.redirect || '/';
          }, 1000);
        } else {
          throw new Error(result.message || 'Login failed. Please check your credentials.');
        }

      } catch (error) {
        this.showError(error.message, loginForm);
        A11y.announce('Login failed: ' + error.message, 'assertive');
      } finally {
        this.setLoadingState(submitBtn, false, 'Sign In');
      }
    });
  }

  /**
   * Setup registration form functionality
   */
  setupRegisterForm() {
    const registerForm = DOM.select('#register-form, form[action*="register"]');
    if (!registerForm) return;

    // Setup AJAX validation for unique fields
    this.setupUniqueFieldValidation(registerForm);

    DOM.on(registerForm, 'submit', async (e) => {
      e.preventDefault();
      
      const formData = new FormData(registerForm);
      const submitBtn = DOM.select('button[type="submit"]', registerForm);
      
      try {
        this.setLoadingState(submitBtn, true, 'Creating Account...');
        
        // Client-side validation
        if (!this.validateRegisterForm(registerForm)) {
          throw new Error('Please correct the errors in the form');
        }

        // Submit form
        const response = await fetch(registerForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const result = await response.json().catch(() => ({}));

        if (response.ok) {
          this.showSuccess('Account created successfully! Redirecting...', registerForm);
          
          // Redirect after short delay
          setTimeout(() => {
            window.location.href = result.redirect || '/';
          }, 1500);
        } else {
          // Handle validation errors
          if (result.errors) {
            this.displayValidationErrors(result.errors, registerForm);
          } else {
            throw new Error(result.message || 'Registration failed. Please try again.');
          }
        }

      } catch (error) {
        this.showError(error.message, registerForm);
        A11y.announce('Registration failed: ' + error.message, 'assertive');
      } finally {
        this.setLoadingState(submitBtn, false, 'Create Account');
      }
    });
  }

  /**
   * Setup password reset form functionality
   */
  setupPasswordResetForm() {
    const resetForm = DOM.select('form[action*="password"]');
    if (!resetForm) return;

    DOM.on(resetForm, 'submit', async (e) => {
      e.preventDefault();
      
      const formData = new FormData(resetForm);
      const submitBtn = DOM.select('button[type="submit"]', resetForm);
      
      try {
        this.setLoadingState(submitBtn, true, 'Sending Reset Link...');
        
        // Submit form
        const response = await fetch(resetForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const result = await response.json().catch(() => ({}));

        if (response.ok) {
          this.showSuccess('Password reset link sent! Check your email.', resetForm);
        } else {
          throw new Error(result.message || 'Failed to send reset link. Please try again.');
        }

      } catch (error) {
        this.showError(error.message, resetForm);
      } finally {
        this.setLoadingState(submitBtn, false, 'Send Reset Link');
      }
    });
  }

  /**
   * Setup real-time validation for unique fields
   */
  setupUniqueFieldValidation(form) {
    const uniqueFields = ['username', 'email', 'student_id'];
    
    uniqueFields.forEach(fieldName => {
      const field = DOM.select(`input[name="${fieldName}"]`, form);
      if (!field) return;

      let validationTimeout;
      
      DOM.on(field, 'blur', async () => {
        const value = field.value.trim();
        if (!value) return;

        clearTimeout(validationTimeout);
        validationTimeout = setTimeout(async () => {
          await this.validateUniqueField(field, fieldName, value);
        }, 500);
      });
    });
  }

  /**
   * Validate unique field via AJAX
   */
  async validateUniqueField(field, fieldName, value) {
    const checkUrl = `/check-${fieldName.replace('_', '-')}`;
    
    try {
      const response = await fetch(checkUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        body: JSON.stringify({ [fieldName]: value })
      });

      const result = await response.json();
      
      if (result.available) {
        this.setFieldState(field, 'success', '✓ Available');
      } else {
        this.setFieldState(field, 'error', result.message);
      }

    } catch (error) {
      console.warn('Unique validation failed:', error);
    }
  }

  /**
   * Setup real-time validation for all forms
   */
  setupRealTimeValidation() {
    const forms = DOM.selectAll('form[data-validate], .auth-form');
    
    forms.forEach(form => {
      const inputs = DOM.selectAll('input, textarea, select', form);
      
      inputs.forEach(input => {
        DOM.on(input, 'blur', () => {
          this.validateField(input);
        });

        DOM.on(input, 'input', () => {
          // Clear error state on input
          if (input.classList.contains('error')) {
            this.clearFieldState(input);
          }
        });
      });
    });
  }

  /**
   * Setup loading states for all forms
   */
  setupLoadingStates() {
    // Prevent double submission
    const forms = DOM.selectAll('form');
    
    forms.forEach(form => {
      let isSubmitting = false;
      
      DOM.on(form, 'submit', (e) => {
        if (isSubmitting) {
          e.preventDefault();
          return false;
        }
        isSubmitting = true;
        
        // Reset after 10 seconds as fallback
        setTimeout(() => {
          isSubmitting = false;
        }, 10000);
      });
    });
  }

  /**
   * Validate login form
   */
  validateLoginForm(form) {
    const loginField = DOM.select('input[name="login"]', form);
    const passwordField = DOM.select('input[name="password"]', form);
    
    let isValid = true;

    // Validate login field
    if (!this.validateField(loginField)) {
      isValid = false;
    }

    // Validate password field
    if (!this.validateField(passwordField)) {
      isValid = false;
    }

    return isValid;
  }

  /**
   * Validate registration form
   */
  validateRegisterForm(form) {
    const requiredFields = [
      'full_name', 'username', 'email', 'bank_name', 'bank_account_number',
      'phone_number', 'street_address', 'city', 'state', 'postal_code',
      'student_id', 'course', 'semester', 'year_of_study', 'password'
    ];
    
    let isValid = true;

    requiredFields.forEach(fieldName => {
      const field = DOM.select(`input[name="${fieldName}"], select[name="${fieldName}"]`, form);
      if (field && !this.validateField(field)) {
        isValid = false;
      }
    });

    // Validate password confirmation
    const passwordField = DOM.select('input[name="password"]', form);
    const confirmField = DOM.select('input[name="password_confirmation"]', form);
    
    if (passwordField && confirmField) {
      if (passwordField.value !== confirmField.value) {
        this.setFieldState(confirmField, 'error', 'Passwords do not match');
        isValid = false;
      }
    }

    // Validate terms acceptance
    const termsField = DOM.select('input[name="terms_accepted"]', form);
    if (termsField && !termsField.checked) {
      this.setFieldState(termsField, 'error', 'You must accept the terms and conditions');
      isValid = false;
    }

    return isValid;
  }

  /**
   * Validate individual field
   */
  validateField(field) {
    if (!field) return true;

    const value = field.value.trim();
    const fieldName = field.name;
    const fieldType = field.type;
    let isValid = true;
    let errorMessage = '';

    // Required field validation
    if (field.hasAttribute('required') && !value) {
      isValid = false;
      errorMessage = 'This field is required';
    }

    // Type-specific validation
    if (value && isValid) {
      switch (fieldType) {
        case 'email':
          if (!Validation.isValidEmail(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
          }
          break;

        case 'tel':
          if (!Validation.isValidPhone(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid phone number';
          }
          break;

        case 'password':
          const passwordResult = Validation.validatePassword(value);
          if (!passwordResult.isValid) {
            isValid = false;
            errorMessage = passwordResult.errors[0];
          }
          break;
      }

      // Field-specific validation
      switch (fieldName) {
        case 'student_id':
          if (!Validation.isValidStudentId(value)) {
            isValid = false;
            errorMessage = 'Student ID must be in correct format';
          }
          break;

        case 'postal_code':
          if (!/^\d{5}$/.test(value)) {
            isValid = false;
            errorMessage = 'Postal code must be exactly 5 digits';
          }
          break;

        case 'bank_account_number':
          if (!/^\d+$/.test(value)) {
            isValid = false;
            errorMessage = 'Bank account number can only contain digits';
          }
          break;
      }
    }

    // Update field state
    if (!isValid) {
      this.setFieldState(field, 'error', errorMessage);
    } else if (value) {
      this.setFieldState(field, 'success');
    } else {
      this.clearFieldState(field);
    }

    return isValid;
  }

  /**
   * Set field validation state
   */
  setFieldState(field, state, message = '') {
    // Remove existing states
    field.classList.remove('error', 'success');
    
    // Remove existing error/success messages
    const existingMessage = field.parentNode.querySelector('.form-error:not([id]), .form-success:not([id])');
    if (existingMessage) {
      existingMessage.remove();
    }

    // Add new state
    if (state === 'error') {
      field.classList.add('error');
      field.setAttribute('aria-invalid', 'true');
      
      if (message) {
        const errorSpan = DOM.create('span', {
          className: 'form-error',
          role: 'alert'
        }, message);
        field.parentNode.appendChild(errorSpan);
      }
    } else if (state === 'success') {
      field.classList.add('success');
      field.setAttribute('aria-invalid', 'false');
      
      if (message) {
        const successSpan = DOM.create('span', {
          className: 'form-success'
        }, message);
        field.parentNode.appendChild(successSpan);
      }
    }
  }

  /**
   * Clear field validation state
   */
  clearFieldState(field) {
    field.classList.remove('error', 'success');
    field.removeAttribute('aria-invalid');
    
    const message = field.parentNode.querySelector('.form-error:not([id]), .form-success:not([id])');
    if (message) {
      message.remove();
    }
  }

  /**
   * Set loading state for submit button
   */
  setLoadingState(button, loading, text = '') {
    if (!button) return;

    if (loading) {
      button.disabled = true;
      button.classList.add('loading');
      button.setAttribute('aria-busy', 'true');
      if (text) button.textContent = text;
    } else {
      button.disabled = false;
      button.classList.remove('loading');
      button.setAttribute('aria-busy', 'false');
      if (text) button.textContent = text;
    }
  }

  /**
   * Show success message
   */
  showSuccess(message, form) {
    this.showMessage(message, 'success', form);
  }

  /**
   * Show error message
   */
  showError(message, form) {
    this.showMessage(message, 'error', form);
  }

  /**
   * Show form message
   */
  showMessage(message, type, form) {
    // Remove existing messages
    const existingMessage = DOM.select('.form-message, .alert', form);
    if (existingMessage) {
      existingMessage.remove();
    }

    // Create new message
    const messageElement = DOM.create('div', {
      className: `form-message alert alert-${type}`,
      role: 'alert'
    }, message);

    // Insert at top of form
    form.insertBefore(messageElement, form.firstChild);

    // Auto-remove success messages
    if (type === 'success') {
      setTimeout(() => {
        if (messageElement.parentElement) {
          messageElement.remove();
        }
      }, 5000);
    }

    // Scroll message into view
    messageElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  /**
   * Display validation errors from server
   */
  displayValidationErrors(errors, form) {
    Object.entries(errors).forEach(([fieldName, messages]) => {
      const field = DOM.select(`input[name="${fieldName}"], select[name="${fieldName}"]`, form);
      if (field && messages.length > 0) {
        this.setFieldState(field, 'error', messages[0]);
      }
    });

    // Focus first error field
    const firstErrorField = DOM.select('.error', form);
    if (firstErrorField) {
      firstErrorField.focus();
    }
  }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  new AuthHandler();
});

export default AuthHandler;