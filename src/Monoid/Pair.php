<?php

namespace Talk\Monoid;

/**
 * The Pair monoid. Both elements of the pair must
 * be monoids in order for this to be a monoid.
 */
class Pair implements Monoid
{
    /**
     * The factory for the left-side monoid.
     * @var Talk\Monoid\Monoid
     */
    private $left = null;

    /**
     * The factory for the right-side monoid.
     * @var Talk\Monoid\Monoid
     */
    private $right = null;

    /**
     * Create a new monoid pair.
     * @param Talk\Monoid\Monoid $left
     * @param Talk\Monoid\Monoid $right
     */
    public function __construct(Monoid $left, Monoid $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * Combine two pairs. In practice, this means combining
     * the lefts and the rights to make a new left and right.
     * @param Talk\Monoid\Pair $that
     * @return Talk\Monoid\Pair
     */
    public function concat(Pair $that) : Pair
    {
        return new self(
            $this->left->concat($that->left),
            $this->right->concat($that->right)
        );
    }

    /**
     * Get the value from the pair. Because the pair is
     * comprised of two values, a callback must be supplied
     * that takes both values and returns them in some
     * combined form.
     * @param callable $f The final folding function.
     * @return mixed The result of $f(left, right).
     */
    public function get(callable $f)
    {
        return $f($this->left, $this->right);
    }
}
