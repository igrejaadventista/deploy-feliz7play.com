<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Filters;

use Metricool\Vendor\Carbon\Carbon;
use IntlDateFormatter;

final class PeriodFilter
{
    private string $period;
    private Carbon $startDate;
    private Carbon $endDate;

    public const YESTERDAY_MONTH_FORMAT = 'LT';
    public const DEFAULT_MONTH_FORMAT = 'D MMM';
    public const LAST_WEEK_MONTH_FORMAT = 'ddd D MMM';

    public function __construct(string $period)
    {
        $this->startDate = Carbon::now();
        $this->endDate = Carbon::now();
        $this->period = $period;
    }

    /**
     * Use this method to retrieve the calculated start date after calling
     * {@see calculate()}
     */
    public function getStartDate(): Carbon
    {
        return $this->startDate;
    }

    /**
     * Use this method to retrieve the calculated end date after calling
     * {@see calculate()}
     */
    public function getEndDate(): Carbon
    {
        return $this->endDate;
    }

    /**
     * Method is used to calculate the start and end date based on the given
     * period. The calculation is based on the same calculation on the remote.
     */
    public function calculate(): self
    {
        switch ($this->period) {
            case 'yesterday':
                $this->startDate->subDay();
                $this->endDate->subDay();
                break;
            case 'lastweek':
                $this->startDate->subDays(7);
                $this->endDate->subDay();
                break;
            case 'previousmonth':
                $this->startDate->subMonths(1)->startOfMonth();
                $this->endDate->subMonths(1)->endOfMonth();
                break;
            case 'last30days':
                $this->startDate->subDays(30);
                break;
            case 'last3months':
                $this->startDate->subDay()->subMonths(3);
                break;
            case 'last6months':
                $this->startDate->subDay()->subMonths(6);
                break;
            case 'last12months':
                $this->startDate->subDay()->subMonths(12);
                break;
            case 'currentmonth':
                $this->startDate->startOfMonth();
                break;
            default:
                throw new \LogicException('Unsupported period given: ' . esc_html(sanitize_text_field($this->period)));
        }

        return $this;
    }

    /**
     * Use this static method to parse a given period string into a new instance
     * @param bool $calculate Whether to calculate the start and end dates.
     */
    public static function parse(string $period, bool $calculate = false): PeriodFilter
    {
        $instance = new PeriodFilter(strtolower($period));

        return $calculate ? $instance->calculate() : $instance;
    }

    /**
     * Use this function to retrieve the ISO date month format based on the
     * given period.
     */
    public static function getIsoDateMonthFormat(?string $period = null): string
    {
        switch ($period) {
            case 'yesterday':
                return self::YESTERDAY_MONTH_FORMAT;
            case 'lastweek':
                return self::LAST_WEEK_MONTH_FORMAT;
            case 'currentmonth':
            case 'lastmonth':
            case 'previousmonth':
            case 'last30days':
                return self::DEFAULT_MONTH_FORMAT;
            default:
                return self::localIsoDateMonthFormat();
        }
    }

    /**
     * Use this function to retrieve if the month comes before the day in the
     * user's local date format.
     */
    private static function localIsoDateMonthFormat(): string
    {
        $formatter = new IntlDateFormatter(
            get_locale(),
            IntlDateFormatter::SHORT,
            IntlDateFormatter::NONE
        );

        // Get the pattern
        $pattern = $formatter->getPattern();

        // Check if month comes before day
        $monthPos = strpos($pattern, 'M');
        $dayPos = strpos($pattern, 'd');

        return $monthPos < $dayPos ? 'MM/DD' : 'DD/MM';
    }
}
