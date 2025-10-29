/**
 * Lazy Loading Module
 * Handles lazy loading of images and other resources for performance optimization
 */

class LazyLoader {
    constructor() {
        this.imageObserver = null;
        this.iframeObserver = null;
        this.init();
    }

    init() {
        // Check for Intersection Observer support
        if ('IntersectionObserver' in window) {
            this.setupIntersectionObserver();
        } else {
            // Fallback for older browsers
            this.setupScrollListener();
        }

        // Setup WebP detection
        this.detectWebPSupport();
        
        // Setup responsive images
        this.setupResponsiveImages();
        
        // Setup lazy loading for existing images
        this.setupLazyImages();
        
        // Setup lazy loading for iframes
        this.setupLazyIframes();
    }

    /**
     * Setup Intersection Observer for modern browsers
     */
    setupIntersectionObserver() {
        const imageOptions = {
            root: null,
            rootMargin: '50px 0px',
            threshold: 0.01
        };

        // Observer for images
        this.imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    this.imageObserver.unobserve(entry.target);
                }
            });
        }, imageOptions);

        // Observer for iframes
        this.iframeObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadIframe(entry.target);
                    this.iframeObserver.unobserve(entry.target);
                }
            });
        }, imageOptions);

        // Observe all lazy elements
        this.observeLazyElements();
    }

    /**
     * Fallback scroll listener for older browsers
     */
    setupScrollListener() {
        let ticking = false;

        const checkLazyElements = () => {
            const lazyImages = document.querySelectorAll('.lazy-image[data-src]');
            const lazyIframes = document.querySelectorAll('.lazy-iframe[data-src]');

            [...lazyImages, ...lazyIframes].forEach(element => {
                if (this.isInViewport(element)) {
                    if (element.classList.contains('lazy-image')) {
                        this.loadImage(element);
                    } else if (element.classList.contains('lazy-iframe')) {
                        this.loadIframe(element);
                    }
                }
            });

            ticking = false;
        };

        const onScroll = () => {
            if (!ticking) {
                requestAnimationFrame(checkLazyElements);
                ticking = true;
            }
        };

        window.addEventListener('scroll', onScroll);
        window.addEventListener('resize', onScroll);
        
        // Initial check
        checkLazyElements();
    }

    /**
     * Check if element is in viewport (fallback method)
     */
    isInViewport(element) {
        const rect = element.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        const windowWidth = window.innerWidth || document.documentElement.clientWidth;

        return (
            rect.top >= -50 &&
            rect.left >= -50 &&
            rect.bottom <= windowHeight + 50 &&
            rect.right <= windowWidth + 50
        );
    }

    /**
     * Observe all lazy elements
     */
    observeLazyElements() {
        // Observe lazy images
        const lazyImages = document.querySelectorAll('.lazy-image[data-src]');
        lazyImages.forEach(img => {
            this.imageObserver.observe(img);
        });

        // Observe lazy iframes
        const lazyIframes = document.querySelectorAll('.lazy-iframe[data-src]');
        lazyIframes.forEach(iframe => {
            this.iframeObserver.observe(iframe);
        });
    }

    /**
     * Load lazy image
     */
    loadImage(img) {
        const src = img.dataset.src;
        const srcset = img.dataset.srcset;

        if (!src) return;

        // Create new image to preload
        const imageLoader = new Image();
        
        imageLoader.onload = () => {
            // Image loaded successfully
            img.src = src;
            if (srcset) {
                img.srcset = srcset;
            }
            
            img.classList.remove('lazy-image');
            img.classList.add('lazy-loaded');
            
            // Remove data attributes
            delete img.dataset.src;
            delete img.dataset.srcset;
            
            // Trigger custom event
            img.dispatchEvent(new CustomEvent('lazyloaded', {
                bubbles: true,
                detail: { element: img }
            }));
        };

        imageLoader.onerror = () => {
            // Handle error
            img.classList.add('lazy-error');
            console.warn('Failed to load lazy image:', src);
        };

        // Start loading
        imageLoader.src = src;
        if (srcset) {
            imageLoader.srcset = srcset;
        }
    }

    /**
     * Load lazy iframe
     */
    loadIframe(iframe) {
        const src = iframe.dataset.src;
        if (!src) return;

        iframe.src = src;
        iframe.classList.remove('lazy-iframe');
        iframe.classList.add('lazy-loaded');
        
        delete iframe.dataset.src;
        
        // Trigger custom event
        iframe.dispatchEvent(new CustomEvent('lazyloaded', {
            bubbles: true,
            detail: { element: iframe }
        }));
    }

    /**
     * Detect WebP support
     */
    detectWebPSupport() {
        const webpTestImages = {
            lossy: 'data:image/webp;base64,UklGRiIAAABXRUJQVlA4IBYAAAAwAQCdASoBAAEADsD+JaQAA3AAAAAA',
            lossless: 'data:image/webp;base64,UklGRhoAAABXRUJQVlA4TA0AAAAvAAAAEAcQERGIiP4HAA==',
            alpha: 'data:image/webp;base64,UklGRkoAAABXRUJQVlA4WAoAAAAQAAAAAAAAAAAAQUxQSAwAAAARBxAR/Q9ERP8DAABWUDggGAAAABQBAJ0BKgEAAQAAAP4AAA3AAP7mtQAAAA=='
        };

        const checkWebPSupport = (type, dataUri) => {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = () => resolve(img.width === 1 && img.height === 1);
                img.onerror = () => resolve(false);
                img.src = dataUri;
            });
        };

        Promise.all([
            checkWebPSupport('lossy', webpTestImages.lossy),
            checkWebPSupport('lossless', webpTestImages.lossless),
            checkWebPSupport('alpha', webpTestImages.alpha)
        ]).then(([lossy, lossless, alpha]) => {
            const webpSupport = {
                lossy,
                lossless,
                alpha,
                supported: lossy || lossless || alpha
            };

            // Store WebP support info
            window.webpSupport = webpSupport;
            
            // Add class to document for CSS targeting
            if (webpSupport.supported) {
                document.documentElement.classList.add('webp-supported');
            } else {
                document.documentElement.classList.add('webp-not-supported');
            }

            // Update picture sources if WebP is supported
            this.updatePictureSources();
        });
    }

    /**
     * Update picture sources based on WebP support
     */
    updatePictureSources() {
        if (!window.webpSupport?.supported) return;

        const pictures = document.querySelectorAll('picture');
        pictures.forEach(picture => {
            const webpSource = picture.querySelector('source[type="image/webp"]');
            const img = picture.querySelector('img');
            
            if (webpSource && img && webpSource.dataset.srcset) {
                // Use WebP source if supported
                if (img.classList.contains('lazy-image')) {
                    img.dataset.srcset = webpSource.dataset.srcset;
                } else {
                    img.srcset = webpSource.dataset.srcset;
                }
            }
        });
    }

    /**
     * Setup responsive images
     */
    setupResponsiveImages() {
        const responsiveImages = document.querySelectorAll('img[data-sizes]');
        
        responsiveImages.forEach(img => {
            const sizes = img.dataset.sizes;
            if (sizes) {
                img.sizes = sizes;
            }
        });
    }

    /**
     * Setup lazy loading for existing images
     */
    setupLazyImages() {
        // Convert regular images to lazy loading if they have the class
        const images = document.querySelectorAll('img.lazy-image:not([data-src])');
        
        images.forEach(img => {
            if (img.src && !img.dataset.src) {
                img.dataset.src = img.src;
                img.src = this.generatePlaceholder(img);
                
                if (this.imageObserver) {
                    this.imageObserver.observe(img);
                }
            }
        });
    }

    /**
     * Setup lazy loading for iframes
     */
    setupLazyIframes() {
        const iframes = document.querySelectorAll('iframe.lazy-iframe:not([data-src])');
        
        iframes.forEach(iframe => {
            if (iframe.src && !iframe.dataset.src) {
                iframe.dataset.src = iframe.src;
                iframe.removeAttribute('src');
                
                if (this.iframeObserver) {
                    this.iframeObserver.observe(iframe);
                }
            }
        });
    }

    /**
     * Generate placeholder for image
     */
    generatePlaceholder(img) {
        const width = img.width || img.getAttribute('width') || 300;
        const height = img.height || img.getAttribute('height') || 200;
        
        const svg = `
            <svg width="${width}" height="${height}" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="#f0f0f0"/>
                <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#999" font-family="Arial, sans-serif" font-size="14">
                    Loading...
                </text>
            </svg>
        `;
        
        return 'data:image/svg+xml;base64,' + btoa(svg);
    }

    /**
     * Manually trigger lazy loading for new elements
     */
    refresh() {
        if (this.imageObserver && this.iframeObserver) {
            this.observeLazyElements();
        } else {
            // Fallback for scroll listener
            this.setupLazyImages();
            this.setupLazyIframes();
        }
    }

    /**
     * Preload critical images
     */
    preloadCriticalImages(urls) {
        urls.forEach(url => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = url;
            document.head.appendChild(link);
        });
    }

    /**
     * Load image immediately (bypass lazy loading)
     */
    loadImageNow(img) {
        if (img.dataset.src) {
            this.loadImage(img);
            if (this.imageObserver) {
                this.imageObserver.unobserve(img);
            }
        }
    }
}

// Initialize lazy loader when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.lazyLoader = new LazyLoader();
});

// Handle dynamic content
document.addEventListener('DOMNodeInserted', () => {
    if (window.lazyLoader) {
        // Debounce refresh calls
        clearTimeout(window.lazyLoader.refreshTimeout);
        window.lazyLoader.refreshTimeout = setTimeout(() => {
            window.lazyLoader.refresh();
        }, 100);
    }
});

export default LazyLoader;