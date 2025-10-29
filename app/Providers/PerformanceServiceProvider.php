<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

class PerformanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('performance.optimizer', function ($app) {
            return new \App\Services\PerformanceOptimizer();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Blade directives for performance optimization
        $this->registerBladeDirectives();
        
        // Register view composers for performance data
        $this->registerViewComposers();
        
        // Set up response optimization middleware
        $this->setupResponseOptimization();
    }

    /**
     * Register custom Blade directives for performance optimization
     */
    protected function registerBladeDirectives(): void
    {
        // WebP image directive with fallback
        Blade::directive('webpImage', function ($expression) {
            return "<?php echo app('performance.optimizer')->generateWebpImage($expression); ?>";
        });

        // Lazy loading image directive
        Blade::directive('lazyImage', function ($expression) {
            return "<?php echo app('performance.optimizer')->generateLazyImage($expression); ?>";
        });

        // Critical CSS directive
        Blade::directive('criticalCss', function ($expression) {
            return "<?php echo app('performance.optimizer')->getCriticalCss($expression); ?>";
        });

        // Preload directive for resources
        Blade::directive('preload', function ($expression) {
            return "<?php echo app('performance.optimizer')->generatePreloadTag($expression); ?>";
        });

        // DNS prefetch directive
        Blade::directive('dnsPrefetch', function ($expression) {
            return "<?php echo '<link rel=\"dns-prefetch\" href=\"' . $expression . '\">'; ?>";
        });
    }

    /**
     * Register view composers for performance data
     */
    protected function registerViewComposers(): void
    {
        View::composer('*', function ($view) {
            $view->with('performanceConfig', config('performance'));
        });
    }

    /**
     * Setup response optimization
     */
    protected function setupResponseOptimization(): void
    {
        // Add compression headers
        if (config('performance.compression.gzip_enabled')) {
            $this->app['router']->pushMiddlewareToGroup('web', \App\Http\Middleware\CompressionMiddleware::class);
        }
    }
}