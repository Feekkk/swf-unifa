// Navigation component functionality
import { DOM, Animation, throttle } from './utils.js';

/**
 * Navigation Component Class
 */
class Navigation {
  constructor() {
    this.header = DOM.select('.header');
    this.navToggle = DOM.select('.nav-toggle');
    this.navMenu = DOM.select('.nav-menu');
    this.navLinks = DOM.selectAll('.nav-link');
    this.isMenuOpen = false;
    
    this.init();
  }

  /**
   * Initialize navigation functionality
   */
  init() {
    this.setupScrollEffects();
    this.setupMobileMenu();
    this.setupSmoothScroll();
    this.setupActiveLinks();
  }

  /**
   * Setup scroll effects for header
   */
  setupScrollEffects() {
    const handleScroll = throttle(() => {
      const scrollY = window.scrollY;
      
      if (scrollY > 50) {
        DOM.addClass(this.header, 'scrolled');
      } else {
        DOM.removeClass(this.header, 'scrolled');
      }
    }, 16); // ~60fps

    window.addEventListener('scroll', handleScroll);
  }

  /**
   * Setup mobile menu functionality
   */
  setupMobileMenu() {
    if (!this.navToggle || !this.navMenu) return;

    // Toggle menu on button click
    DOM.on(this.navToggle, 'click', (e) => {
      e.preventDefault();
      this.toggleMobileMenu();
    });

    // Close menu when clicking nav links
    this.navLinks.forEach(link => {
      DOM.on(link, 'click', () => {
        if (this.isMenuOpen) {
          this.closeMobileMenu();
        }
      });
    });

    // Close menu on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isMenuOpen) {
        this.closeMobileMenu();
      }
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
      if (this.isMenuOpen && 
          !this.navMenu.contains(e.target) && 
          !this.navToggle.contains(e.target)) {
        this.closeMobileMenu();
      }
    });
  }

  /**
   * Toggle mobile menu
   */
  toggleMobileMenu() {
    this.isMenuOpen = !this.isMenuOpen;
    
    if (this.isMenuOpen) {
      this.openMobileMenu();
    } else {
      this.closeMobileMenu();
    }
  }

  /**
   * Open mobile menu
   */
  openMobileMenu() {
    DOM.addClass(this.navMenu, 'open');
    this.navToggle.setAttribute('aria-expanded', 'true');
    this.navToggle.innerHTML = '✕'; // Close icon
    this.isMenuOpen = true;
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
    
    // Focus first menu item
    const firstLink = DOM.select('.nav-link', this.navMenu);
    if (firstLink) {
      firstLink.focus();
    }
  }

  /**
   * Close mobile menu
   */
  closeMobileMenu() {
    DOM.removeClass(this.navMenu, 'open');
    this.navToggle.setAttribute('aria-expanded', 'false');
    this.navToggle.innerHTML = '☰'; // Hamburger icon
    this.isMenuOpen = false;
    
    // Restore body scroll
    document.body.style.overflow = '';
    
    // Return focus to toggle button
    this.navToggle.focus();
  }

  /**
   * Setup smooth scroll for navigation links
   */
  setupSmoothScroll() {
    this.navLinks.forEach(link => {
      DOM.on(link, 'click', (e) => {
        const href = link.getAttribute('href');
        
        // Only handle internal links (starting with #)
        if (href && href.startsWith('#')) {
          e.preventDefault();
          
          const targetId = href.substring(1);
          const targetElement = DOM.select(`#${targetId}`);
          
          if (targetElement) {
            Animation.scrollTo(targetElement);
            
            // Update URL without triggering scroll
            history.pushState(null, null, href);
          }
        }
      });
    });
  }

  /**
   * Setup active link highlighting based on scroll position
   */
  setupActiveLinks() {
    const sections = [];
    
    // Collect all sections that have corresponding nav links
    this.navLinks.forEach(link => {
      const href = link.getAttribute('href');
      if (href && href.startsWith('#')) {
        const targetId = href.substring(1);
        const section = DOM.select(`#${targetId}`);
        if (section) {
          sections.push({
            element: section,
            link: link,
            id: targetId
          });
        }
      }
    });

    if (sections.length === 0) return;

    const updateActiveLink = throttle(() => {
      const scrollY = window.scrollY;
      const windowHeight = window.innerHeight;
      
      let activeSection = null;
      
      sections.forEach(section => {
        const rect = section.element.getBoundingClientRect();
        const sectionTop = rect.top + scrollY;
        const sectionHeight = rect.height;
        
        // Check if section is in viewport
        if (scrollY >= sectionTop - 100 && 
            scrollY < sectionTop + sectionHeight - 100) {
          activeSection = section;
        }
      });
      
      // Update active states
      sections.forEach(section => {
        if (section === activeSection) {
          DOM.addClass(section.link, 'active');
        } else {
          DOM.removeClass(section.link, 'active');
        }
      });
    }, 16);

    window.addEventListener('scroll', updateActiveLink);
    
    // Initial check
    updateActiveLink();
  }

  /**
   * Add navigation item programmatically
   * @param {string} text - Link text
   * @param {string} href - Link href
   * @param {number} position - Position to insert (optional)
   */
  addNavItem(text, href, position = null) {
    const navItem = DOM.create('li', { className: 'nav-item' });
    const navLink = DOM.create('a', {
      className: 'nav-link',
      href: href
    }, text);
    
    navItem.appendChild(navLink);
    
    if (position !== null && position < this.navMenu.children.length) {
      this.navMenu.insertBefore(navItem, this.navMenu.children[position]);
    } else {
      this.navMenu.appendChild(navItem);
    }
    
    // Re-setup smooth scroll for new link
    this.setupSmoothScroll();
  }

  /**
   * Remove navigation item
   * @param {string} href - Link href to remove
   */
  removeNavItem(href) {
    const link = DOM.select(`a[href="${href}"]`, this.navMenu);
    if (link && link.parentElement) {
      link.parentElement.remove();
    }
  }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  window.navigation = new Navigation();
});

export default Navigation;