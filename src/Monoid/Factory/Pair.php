<?php

namespace Talk\Monoid\Factory;

use Talk\Monoid;
use Talk\Monoid\Factory;
use Talk\Monoid\Pair as Instance;

/**
 * A factory for creating Pair monoids. The Pair is a monoid
 * if both the left and right sides are also monoids, as
 * concatenation is done on both sides independently.
 */
class Pair implements Factory
{
    /**
     * The factory for the left-side monoid.
     * @var Fold\Monoid\Factory
     */
    private $left = null;

    /**
     * The factory for the right-side monoid.
     * @var Fold\Monoid\Factory
     */
    private $right = null;

    /**
     * Create a new Factory for pairs. Pair is an example of why
     * we need factories to create our monoids - a more strongly-
     * typed language would be able to infer the monoids on each
     * side of the pair, so the factory would be unnecessary.
     * @param Fold\Monoid\Factory $left
     * @param Fold\Monoid\Factory $right
     */
    public function __construct(
        Factory $left,
        Factory $right
    ) {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * Create a new monoid pair from values that are already
     * wrapped in the monoids of their respective sides.
     * @param Fold\Monoid The left value.
     * @param Fold\Monoid The right value.
     * @return Fold\Monoid\Pair
     */
    public function build($left, $right) : Instance
    {
        return new Instance($left, $right);
    }

    /**
     * Return an empty monoid pair. As mentioned earlier in
     * {@see __construct}, the factory is only necessary in
     * PHP because the types of each side can't be inferred.
     * @return Fold\Monoid\Pair
     */
    public function empty() : Instance
    {
        return new Instance(
            $this->left->empty(),
            $this->right->empty()
        );
    }
}
