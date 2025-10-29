// Form handling and validation functionality
import { DOM, Validation, A11y } from './utils.js';

/**
 * Form Handler Class
 */
class FormHandler {
  constructor(form, options = {}) {
    this.form = typeof form === 'string' ? DOM.select(form) : form;
    
    if (!this.form) {
      console.warn('Form not found');
      return;
    }

    this.options = {
      validateOnBlur: true,
      validateOnInput: false,
      showSuccessMessages: true,
      submitCallback: null,
      ...options
    };

    this.fields = new Map();
    this.isSubmitting = false;

    this.init();
  }

  /**
   * Initialize form handler
   */
  init() {
    this.setupFields();
    this.setupEventListeners();
  }

  /**
   * Setup form fields
   */
  setupFields() {
    const inputs = DOM.selectAll('input, textarea, select', this.form);
    
    inputs.forEach(input => {
      const fieldConfig = {
        element: input,
        rules: this.getValidationRules(input),
        errorElement: null,
        successElement: null
      };

      // Create error message element
      const errorId = `${input.id || input.name}-error`;
      fieldConfig.errorElement = DOM.create('span', {
        id: errorId,
        className: 'form-error',
        'aria-live': 'polite'
      });

      // Create success message element
      if (this.options.showSuccessMessages) {
        const successId = `${input.id || input.name}-success`;
        fieldConfig.successElement = DOM.create('span', {
          id: successId,
          className: 'form-success',
          'aria-live': 'polite'
        });
      }

      // Insert error and success elements after the input
      const parent = input.parentElement;
      parent.appendChild(fieldConfig.errorElement);
      if (fieldConfig.successElement) {
        parent.appendChild(fieldConfig.successElement);
      }

      // Set ARIA attributes
      input.setAttribute('aria-describedby', errorId);
      if (fieldConfig.successElement) {
        input.setAttribute('aria-describedby', `${errorId} ${fieldConfig.successElement.id}`);
      }

      this.fields.set(input.name || input.id, fieldConfig);
    });
  }

  /**
   * Get validation rules for input
   * @param {Element} input - Input element
   * @returns {Array} - Array of validation rules
   */
  getValidationRules(input) {
    const rules = [];
    const type = input.type;
    const required = input.hasAttribute('required');

    // Required validation
    if (required) {
      rules.push({
        name: 'required',
        message: 'This field is required',
        validate: (value) => value.trim() !== ''
      });
    }

    // Type-specific validations
    switch (type) {
      case 'email':
        rules.push({
          name: 'email',
          message: 'Please enter a valid email address',
          validate: (value) => !value || Validation.isValidEmail(value)
        });
        break;

      case 'tel':
        rules.push({
          name: 'phone',
          message: 'Please enter a valid phone number',
          validate: (value) => !value || Validation.isValidPhone(value)
        });
        break;

      case 'password':
        rules.push({
          name: 'password',
          message: 'Password must be at least 8 characters with uppercase, lowercase, numbers, and special characters',
          validate: (value) => {
            if (!value) return true; // Let required rule handle empty values
            const result = Validation.validatePassword(value);
            return result.isValid;
          }
        });
        break;
    }

    // Custom validations based on data attributes or name
    if (input.name === 'student_id' || input.dataset.validation === 'student-id') {
      rules.push({
        name: 'student-id',
        message: 'Please enter a valid 12-digit student ID',
        validate: (value) => !value || Validation.isValidStudentId(value)
      });
    }

    if (input.name === 'confirm_password') {
      rules.push({
        name: 'password-match',
        message: 'Passwords do not match',
        validate: (value) => {
          const passwordField = DOM.select('input[name="password"]', this.form);
          return !value || !passwordField || value === passwordField.value;
        }
      });
    }

    // Min/max length validations
    if (input.minLength) {
      rules.push({
        name: 'minlength',
        message: `Must be at least ${input.minLength} characters`,
        validate: (value) => !value || value.length >= input.minLength
      });
    }

    if (input.maxLength) {
      rules.push({
        name: 'maxlength',
        message: `Must be no more than ${input.maxLength} characters`,
        validate: (value) => !value || value.length <= input.maxLength
      });
    }

    return rules;
  }

  /**
   * Setup event listeners
   */
  setupEventListeners() {
    // Form submission
    DOM.on(this.form, 'submit', (e) => {
      e.preventDefault();
      this.handleSubmit();
    });

    // Field validation on blur/input
    this.fields.forEach((fieldConfig, fieldName) => {
      const input = fieldConfig.element;

      if (this.options.validateOnBlur) {
        DOM.on(input, 'blur', () => {
          this.validateField(fieldName);
        });
      }

      if (this.options.validateOnInput) {
        DOM.on(input, 'input', () => {
          // Debounce input validation
          clearTimeout(input.validationTimeout);
          input.validationTimeout = setTimeout(() => {
            this.validateField(fieldName);
          }, 300);
        });
      }

      // Special handling for password confirmation
      if (input.name === 'confirm_password') {
        const passwordField = DOM.select('input[name="password"]', this.form);
        if (passwordField) {
          DOM.on(passwordField, 'input', () => {
            if (input.value) {
              this.validateField('confirm_password');
            }
          });
        }
      }
    });
  }

  /**
   * Validate a specific field
   * @param {string} fieldName - Field name to validate
   * @returns {boolean} - Validation result
   */
  validateField(fieldName) {
    const fieldConfig = this.fields.get(fieldName);
    if (!fieldConfig) return true;

    const input = fieldConfig.element;
    const value = input.value;
    const errors = [];

    // Run validation rules
    fieldConfig.rules.forEach(rule => {
      if (!rule.validate(value)) {
        errors.push(rule.message);
      }
    });

    // Update field state
    this.updateFieldState(fieldConfig, errors);

    return errors.length === 0;
  }

  /**
   * Update field visual state
   * @param {Object} fieldConfig - Field configuration
   * @param {Array} errors - Array of error messages
   */
  updateFieldState(fieldConfig, errors) {
    const input = fieldConfig.element;
    const errorElement = fieldConfig.errorElement;
    const successElement = fieldConfig.successElement;

    if (errors.length > 0) {
      // Error state
      DOM.addClass(input, 'error');
      DOM.removeClass(input, 'success');
      errorElement.textContent = errors[0]; // Show first error
      errorElement.style.display = 'block';
      
      if (successElement) {
        successElement.style.display = 'none';
      }

      input.setAttribute('aria-invalid', 'true');
    } else if (input.value.trim() !== '') {
      // Success state (only if field has value)
      DOM.removeClass(input, 'error');
      DOM.addClass(input, 'success');
      errorElement.style.display = 'none';
      
      if (successElement) {
        successElement.textContent = '✓ Valid';
        successElement.style.display = 'block';
      }

      input.setAttribute('aria-invalid', 'false');
    } else {
      // Neutral state
      DOM.removeClass(input, 'error');
      DOM.removeClass(input, 'success');
      errorElement.style.display = 'none';
      
      if (successElement) {
        successElement.style.display = 'none';
      }

      input.removeAttribute('aria-invalid');
    }
  }

  /**
   * Validate entire form
   * @returns {boolean} - Form validation result
   */
  validateForm() {
    let isValid = true;
    const firstErrorField = null;

    this.fields.forEach((fieldConfig, fieldName) => {
      const fieldValid = this.validateField(fieldName);
      if (!fieldValid) {
        isValid = false;
        if (!firstErrorField) {
          firstErrorField = fieldConfig.element;
        }
      }
    });

    // Focus first error field
    if (!isValid && firstErrorField) {
      firstErrorField.focus();
    }

    return isValid;
  }

  /**
   * Handle form submission
   */
  async handleSubmit() {
    if (this.isSubmitting) return;

    // Validate form
    const isValid = this.validateForm();
    if (!isValid) {
      A11y.announce('Please correct the errors in the form', 'assertive');
      return;
    }

    this.isSubmitting = true;
    this.setSubmitState(true);

    try {
      // Get form data
      const formData = new FormData(this.form);
      const data = Object.fromEntries(formData.entries());

      // Call custom submit callback if provided
      if (this.options.submitCallback) {
        await this.options.submitCallback(data, this.form);
      } else {
        // Default submission (you can customize this)
        await this.defaultSubmit(data);
      }

      // Success handling
      this.handleSubmitSuccess();
      
    } catch (error) {
      console.error('Form submission error:', error);
      this.handleSubmitError(error);
    } finally {
      this.isSubmitting = false;
      this.setSubmitState(false);
    }
  }

  /**
   * Default form submission
   * @param {Object} data - Form data
   */
  async defaultSubmit(data) {
    const response = await fetch(this.form.action || window.location.href, {
      method: this.form.method || 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
      },
      body: JSON.stringify(data)
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    return response.json();
  }

  /**
   * Handle successful form submission
   */
  handleSubmitSuccess() {
    // Show success message
    this.showMessage('Form submitted successfully!', 'success');
    
    // Reset form
    this.form.reset();
    this.clearValidationStates();
    
    // Announce success
    A11y.announce('Form submitted successfully', 'polite');
  }

  /**
   * Handle form submission error
   * @param {Error} error - Error object
   */
  handleSubmitError(error) {
    const message = error.message || 'An error occurred while submitting the form. Please try again.';
    this.showMessage(message, 'error');
    A11y.announce('Form submission failed. Please check for errors and try again.', 'assertive');
  }

  /**
   * Set submit button loading state
   * @param {boolean} loading - Loading state
   */
  setSubmitState(loading) {
    const submitBtn = DOM.select('button[type="submit"], input[type="submit"]', this.form);
    if (!submitBtn) return;

    if (loading) {
      submitBtn.disabled = true;
      DOM.addClass(submitBtn, 'loading');
      submitBtn.setAttribute('aria-busy', 'true');
    } else {
      submitBtn.disabled = false;
      DOM.removeClass(submitBtn, 'loading');
      submitBtn.setAttribute('aria-busy', 'false');
    }
  }

  /**
   * Show form message
   * @param {string} message - Message text
   * @param {string} type - Message type (success, error, warning, info)
   */
  showMessage(message, type = 'info') {
    // Remove existing messages
    const existingMessage = DOM.select('.form-message', this.form);
    if (existingMessage) {
      existingMessage.remove();
    }

    // Create new message
    const messageElement = DOM.create('div', {
      className: `form-message alert alert-${type}`,
      role: 'alert'
    }, message);

    // Insert at top of form
    this.form.insertBefore(messageElement, this.form.firstChild);

    // Auto-remove success messages after 5 seconds
    if (type === 'success') {
      setTimeout(() => {
        if (messageElement.parentElement) {
          messageElement.remove();
        }
      }, 5000);
    }
  }

  /**
   * Clear all validation states
   */
  clearValidationStates() {
    this.fields.forEach((fieldConfig) => {
      const input = fieldConfig.element;
      DOM.removeClass(input, 'error');
      DOM.removeClass(input, 'success');
      fieldConfig.errorElement.style.display = 'none';
      if (fieldConfig.successElement) {
        fieldConfig.successElement.style.display = 'none';
      }
      input.removeAttribute('aria-invalid');
    });
  }

  /**
   * Add custom validation rule to a field
   * @param {string} fieldName - Field name
   * @param {Object} rule - Validation rule object
   */
  addValidationRule(fieldName, rule) {
    const fieldConfig = this.fields.get(fieldName);
    if (fieldConfig) {
      fieldConfig.rules.push(rule);
    }
  }

  /**
   * Remove validation rule from a field
   * @param {string} fieldName - Field name
   * @param {string} ruleName - Rule name to remove
   */
  removeValidationRule(fieldName, ruleName) {
    const fieldConfig = this.fields.get(fieldName);
    if (fieldConfig) {
      fieldConfig.rules = fieldConfig.rules.filter(rule => rule.name !== ruleName);
    }
  }
}

// Auto-initialize forms when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  const forms = DOM.selectAll('form[data-validate]');
  forms.forEach(form => {
    new FormHandler(form);
  });
});

export default FormHandler;