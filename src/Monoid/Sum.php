<?php

namespace Talk\Monoid;

/**
 * The Sum monoid.
 */
class Sum implements Monoid
{
    /**
     * The inner value.
     * @var float
     */
    private $value = null;

    /**
     * Create a new Sum instance.
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * Combine two Sum instances by returning a new
     * instance with the Sum of $this and $that.
     * @param Talk\Monoid\Sum $that
     * @return Talk\Monoid\Sum
     */
    public function concat(Sum $that) : Sum
    {
        return new self($this->value + $that->value);
    }

    /**
     * Get the value from this Sum.
     * @return float
     */
    public function get() : float
    {
        return $this->value;
    }
}
