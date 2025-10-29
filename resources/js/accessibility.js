/**
 * Accessibility Enhancement Module
 * Provides keyboard navigation, screen reader support, and ARIA enhancements
 */

class AccessibilityManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupKeyboardNavigation();
        this.setupFocusManagement();
        this.setupScreenReaderSupport();
        this.setupSkipLinks();
        this.setupAriaLiveRegions();
        this.setupReducedMotion();
    }

    /**
     * Enhanced keyboard navigation for all interactive elements
     */
    setupKeyboardNavigation() {
        // Handle navigation menu keyboard interaction
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');
        
        if (navToggle && navMenu) {
            navToggle.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggleMobileMenu();
                }
            });

            // Handle escape key to close mobile menu
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                    this.closeMobileMenu();
                    navToggle.focus();
                }
            });
        }

        // Enhanced form navigation
        this.setupFormKeyboardNavigation();
        
        // Card keyboard navigation
        this.setupCardKeyboardNavigation();
        
        // Timeline keyboard navigation
        this.setupTimelineKeyboardNavigation();
    }

    /**
     * Setup keyboard navigation for forms
     */
    setupFormKeyboardNavigation() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select, textarea, button');
            
            inputs.forEach((input, index) => {
                input.addEventListener('keydown', (e) => {
                    // Tab navigation enhancement
                    if (e.key === 'Tab') {
                        // Let default behavior handle tab navigation
                        return;
                    }
                    
                    // Enter key navigation between form fields
                    if (e.key === 'Enter' && input.type !== 'submit' && input.type !== 'button') {
                        e.preventDefault();
                        const nextInput = inputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }
                });
            });
        });
    }

    /**
     * Setup keyboard navigation for interactive cards
     */
    setupCardKeyboardNavigation() {
        const cards = document.querySelectorAll('.card-clickable, .card[tabindex]');
        
        cards.forEach(card => {
            if (!card.hasAttribute('tabindex')) {
                card.setAttribute('tabindex', '0');
            }
            
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    card.click();
                }
            });
        });
    }

    /**
     * Setup keyboard navigation for timeline
     */
    setupTimelineKeyboardNavigation() {
        const timeline = document.querySelector('.timeline');
        if (!timeline) return;

        const timelineItems = timeline.querySelectorAll('.timeline-item');
        
        timelineItems.forEach((item, index) => {
            const content = item.querySelector('.timeline-content');
            if (content && !content.hasAttribute('tabindex')) {
                content.setAttribute('tabindex', '0');
                content.setAttribute('role', 'listitem');
                content.setAttribute('aria-label', `Step ${index + 1} of ${timelineItems.length}`);
            }
        });
    }

    /**
     * Focus management for dynamic content
     */
    setupFocusManagement() {
        // Focus trap for mobile menu
        this.setupMobileMenuFocusTrap();
        
        // Focus management for slideshow
        this.setupSlideshowFocusManagement();
        
        // Focus indicators
        this.setupFocusIndicators();
    }

    /**
     * Focus trap for mobile navigation menu
     */
    setupMobileMenuFocusTrap() {
        const navMenu = document.querySelector('.nav-menu');
        const navToggle = document.querySelector('.nav-toggle');
        
        if (!navMenu || !navToggle) return;

        document.addEventListener('keydown', (e) => {
            if (!navMenu.classList.contains('active')) return;
            
            const focusableElements = navMenu.querySelectorAll(
                'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );
            
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        });
    }

    /**
     * Focus management for slideshow controls
     */
    setupSlideshowFocusManagement() {
        const slideshow = document.querySelector('.hero-slideshow');
        if (!slideshow) return;

        // Add keyboard navigation for slideshow controls
        const prevBtn = slideshow.querySelector('.slideshow-prev');
        const nextBtn = slideshow.querySelector('.slideshow-next');
        const indicators = slideshow.querySelectorAll('.slideshow-indicator');
        const playPauseBtn = slideshow.querySelector('.slideshow-play-pause');

        // Ensure all controls are keyboard accessible
        [prevBtn, nextBtn, playPauseBtn].forEach(btn => {
            if (btn && !btn.hasAttribute('tabindex')) {
                btn.setAttribute('tabindex', '0');
            }
        });

        indicators.forEach((indicator, index) => {
            if (!indicator.hasAttribute('tabindex')) {
                indicator.setAttribute('tabindex', '0');
            }
            indicator.setAttribute('aria-label', `Go to slide ${index + 1}`);
        });
    }

    /**
     * Enhanced focus indicators
     */
    setupFocusIndicators() {
        // Add focus-visible polyfill behavior
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });
    }

    /**
     * Screen reader support enhancements
     */
    setupScreenReaderSupport() {
        // Add screen reader only content
        this.addScreenReaderContent();
        
        // Enhance form labels and descriptions
        this.enhanceFormAccessibility();
        
        // Add status announcements
        this.setupStatusAnnouncements();
        
        // Enhance navigation landmarks
        this.enhanceNavigationLandmarks();
    }

    /**
     * Add screen reader only content for better context
     */
    addScreenReaderContent() {
        // Add context to navigation links
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            if (link.getAttribute('href').startsWith('#')) {
                const srText = document.createElement('span');
                srText.className = 'sr-only';
                srText.textContent = ' (navigate to section)';
                link.appendChild(srText);
            }
        });

        // Add context to external links
        const externalLinks = document.querySelectorAll('a[target="_blank"]');
        externalLinks.forEach(link => {
            if (!link.querySelector('.sr-only')) {
                const srText = document.createElement('span');
                srText.className = 'sr-only';
                srText.textContent = ' (opens in new window)';
                link.appendChild(srText);
            }
        });

        // Add context to form buttons
        const submitButtons = document.querySelectorAll('button[type="submit"]');
        submitButtons.forEach(button => {
            if (!button.getAttribute('aria-describedby')) {
                const form = button.closest('form');
                if (form) {
                    const formName = form.getAttribute('name') || 'form';
                    button.setAttribute('aria-label', `Submit ${formName}`);
                }
            }
        });
    }

    /**
     * Enhance form accessibility
     */
    enhanceFormAccessibility() {
        // Ensure all form inputs have proper labels
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const label = document.querySelector(`label[for="${input.id}"]`);
            if (!label && input.id) {
                console.warn(`Input with id "${input.id}" is missing a label`);
            }
            
            // Add required indicator to screen readers
            if (input.hasAttribute('required')) {
                input.setAttribute('aria-required', 'true');
            }
            
            // Enhance error messages
            const errorElement = document.querySelector(`#${input.id}-error, .form-error[id*="${input.id}"]`);
            if (errorElement) {
                input.setAttribute('aria-describedby', errorElement.id);
                input.setAttribute('aria-invalid', 'true');
            }
        });

        // Add fieldset and legend for grouped form elements
        this.addFieldsetToFormGroups();
    }

    /**
     * Add fieldset and legend to related form groups
     */
    addFieldsetToFormGroups() {
        // Group related form fields (like address fields)
        const addressFields = document.querySelectorAll('input[name*="address"], input[name*="city"], input[name*="state"], input[name*="postal"]');
        if (addressFields.length > 1) {
            this.wrapInFieldset(addressFields, 'Address Information');
        }

        // Group contact fields
        const contactFields = document.querySelectorAll('input[name*="phone"], input[name*="email"]');
        if (contactFields.length > 1) {
            this.wrapInFieldset(contactFields, 'Contact Information');
        }
    }

    /**
     * Wrap related fields in fieldset with legend
     */
    wrapInFieldset(fields, legendText) {
        if (fields.length === 0) return;

        const firstField = fields[0];
        const parent = firstField.closest('.form-group').parentNode;
        
        // Check if already wrapped in fieldset
        if (parent.tagName === 'FIELDSET') return;

        const fieldset = document.createElement('fieldset');
        const legend = document.createElement('legend');
        legend.textContent = legendText;
        legend.className = 'sr-only';
        
        fieldset.appendChild(legend);
        
        // Move all related form groups into fieldset
        fields.forEach(field => {
            const formGroup = field.closest('.form-group');
            if (formGroup) {
                fieldset.appendChild(formGroup);
            }
        });
        
        parent.appendChild(fieldset);
    }

    /**
     * Setup status announcements for dynamic content
     */
    setupStatusAnnouncements() {
        // Create live region for announcements
        if (!document.getElementById('aria-live-region')) {
            const liveRegion = document.createElement('div');
            liveRegion.id = 'aria-live-region';
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.className = 'sr-only';
            document.body.appendChild(liveRegion);
        }

        // Announce form submission status
        this.setupFormStatusAnnouncements();
        
        // Announce slideshow changes
        this.setupSlideshowAnnouncements();
    }

    /**
     * Setup form status announcements
     */
    setupFormStatusAnnouncements() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', () => {
                this.announce('Form submitted. Please wait for processing.');
            });

            // Announce validation errors
            const errorElements = form.querySelectorAll('.form-error');
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        const errorText = mutation.target.textContent;
                        if (errorText && mutation.target.classList.contains('form-error')) {
                            this.announce(`Error: ${errorText}`);
                        }
                    }
                });
            });

            errorElements.forEach(errorElement => {
                observer.observe(errorElement, { childList: true, subtree: true });
            });
        });
    }

    /**
     * Setup slideshow announcements
     */
    setupSlideshowAnnouncements() {
        const slideshow = document.querySelector('.hero-slideshow');
        if (!slideshow) return;

        // Announce slide changes
        const slides = slideshow.querySelectorAll('.slideshow-slide');
        if (slides.length > 0) {
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        const slide = mutation.target;
                        if (slide.classList.contains('active')) {
                            const slideIndex = Array.from(slides).indexOf(slide) + 1;
                            const slideTitle = slide.querySelector('h1, h2, .hero-title')?.textContent || `Slide ${slideIndex}`;
                            this.announce(`${slideTitle}. Slide ${slideIndex} of ${slides.length}`);
                        }
                    }
                });
            });

            slides.forEach(slide => {
                observer.observe(slide, { attributes: true });
            });
        }
    }

    /**
     * Announce message to screen readers
     */
    announce(message) {
        const liveRegion = document.getElementById('aria-live-region');
        if (liveRegion) {
            liveRegion.textContent = message;
            
            // Clear after announcement
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        }
    }

    /**
     * Setup skip links functionality
     */
    setupSkipLinks() {
        const skipLinks = document.querySelectorAll('.skip-link');
        
        skipLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                
                if (target) {
                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth' });
                    
                    // Ensure target is focusable
                    if (!target.hasAttribute('tabindex')) {
                        target.setAttribute('tabindex', '-1');
                    }
                }
            });
        });
    }

    /**
     * Setup ARIA live regions for dynamic content
     */
    setupAriaLiveRegions() {
        // Add live region for form validation messages
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            if (!form.querySelector('[aria-live]')) {
                const liveRegion = document.createElement('div');
                liveRegion.setAttribute('aria-live', 'assertive');
                liveRegion.setAttribute('aria-atomic', 'true');
                liveRegion.className = 'sr-only';
                liveRegion.id = `${form.id || 'form'}-live-region`;
                form.appendChild(liveRegion);
            }
        });
    }

    /**
     * Enhance navigation landmarks
     */
    enhanceNavigationLandmarks() {
        // Ensure main navigation has proper role
        const mainNav = document.querySelector('nav[role="navigation"]');
        if (mainNav && !mainNav.getAttribute('aria-label')) {
            mainNav.setAttribute('aria-label', 'Main navigation');
        }

        // Add landmark roles where missing
        const header = document.querySelector('header');
        if (header && !header.getAttribute('role')) {
            header.setAttribute('role', 'banner');
        }

        const main = document.querySelector('main');
        if (main && !main.getAttribute('role')) {
            main.setAttribute('role', 'main');
        }

        const footer = document.querySelector('footer');
        if (footer && !footer.getAttribute('role')) {
            footer.setAttribute('role', 'contentinfo');
        }

        // Add navigation role to footer navigation
        const footerNav = footer?.querySelector('.footer-navigation');
        if (footerNav) {
            footerNav.setAttribute('role', 'navigation');
            footerNav.setAttribute('aria-label', 'Footer navigation');
        }
    }

    /**
     * Setup reduced motion preferences
     */
    setupReducedMotion() {
        // Respect user's motion preferences
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        
        if (prefersReducedMotion.matches) {
            document.body.classList.add('reduce-motion');
        }

        prefersReducedMotion.addEventListener('change', (e) => {
            if (e.matches) {
                document.body.classList.add('reduce-motion');
            } else {
                document.body.classList.remove('reduce-motion');
            }
        });
    }

    /**
     * Mobile menu toggle with accessibility
     */
    toggleMobileMenu() {
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');
        
        if (!navToggle || !navMenu) return;

        const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
        
        navToggle.setAttribute('aria-expanded', !isExpanded);
        navMenu.classList.toggle('active');
        
        if (!isExpanded) {
            // Focus first menu item when opening
            const firstMenuItem = navMenu.querySelector('a, button');
            if (firstMenuItem) {
                firstMenuItem.focus();
            }
        }
        
        this.announce(isExpanded ? 'Menu closed' : 'Menu opened');
    }

    /**
     * Close mobile menu
     */
    closeMobileMenu() {
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');
        
        if (!navToggle || !navMenu) return;

        navToggle.setAttribute('aria-expanded', 'false');
        navMenu.classList.remove('active');
        this.announce('Menu closed');
    }
}

// Initialize accessibility manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new AccessibilityManager();
});

// Export for use in other modules
export default AccessibilityManager;