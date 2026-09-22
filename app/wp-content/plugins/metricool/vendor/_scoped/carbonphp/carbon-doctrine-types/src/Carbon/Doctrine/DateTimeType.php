<?php

namespace Metricool\Vendor\Carbon\Doctrine;

use Metricool\Vendor\Carbon\Carbon;
use Metricool\Vendor\Doctrine\DBAL\Types\VarDateTimeType;
class DateTimeType extends VarDateTimeType implements CarbonDoctrineType
{
    /** @use CarbonTypeConverter<Carbon> */
    use CarbonTypeConverter;
}
