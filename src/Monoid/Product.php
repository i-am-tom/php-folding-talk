<?php

namespace Talk\Monoid;

/**
 * The Product monoid.
 */
class Product implements Monoid
{
    /**
     * The inner value.
     * @var float
     */
    private $value = null;

    /**
     * Create a new Product instance.
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * Combine two Product instances. This really means
     * creating a new Product instance that contains, you
     * guessed it, the product of $this and $that.
     * @param Talk\Monoid\Product $that
     * @return Talk\Monoid\Product
     */
    public function concat(Product $that) : Product
    {
        return new self($this->value * $that->value);
    }

    /**
     * Get the value from this Product.
     * @return float
     */
    public function get() : float
    {
        return $this->value;
    }
}
