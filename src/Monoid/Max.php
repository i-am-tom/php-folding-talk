<?php

namespace Talk\Monoid;

/**
 * The Max monoid. Represents a maximum value.
 */
class Max implements Monoid
{
    /**
     * The inner value of the monoid.
     * @var float
     */
    private $value = null;

    /**
     * Create a new Max.
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * Combine two Max instances. In practice, this
     * means returning the bigger one.
     * @param Talk\Monoid\Max $that The other to compare.
     * @return Talk\Monoid\Max
     */
    public function concat(Max $that) : Max
    {
        return $this->value > $that->value ? $this : $that;
    }

    /**
     * Get the value out of this Max.
     * @return float
     */
    public function get() : float
    {
        return $this->value;
    }
}
