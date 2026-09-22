<?php

declare(strict_types=1);

namespace Metricool\Http\Metricool\Traits;

trait IsCountable
{
    /**
     * Abstract method to be implemented by classes using this trait. It should
     * return an array of data. When {@see count()} is called, this method will
     * be used to retrieve the data to count.
     */
    abstract public function get(): array;

    /**
     * Method just returns the count of the items in the array returned by the
     * get() method. Operation type methods should always return an array.
     */
    public function count(): array
    {
        return [
            count($this->get()),
        ];
    }
}
