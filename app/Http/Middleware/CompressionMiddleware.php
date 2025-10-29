<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only compress if the response is successful and contains content
        if ($response->getStatusCode() !== 200 || empty($response->getContent())) {
            return $response;
        }

        // Check if client accepts compression
        $acceptEncoding = $request->header('Accept-Encoding', '');
        
        // Check if content is compressible
        $contentType = $response->headers->get('Content-Type', '');
        if (!$this->isCompressible($contentType)) {
            return $response;
        }

        // Apply compression based on client support
        if (strpos($acceptEncoding, 'br') !== false && function_exists('brotli_compress')) {
            return $this->applyBrotliCompression($response);
        } elseif (strpos($acceptEncoding, 'gzip') !== false && function_exists('gzencode')) {
            return $this->applyGzipCompression($response);
        }

        return $response;
    }

    /**
     * Check if content type is compressible
     */
    protected function isCompressible(string $contentType): bool
    {
        $compressibleTypes = [
            'text/html',
            'text/css',
            'text/javascript',
            'application/javascript',
            'application/json',
            'application/xml',
            'text/xml',
            'text/plain',
            'image/svg+xml',
        ];

        foreach ($compressibleTypes as $type) {
            if (strpos($contentType, $type) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Apply Brotli compression
     */
    protected function applyBrotliCompression(Response $response): Response
    {
        $content = $response->getContent();
        $compressed = brotli_compress($content, 6, BROTLI_TEXT);

        if ($compressed !== false) {
            $response->setContent($compressed);
            $response->headers->set('Content-Encoding', 'br');
            $response->headers->set('Content-Length', strlen($compressed));
            $response->headers->set('Vary', 'Accept-Encoding');
        }

        return $response;
    }

    /**
     * Apply Gzip compression
     */
    protected function applyGzipCompression(Response $response): Response
    {
        $content = $response->getContent();
        $compressed = gzencode($content, 6);

        if ($compressed !== false) {
            $response->setContent($compressed);
            $response->headers->set('Content-Encoding', 'gzip');
            $response->headers->set('Content-Length', strlen($compressed));
            $response->headers->set('Vary', 'Accept-Encoding');
        }

        return $response;
    }
}