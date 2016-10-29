<?php

namespace Talk\Monoid\Factory;

use Talk\Monoid\Factory;
use Talk\Monoid\Max as Instance;

/**
 * A factory for creating Max monoids.
 * @see Talk\Monoid\Monoid
 */
class Max implements Factory
{
    /**
     * Create a new Max monoid for a given value.
     * @param float The inner value.
     * @return Fold\Monoid\Max
     */
    public function build(float $value) : Instance
    {
        return new Instance($value);
    }

    /**
     * Return the Max identity. Note that this isn't a true
     * identity as there are larger representable values.
     * PHP7.2 has PHP_FLOAT_MIN, which will be the true Max
     * identity (for the PHP language, at least).
     * @return Fold\Monoid\Max
     */
    public function empty() : Instance
    {
        return new Instance(PHP_INT_MIN);
    }
}
