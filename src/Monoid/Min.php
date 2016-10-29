<?php

namespace Talk\Monoid;

/**
 * The Min monoid.
 */
class Min implements Monoid
{
    /**
     * The inner value of the Min.
     * @var float
     */
    private $value = null;

    /**
     * Create a new Min instance.
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * Combine two Mins. In practice, this means
     * returning the smaller of the two.
     * @param Talk\Monoid\Min $that The value to compare
     * @return Talk\Monoid\Min
     */
    public function concat(Min $that) : Min
    {
        return $this->value < $that->value ? $this : $that;
    }

    /**
     * Get the value from this Min
     * @return float
     */
    public function get() : float
    {
        return $this->value;
    }
}
