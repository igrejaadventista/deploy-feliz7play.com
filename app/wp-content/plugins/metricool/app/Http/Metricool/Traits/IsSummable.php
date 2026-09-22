<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Traits;

trait IsSummable
{
    /**
     * Abstract method to be implemented by classes using this trait. It should
     * return an array of data. When {@see sum()} is called, this method will
     * be used to retrieve the data to sum.
     */
    abstract public function get(): array;

    /**
     * Method just returns the sum of the items in the array returned by the
     * get() method. Operation type methods should always return an array.
     */
    public function sum(): array
    {
        $numericValues = array_filter($this->get(), 'is_numeric');
        return [
            array_sum($numericValues),
        ];
    }
}
