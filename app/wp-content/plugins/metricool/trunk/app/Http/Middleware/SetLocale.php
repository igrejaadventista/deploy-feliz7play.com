<?php

declare(strict_types=1);

namespace Metricool\Http\Middleware;

use Metricool\Vendor\Carbon\Carbon;
use Metricool\Interfaces\MiddlewareInterface;

/**
 * Set the locale for the current request. The locale is determined by the user's locale, or the site's locale if the user is not logged in.
 */
class SetLocale implements MiddlewareInterface
{
    public function handle(\WP_REST_Request $request, callable $next)
    {
        $locale = get_user_locale() ?: get_locale();
        switch_to_locale($locale);

        Carbon::setLocale($locale);

        return $next($request);
    }
}
