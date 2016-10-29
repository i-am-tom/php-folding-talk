<?php

namespace Talk\Monoid\Factory;

use Talk\Monoid\Factory;
use Talk\Monoid\Min as Instance;

/**
 * A factory for creating Min values.
 * @see Talk\Monoid\Monoid
 */
class Min implements Factory
{
    /**
     * Create a Min-wrapped value.
     * @param float The inner value.
     * @return Fold\Monoid\Min
     */
    public function build(float $value) : Instance
    {
        return new Instance($value);
    }

    /**
     * Return the identity for Min. Note that this is
     * not a true identity - see the Max empty for an
     * explanation.
     * @return Fold\Monoid\Min
     * @see Fold\Monoid\Factory\Max::empty()
     */
    public function empty() : Instance
    {
        return new Instance(PHP_INT_MAX);
    }
}
