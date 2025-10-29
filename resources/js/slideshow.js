/**
 * Hero Slideshow Component
 * Handles the hero slideshow with accessibility and performance optimizations
 */

class HeroSlideshow {
    constructor(container) {
        this.container = container;
        this.slides = [];
        this.currentSlide = 0;
        this.isPlaying = true;
        this.interval = null;
        this.intervalDuration = 5000; // 5 seconds
        
        this.init();
    }

    init() {
        this.createSlideshow();
        this.setupControls();
        this.setupKeyboardNavigation();
        this.setupAccessibility();
        this.startAutoplay();
        
        // Handle reduced motion preference
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.pauseAutoplay();
        }
    }

    createSlideshow() {
        // Slideshow data - in a real app, this would come from a CMS or API
        const slidesData = [
            {
                image: '/assets/images/hero/campus-1.jpg',
                title: 'Supporting Your Academic Journey',
                subtitle: 'UniKL RCMP Student Welfare Fund provides essential financial assistance to help you succeed in your medical education.',
                cta: {
                    primary: { text: 'Apply for Financial Aid', href: '#application' },
                    secondary: { text: 'Learn More', href: '#about' }
                }
            },
            {
                image: '/assets/images/hero/students-1.jpg',
                title: 'Emergency Financial Support',
                subtitle: 'Get help when you need it most. Our fund covers medical emergencies, bereavement, and critical situations.',
                cta: {
                    primary: { text: 'View Fund Categories', href: '#about' },
                    secondary: { text: 'Contact Us', href: '#contact' }
                }
            },
            {
                image: '/assets/images/hero/campus-2.jpg',
                title: 'Simple Application Process',
                subtitle: 'Apply online in just 5 easy steps. Our streamlined process ensures quick review and disbursement.',
                cta: {
                    primary: { text: 'Start Application', href: '/register' },
                    secondary: { text: 'View Requirements', href: '#application' }
                }
            },
            {
                image: '/assets/images/hero/graduation-1.jpg',
                title: 'Community Support',
                subtitle: 'Join a supportive community where students help students through the Student Welfare Fund contributions.',
                cta: {
                    primary: { text: 'Join Our Community', href: '/register' },
                    secondary: { text: 'Learn About SWF', href: '#about' }
                }
            }
        ];

        // Create slideshow HTML
        const slideshowHTML = `
            <div class="slideshow-wrapper">
                <div class="slideshow-track" role="region" aria-label="Hero slideshow" aria-live="polite">
                    ${slidesData.map((slide, index) => this.createSlideHTML(slide, index)).join('')}
                </div>
                
                <!-- Slideshow Controls -->
                <button class="slideshow-control slideshow-prev" aria-label="Previous slide" tabindex="0">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15,18 9,12 15,6"></polyline>
                    </svg>
                </button>
                
                <button class="slideshow-control slideshow-next" aria-label="Next slide" tabindex="0">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9,18 15,12 9,6"></polyline>
                    </svg>
                </button>
                
                <!-- Play/Pause Button -->
                <button class="slideshow-play-pause" aria-label="Pause slideshow" tabindex="0">
                    <svg class="play-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                        <polygon points="5,3 19,12 5,21"></polygon>
                    </svg>
                    <svg class="pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="6" y="4" width="4" height="16"></rect>
                        <rect x="14" y="4" width="4" height="16"></rect>
                    </svg>
                </button>
                
                <!-- Slide Indicators -->
                <div class="slideshow-indicators" role="tablist" aria-label="Slide navigation">
                    ${slidesData.map((_, index) => `
                        <button class="slideshow-indicator ${index === 0 ? 'active' : ''}" 
                                role="tab" 
                                aria-selected="${index === 0 ? 'true' : 'false'}"
                                aria-label="Go to slide ${index + 1}"
                                data-slide="${index}"
                                tabindex="0">
                        </button>
                    `).join('')}
                </div>
            </div>
        `;

        this.container.innerHTML = slideshowHTML;
        this.slides = this.container.querySelectorAll('.slideshow-slide');
        
        // Set first slide as active
        if (this.slides.length > 0) {
            this.slides[0].classList.add('active');
        }
    }

    createSlideHTML(slide, index) {
        return `
            <div class="slideshow-slide ${index === 0 ? 'active' : ''}" 
                 role="tabpanel" 
                 aria-label="Slide ${index + 1} of 4"
                 data-slide="${index}">
                <img src="${slide.image}" 
                     alt="${slide.title}" 
                     class="slideshow-image ${index > 0 ? 'lazy-image' : ''}"
                     ${index > 0 ? `data-src="${slide.image}"` : ''}
                     loading="${index === 0 ? 'eager' : 'lazy'}"
                     width="1920" 
                     height="1080">
                
                <div class="slideshow-overlay">
                    <div class="hero-content">
                        <h1 class="hero-title">${slide.title}</h1>
                        <p class="hero-subtitle">${slide.subtitle}</p>
                        <div class="hero-actions">
                            <a href="${slide.cta.primary.href}" class="btn btn-primary btn-large">
                                ${slide.cta.primary.text}
                            </a>
                            <a href="${slide.cta.secondary.href}" class="btn btn-secondary btn-large">
                                ${slide.cta.secondary.text}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    setupControls() {
        const prevBtn = this.container.querySelector('.slideshow-prev');
        const nextBtn = this.container.querySelector('.slideshow-next');
        const playPauseBtn = this.container.querySelector('.slideshow-play-pause');
        const indicators = this.container.querySelectorAll('.slideshow-indicator');

        // Previous button
        prevBtn?.addEventListener('click', () => {
            this.previousSlide();
        });

        // Next button
        nextBtn?.addEventListener('click', () => {
            this.nextSlide();
        });

        // Play/Pause button
        playPauseBtn?.addEventListener('click', () => {
            this.togglePlayPause();
        });

        // Indicator buttons
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                this.goToSlide(index);
            });
        });

        // Pause on hover
        this.container.addEventListener('mouseenter', () => {
            this.pauseAutoplay();
        });

        this.container.addEventListener('mouseleave', () => {
            if (this.isPlaying) {
                this.startAutoplay();
            }
        });
    }

    setupKeyboardNavigation() {
        this.container.addEventListener('keydown', (e) => {
            switch (e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    this.previousSlide();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    this.nextSlide();
                    break;
                case ' ':
                case 'Enter':
                    if (e.target.classList.contains('slideshow-indicator')) {
                        e.preventDefault();
                        const slideIndex = parseInt(e.target.dataset.slide);
                        this.goToSlide(slideIndex);
                    }
                    break;
                case 'Home':
                    e.preventDefault();
                    this.goToSlide(0);
                    break;
                case 'End':
                    e.preventDefault();
                    this.goToSlide(this.slides.length - 1);
                    break;
            }
        });
    }

    setupAccessibility() {
        // Add screen reader announcements
        const liveRegion = document.createElement('div');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        liveRegion.id = 'slideshow-announcements';
        this.container.appendChild(liveRegion);

        // Update ARIA attributes when slide changes
        this.container.addEventListener('slideChanged', (e) => {
            const { currentSlide, totalSlides } = e.detail;
            const slideTitle = this.slides[currentSlide].querySelector('.hero-title').textContent;
            
            liveRegion.textContent = `Slide ${currentSlide + 1} of ${totalSlides}: ${slideTitle}`;
            
            // Update indicators
            const indicators = this.container.querySelectorAll('.slideshow-indicator');
            indicators.forEach((indicator, index) => {
                indicator.setAttribute('aria-selected', index === currentSlide ? 'true' : 'false');
                indicator.classList.toggle('active', index === currentSlide);
            });
        });
    }

    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(nextIndex);
    }

    previousSlide() {
        const prevIndex = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prevIndex);
    }

    goToSlide(index) {
        if (index === this.currentSlide || index < 0 || index >= this.slides.length) {
            return;
        }

        // Remove active class from current slide
        this.slides[this.currentSlide].classList.remove('active');
        
        // Add active class to new slide
        this.slides[index].classList.add('active');
        
        // Load lazy image if needed
        const newSlideImg = this.slides[index].querySelector('.lazy-image');
        if (newSlideImg && window.lazyLoader) {
            window.lazyLoader.loadImageNow(newSlideImg);
        }

        this.currentSlide = index;

        // Dispatch custom event
        this.container.dispatchEvent(new CustomEvent('slideChanged', {
            detail: {
                currentSlide: this.currentSlide,
                totalSlides: this.slides.length
            }
        }));

        // Restart autoplay if playing
        if (this.isPlaying) {
            this.startAutoplay();
        }
    }

    startAutoplay() {
        this.clearAutoplay();
        this.interval = setInterval(() => {
            this.nextSlide();
        }, this.intervalDuration);
    }

    pauseAutoplay() {
        this.clearAutoplay();
    }

    clearAutoplay() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    }

    togglePlayPause() {
        const playPauseBtn = this.container.querySelector('.slideshow-play-pause');
        const playIcon = playPauseBtn.querySelector('.play-icon');
        const pauseIcon = playPauseBtn.querySelector('.pause-icon');

        if (this.isPlaying) {
            this.isPlaying = false;
            this.pauseAutoplay();
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
            playPauseBtn.setAttribute('aria-label', 'Play slideshow');
        } else {
            this.isPlaying = true;
            this.startAutoplay();
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'block';
            playPauseBtn.setAttribute('aria-label', 'Pause slideshow');
        }
    }

    destroy() {
        this.clearAutoplay();
        // Remove event listeners and clean up
        this.container.innerHTML = '';
    }
}

// Initialize slideshow when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const slideshowContainer = document.querySelector('.hero-slideshow');
    if (slideshowContainer) {
        window.heroSlideshow = new HeroSlideshow(slideshowContainer);
    }
});

export default HeroSlideshow;