<?php

namespace Talk\Factory;

use Talk\Fold as Instance;
use Talk\Monoid;

/**
 * This factory is for generating Fold instances. The factory
 * is used to hide the need for knowledge of the intermediate
 * monoid, and to provide some type safety.
 */
class Fold
{
    /**
     * The monoid factory for the intermediate representation.
     * This should match the output of $tally, as well as the
     * input of $summarize.
     * @var Talk\Monoid\Factory
     */
    private $monoid = null;

    /**
     * Create a new factory and cement the intermediate
     * monoid, prettying up the actual fold step.
     * @param Talk\Monoid\Factory
     */
    public function __construct(Monoid\Factory $intermediate)
    {
        $this->monoid = $intermediate;
    }

    /**
     * Create a new Fold. A fold is comprised of a function,
     * $tally, that transforms an input into some monoid, and
     * another function, $summarize, that extracts the result
     * from the final monoid.
     * @param callable Tally function.
     * @param callable Summarize function.
     * @return Talk\Fold
     */
    public function build(callable $tally, callable $summarize) : Instance
    {
        return new Instance($this->monoid, $tally, $summarize);
    }

    /**
     * Lift a value into a Fold. This is part of the contract
     * that comes with the Applicative type class, and is why
     * we need the factory at all: in languages with stronger
     * types, the compiler can work out the intermediate
     * monoid type for us, and we don't need a factory.
     * @param mixed The value to lift.
     * @return Talk\Fold
     */
    public function of($value) : Instance
    {
        return new Instance(
            $this->monoid,

            function ($_) {
                return null;
            },

            function ($_) use ($value) {
                return $value;
            }
        );
    }
}
