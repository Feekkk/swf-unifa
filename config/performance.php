<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for performance optimization
    | including image optimization, caching, and asset management.
    |
    */

    'images' => [
        'webp_enabled' => true,
        'lazy_loading' => true,
        'responsive_images' => true,
        'compression_quality' => 85,
        'formats' => [
            'webp' => ['quality' => 85],
            'jpeg' => ['quality' => 90],
            'png' => ['compression' => 9],
        ],
        'sizes' => [
            'hero' => [
                'desktop' => ['width' => 1920, 'height' => 1080],
                'tablet' => ['width' => 1024, 'height' => 576],
                'mobile' => ['width' => 768, 'height' => 432],
            ],
            'logo' => [
                'standard' => ['width' => 200, 'height' => null],
                'retina' => ['width' => 400, 'height' => null],
            ],
            'thumbnail' => [
                'small' => ['width' => 150, 'height' => 150],
                'medium' => ['width' => 300, 'height' => 300],
            ],
        ],
    ],

    'caching' => [
        'static_assets_ttl' => 31536000, // 1 year
        'dynamic_content_ttl' => 3600,   // 1 hour
        'api_cache_ttl' => 300,          // 5 minutes
    ],

    'compression' => [
        'gzip_enabled' => true,
        'brotli_enabled' => true,
        'css_minification' => true,
        'js_minification' => true,
        'html_minification' => false, // Disabled for blade templates
    ],

    'preloading' => [
        'critical_fonts' => [
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap',
            'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
        ],
        'critical_css' => [
            'resources/css/variables.css',
            'resources/css/base.css',
        ],
        'preload_images' => [
            '/assets/images/logos/unikl-rcmp-logo-white.png',
        ],
    ],

    'lazy_loading' => [
        'images' => true,
        'iframes' => true,
        'intersection_observer' => true,
        'fallback_enabled' => true,
    ],

    'critical_css' => [
        'enabled' => true,
        'inline_threshold' => 14000, // 14KB
        'paths' => [
            '/' => 'critical/home.css',
            '/login' => 'critical/auth.css',
            '/register' => 'critical/auth.css',
        ],
    ],
];