<?php

namespace Metricool\Vendor;

/**
 * Dot - PHP dot notation access to arrays
 *
 * @author  Riku Särkinen <riku@adbar.io>
 * @link    https://github.com/adbario/php-dot-notation
 * @license https://github.com/adbario/php-dot-notation/blob/3.x/LICENSE.md (MIT License)
 */
use Metricool\Vendor\Adbar\Dot;
if (!\function_exists('Metricool\Vendor\dot')) {
    /**
     * Create a new Dot object with the given items
     *
     * @param  mixed  $items
     * @param  bool  $parse
     * @param  non-empty-string  $delimiter
     * @return \Adbar\Dot<array-key, mixed>
     */
    function dot($items, $parse = \false, $delimiter = ".")
    {
        return new Dot($items, $parse, $delimiter);
    }
}
