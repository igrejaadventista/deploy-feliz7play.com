<?php

namespace Metricool\Vendor;

// Don't redefine the functions if included multiple times.
if (!\function_exists('Metricool\Vendor\GuzzleHttp\describe_type')) {
    require __DIR__ . '/functions.php';
}
