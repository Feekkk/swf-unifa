/**
 * Cross-browser Polyfills
 * Provides compatibility for older browsers
 */

// Intersection Observer Polyfill (simplified)
if (!('IntersectionObserver' in window)) {
    window.IntersectionObserver = class IntersectionObserver {
        constructor(callback, options = {}) {
            this.callback = callback;
            this.options = options;
            this.elements = new Set();
            this.setupScrollListener();
        }

        observe(element) {
            this.elements.add(element);
            this.checkIntersection(element);
        }

        unobserve(element) {
            this.elements.delete(element);
        }

        disconnect() {
            this.elements.clear();
            if (this.scrollListener) {
                window.removeEventListener('scroll', this.scrollListener);
                window.removeEventListener('resize', this.scrollListener);
            }
        }

        setupScrollListener() {
            let ticking = false;
            
            this.scrollListener = () => {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        this.elements.forEach(element => {
                            this.checkIntersection(element);
                        });
                        ticking = false;
                    });
                    ticking = true;
                }
            };

            window.addEventListener('scroll', this.scrollListener);
            window.addEventListener('resize', this.scrollListener);
        }

        checkIntersection(element) {
            const rect = element.getBoundingClientRect();
            const rootMargin = this.parseRootMargin(this.options.rootMargin || '0px');
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;
            const windowWidth = window.innerWidth || document.documentElement.clientWidth;

            const isIntersecting = (
                rect.top >= -rootMargin.top &&
                rect.left >= -rootMargin.left &&
                rect.bottom <= windowHeight + rootMargin.bottom &&
                rect.right <= windowWidth + rootMargin.right
            );

            this.callback([{
                target: element,
                isIntersecting,
                intersectionRatio: isIntersecting ? 1 : 0,
                boundingClientRect: rect,
                rootBounds: {
                    top: 0,
                    left: 0,
                    bottom: windowHeight,
                    right: windowWidth,
                    width: windowWidth,
                    height: windowHeight
                }
            }]);
        }

        parseRootMargin(rootMargin) {
            const values = rootMargin.split(' ').map(v => parseInt(v) || 0);
            return {
                top: values[0] || 0,
                right: values[1] || values[0] || 0,
                bottom: values[2] || values[0] || 0,
                left: values[3] || values[1] || values[0] || 0
            };
        }
    };
}

// CustomEvent Polyfill
if (!window.CustomEvent || typeof window.CustomEvent !== 'function') {
    function CustomEvent(event, params) {
        params = params || { bubbles: false, cancelable: false, detail: null };
        const evt = document.createEvent('CustomEvent');
        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
        return evt;
    }
    window.CustomEvent = CustomEvent;
}

// requestAnimationFrame Polyfill
if (!window.requestAnimationFrame) {
    window.requestAnimationFrame = function(callback) {
        return setTimeout(callback, 1000 / 60);
    };
}

if (!window.cancelAnimationFrame) {
    window.cancelAnimationFrame = function(id) {
        clearTimeout(id);
    };
}

// Array.from Polyfill
if (!Array.from) {
    Array.from = function(arrayLike, mapFn, thisArg) {
        const C = this;
        const items = Object(arrayLike);
        if (arrayLike == null) {
            throw new TypeError('Array.from requires an array-like object - not null or undefined');
        }
        const mapFunction = mapFn === undefined ? undefined : mapFn;
        if (typeof mapFunction !== 'undefined' && typeof mapFunction !== 'function') {
            throw new TypeError('Array.from: when provided, the second argument must be a function');
        }
        const len = parseInt(items.length) || 0;
        const A = typeof C === 'function' ? Object(new C(len)) : new Array(len);
        let k = 0;
        let kValue;
        while (k < len) {
            kValue = items[k];
            if (mapFunction) {
                A[k] = typeof thisArg === 'undefined' ? mapFunction(kValue, k) : mapFunction.call(thisArg, kValue, k);
            } else {
                A[k] = kValue;
            }
            k += 1;
        }
        A.length = len;
        return A;
    };
}

// Object.assign Polyfill
if (typeof Object.assign !== 'function') {
    Object.assign = function(target) {
        if (target == null) {
            throw new TypeError('Cannot convert undefined or null to object');
        }
        const to = Object(target);
        for (let index = 1; index < arguments.length; index++) {
            const nextSource = arguments[index];
            if (nextSource != null) {
                for (const nextKey in nextSource) {
                    if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                        to[nextKey] = nextSource[nextKey];
                    }
                }
            }
        }
        return to;
    };
}

// Element.matches Polyfill
if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector || 
                                Element.prototype.webkitMatchesSelector;
}

// Element.closest Polyfill
if (!Element.prototype.closest) {
    Element.prototype.closest = function(s) {
        let el = this;
        do {
            if (Element.prototype.matches.call(el, s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}

// classList Polyfill for older browsers
if (!('classList' in document.createElement('_'))) {
    (function(view) {
        if (!('Element' in view)) return;
        
        const classListProp = 'classList',
              protoProp = 'prototype',
              elemCtrProto = view.Element[protoProp],
              objCtr = Object,
              strTrim = String[protoProp].trim || function() {
                  return this.replace(/^\s+|\s+$/g, '');
              },
              arrIndexOf = Array[protoProp].indexOf || function(item) {
                  let i = 0, len = this.length;
                  for (; i < len; i++) {
                      if (i in this && this[i] === item) {
                          return i;
                      }
                  }
                  return -1;
              };

        const DOMTokenList = function(el) {
            this.el = el;
            const classes = el.className.replace(/^\s+|\s+$/g, '').split(/\s+/);
            for (let i = 0, len = classes.length; i < len; i++) {
                this.push(classes[i]);
            }
            this._updateClassName = function() {
                el.className = this.toString();
            };
        };

        const tokenListProto = DOMTokenList[protoProp] = [];

        tokenListProto.item = function(i) {
            return this[i] || null;
        };

        tokenListProto.contains = function(token) {
            token += '';
            return arrIndexOf.call(this, token) !== -1;
        };

        tokenListProto.add = function() {
            const tokens = arguments;
            let i = 0, l = tokens.length, token, updated = false;
            do {
                token = tokens[i] + '';
                if (arrIndexOf.call(this, token) === -1) {
                    this.push(token);
                    updated = true;
                }
            } while (++i < l);

            if (updated) {
                this._updateClassName();
            }
        };

        tokenListProto.remove = function() {
            const tokens = arguments;
            let i = 0, l = tokens.length, token, updated = false, index;
            do {
                token = tokens[i] + '';
                index = arrIndexOf.call(this, token);
                while (index !== -1) {
                    this.splice(index, 1);
                    updated = true;
                    index = arrIndexOf.call(this, token);
                }
            } while (++i < l);

            if (updated) {
                this._updateClassName();
            }
        };

        tokenListProto.toggle = function(token, force) {
            token += '';
            const result = this.contains(token),
                  method = result ? force !== true && 'remove' : force !== false && 'add';

            if (method) {
                this[method](token);
            }

            if (force === true || force === false) {
                return force;
            } else {
                return !result;
            }
        };

        tokenListProto.toString = function() {
            return this.join(' ');
        };

        if (objCtr.defineProperty) {
            const dPD = {
                get: function() {
                    return new DOMTokenList(this);
                },
                enumerable: true,
                configurable: true
            };
            try {
                objCtr.defineProperty(elemCtrProto, classListProp, dPD);
            } catch (ex) {
                if (ex.number === -0x7FF5EC54) {
                    dPD.enumerable = false;
                    objCtr.defineProperty(elemCtrProto, classListProp, dPD);
                }
            }
        }
    }(window));
}

// Console polyfill for IE
if (!window.console) {
    window.console = {
        log: function() {},
        warn: function() {},
        error: function() {},
        info: function() {},
        debug: function() {}
    };
}

// Performance.now polyfill
if (!window.performance) {
    window.performance = {};
}

if (!window.performance.now) {
    const nowOffset = Date.now();
    window.performance.now = function() {
        return Date.now() - nowOffset;
    };
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {};
}