<?php

namespace Talk\Monoid\Factory;

use Talk\Monoid\Factory;
use Talk\Monoid\Product as Instance;

/**
 * A factory for creating Product monoids.
 * @see Talk\Monoid\Monoid
 */
class Product implements Factory
{
    /**
     * Create a new Product-wrapped value.
     * @param float The inner value.
     * @return Fold\Monoid\Product
     */
    public function build(float $value) : Instance
    {
        return new Instance($value);
    }

    /**
     * Return the identity Product.
     * @return Fold\Monoid\Product
     */
    public function empty() : Instance
    {
        return new Instance(1);
    }
}
