<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PerformanceOptimizer
{
    protected $config;

    public function __construct()
    {
        $this->config = config('performance');
    }

    /**
     * Generate WebP image with fallback
     */
    public function generateWebpImage($params): string
    {
        $params = $this->parseParams($params);
        $src = $params['src'];
        $alt = $params['alt'] ?? '';
        $class = $params['class'] ?? '';
        $loading = $params['loading'] ?? 'lazy';

        if (!$this->config['images']['webp_enabled']) {
            return $this->generateStandardImage($src, $alt, $class, $loading);
        }

        $webpSrc = $this->getWebpPath($src);
        
        return sprintf(
            '<picture class="%s">
                <source srcset="%s" type="image/webp">
                <img src="%s" alt="%s" loading="%s" class="%s">
            </picture>',
            $class,
            $webpSrc,
            $src,
            htmlspecialchars($alt),
            $loading,
            $class
        );
    }

    /**
     * Generate lazy loading image
     */
    public function generateLazyImage($params): string
    {
        $params = $this->parseParams($params);
        $src = $params['src'];
        $alt = $params['alt'] ?? '';
        $class = $params['class'] ?? '';
        $placeholder = $params['placeholder'] ?? $this->generatePlaceholder($src);

        if (!$this->config['lazy_loading']['images']) {
            return $this->generateStandardImage($src, $alt, $class);
        }

        return sprintf(
            '<img src="%s" data-src="%s" alt="%s" class="lazy-image %s" loading="lazy">',
            $placeholder,
            $src,
            htmlspecialchars($alt),
            $class
        );
    }

    /**
     * Generate standard image tag
     */
    protected function generateStandardImage($src, $alt, $class, $loading = 'lazy'): string
    {
        return sprintf(
            '<img src="%s" alt="%s" class="%s" loading="%s">',
            $src,
            htmlspecialchars($alt),
            $class,
            $loading
        );
    }

    /**
     * Get critical CSS for a route
     */
    public function getCriticalCss($route = null): string
    {
        if (!$this->config['critical_css']['enabled']) {
            return '';
        }

        $route = $route ?: request()->path();
        $criticalPath = $this->config['critical_css']['paths'][$route] ?? null;

        if (!$criticalPath) {
            return '';
        }

        $cacheKey = 'critical_css_' . md5($route);
        
        return Cache::remember($cacheKey, 3600, function () use ($criticalPath) {
            $cssPath = resource_path('css/' . $criticalPath);
            
            if (file_exists($cssPath)) {
                $css = file_get_contents($cssPath);
                return '<style>' . $this->minifyCss($css) . '</style>';
            }
            
            return '';
        });
    }

    /**
     * Generate preload tag for resources
     */
    public function generatePreloadTag($params): string
    {
        $params = $this->parseParams($params);
        $href = $params['href'];
        $as = $params['as'] ?? 'style';
        $type = $params['type'] ?? null;
        $crossorigin = $params['crossorigin'] ?? null;

        $attributes = [
            'rel' => 'preload',
            'href' => $href,
            'as' => $as,
        ];

        if ($type) {
            $attributes['type'] = $type;
        }

        if ($crossorigin) {
            $attributes['crossorigin'] = $crossorigin;
        }

        $attributeString = collect($attributes)
            ->map(fn($value, $key) => sprintf('%s="%s"', $key, htmlspecialchars($value)))
            ->implode(' ');

        return sprintf('<link %s>', $attributeString);
    }

    /**
     * Get WebP version path of an image
     */
    protected function getWebpPath($imagePath): string
    {
        $pathInfo = pathinfo($imagePath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
    }

    /**
     * Generate placeholder for lazy loading
     */
    protected function generatePlaceholder($src): string
    {
        // Generate a simple SVG placeholder
        $width = 800;
        $height = 600;
        
        // Try to extract dimensions from filename or use defaults
        if (preg_match('/(\d+)x(\d+)/', $src, $matches)) {
            $width = $matches[1];
            $height = $matches[2];
        }

        $svg = sprintf(
            '<svg width="%d" height="%d" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%%" height="100%%" fill="#f0f0f0"/>
                <text x="50%%" y="50%%" text-anchor="middle" dy=".3em" fill="#999">Loading...</text>
            </svg>',
            $width,
            $height
        );

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Parse parameters from Blade directive
     */
    protected function parseParams($expression): array
    {
        // Remove quotes and parse as array
        $expression = trim($expression, '"\'');
        
        // Simple parsing for key=value pairs
        $params = [];
        if (Str::contains($expression, '=')) {
            parse_str(str_replace([' ', ','], ['&', '&'], $expression), $params);
        } else {
            // If it's just a string, treat as src
            $params['src'] = $expression;
        }

        return $params;
    }

    /**
     * Minify CSS content
     */
    protected function minifyCss($css): string
    {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove whitespace
        $css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $css);
        
        // Remove unnecessary spaces
        $css = preg_replace('/\s+/', ' ', $css);
        $css = preg_replace('/;\s*}/', '}', $css);
        $css = preg_replace('/\s*{\s*/', '{', $css);
        $css = preg_replace('/;\s*/', ';', $css);
        $css = preg_replace('/:\s*/', ':', $css);
        
        return trim($css);
    }

    /**
     * Generate responsive image srcset
     */
    public function generateResponsiveImage($src, $sizes = []): string
    {
        if (empty($sizes)) {
            $sizes = $this->config['images']['sizes']['hero'] ?? [];
        }

        $srcset = [];
        foreach ($sizes as $size => $dimensions) {
            $responsiveSrc = $this->getResponsiveImagePath($src, $dimensions);
            $srcset[] = sprintf('%s %dw', $responsiveSrc, $dimensions['width']);
        }

        return implode(', ', $srcset);
    }

    /**
     * Get responsive image path
     */
    protected function getResponsiveImagePath($src, $dimensions): string
    {
        $pathInfo = pathinfo($src);
        $suffix = sprintf('_%dx%d', $dimensions['width'], $dimensions['height'] ?? 0);
        
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . $suffix . '.' . $pathInfo['extension'];
    }

    /**
     * Optimize image file
     */
    public function optimizeImage($imagePath, $outputPath = null, $quality = null): bool
    {
        $outputPath = $outputPath ?: $imagePath;
        $quality = $quality ?: $this->config['images']['compression_quality'];

        if (!file_exists($imagePath)) {
            return false;
        }

        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) {
            return false;
        }

        $mimeType = $imageInfo['mime'];
        
        switch ($mimeType) {
            case 'image/jpeg':
                return $this->optimizeJpeg($imagePath, $outputPath, $quality);
            case 'image/png':
                return $this->optimizePng($imagePath, $outputPath);
            case 'image/webp':
                return $this->optimizeWebp($imagePath, $outputPath, $quality);
            default:
                return false;
        }
    }

    /**
     * Optimize JPEG image
     */
    protected function optimizeJpeg($src, $dest, $quality): bool
    {
        $image = imagecreatefromjpeg($src);
        if (!$image) return false;

        $result = imagejpeg($image, $dest, $quality);
        imagedestroy($image);
        
        return $result;
    }

    /**
     * Optimize PNG image
     */
    protected function optimizePng($src, $dest): bool
    {
        $image = imagecreatefrompng($src);
        if (!$image) return false;

        imagesavealpha($image, true);
        imagepng($image, $dest, 9);
        imagedestroy($image);
        
        return true;
    }

    /**
     * Optimize WebP image
     */
    protected function optimizeWebp($src, $dest, $quality): bool
    {
        if (!function_exists('imagewebp')) {
            return false;
        }

        $image = imagecreatefromwebp($src);
        if (!$image) return false;

        $result = imagewebp($image, $dest, $quality);
        imagedestroy($image);
        
        return $result;
    }

    /**
     * Convert image to WebP format
     */
    public function convertToWebp($imagePath, $outputPath = null, $quality = null): bool
    {
        if (!function_exists('imagewebp')) {
            return false;
        }

        $quality = $quality ?: $this->config['images']['formats']['webp']['quality'];
        $outputPath = $outputPath ?: $this->getWebpPath($imagePath);

        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) return false;

        $image = null;
        switch ($imageInfo['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            default:
                return false;
        }

        if (!$image) return false;

        $result = imagewebp($image, $outputPath, $quality);
        imagedestroy($image);

        return $result;
    }
}