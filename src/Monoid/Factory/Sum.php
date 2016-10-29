<?php

namespace Talk\Monoid\Factory;

use Talk\Monoid\Factory;
use Talk\Monoid\Sum as Instance;

/**
 * A factory for creating Sum monoids.
 */
class Sum implements Factory
{
    /**
     * Create a new Sum-wrapped value.
     * @param float The inner value.
     * @return Fold\Monoid\Sum
     */
    public function build(float $value) : Instance
    {
        return new Instance($value);
    }

    /**
     * Return the Sum identity.
     * @return Fold\Monoid\Sum
     */
    public function empty() : Instance
    {
        return new Instance(0);
    }
}
