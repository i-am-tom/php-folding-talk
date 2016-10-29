<?php

namespace Talk;

/**
 * This is where all the magic happens. MASSIVE shout-out to
 * Gabriel Gonzalez for basically all the work - this talk was
 * just going to be about refactoring loops to use monoids, but
 * the Fold idea is inspired.
 * @link http://www.haskellforall.com/2013/08/composable-streaming-folds.html
 */
class Fold
{
    /**
     * A monoid factory. As mentioned elsewhere in the codebase, this
     * needs to exist because the PHP compiler can't infer any types
     * for the intermediates.
     * @var Talk\Monoid\Factory
     */
    private $monoid = null;

    /**
     * A function to transform the items of the given list into
     * instances of the above monoid.
     * @var callable
     */
    private $tally = null;

    /**
     * Once the $tally-made monoids have been folded up, this
     * function will retrieve the final value from the monoid.
     * @var callable
     */
    private $summarize = null;

    /**
     * Create a new fold. The {@see Talk\Factory\Fold} factory
     * will hide the need for the intermediate monoid.
     * @param Talk\Monoid\Factory
     * @param callable $tally Monoidify the inputs.
     * @param callable $summarize Retrieve the result.
     */
    public function __construct(
        Monoid\Factory $monoid,
        callable $tally,
        callable $summarize
    ) {
        $this->monoid = $monoid;
        $this->tally = $tally;
        $this->summarize = $summarize;
    }

    /**
     * Combine the items according to the fold. In practice,
     * this means taking the identity for the chosen monoid,
     * wrapping each value, and concatenating it. All the
     * "business logic" is contained within the monoid's
     * operation, making this totally generic.
     * @param array $items The things to fold.
     * @return mixed The result of retrieval from the fold.
     */
    public function fold(array $items)
    {
        $result = $this->monoid->empty();

        // This is for optimisation - ideally, we'd use
        // array_reduce over an array_map of $this->tally,
        // but PHP won't notice that it can merge these
        // operations into one efficient loop.

        foreach ($items as $item) {
            $result = $result->concat(
                ($this->tally)($item)
            );
        }

        return ($this->summarize)($result);
    }

    /**
     * "Apply" a fold to $this. This method is part of the
     * Apply spec, which is part of the Applicative spec,
     * which we implement in order to lift functions and
     * make beautiful-looking folds.
     * @param Talk\Fold $that
     * @return Talk\Fold
     */
    public function ap(Fold $that) : Fold
    {
        $pairer = new Monoid\Factory\Pair(
            $this->monoid, $that->monoid
        );

        return new self(
            $pairer,
            function ($x) use ($pairer, $that) {
                return $pairer->build(
                    ($this->tally)($x),
                    ($that->tally)($x)
                );
            },

            function ($pair) use ($that) {
                list ($left, $right) = $pair->get(
                    function ($l, $r) {
                        return [$l, $r];
                    }
                );

                return ($this->summarize)($left)(
                    ($that->summarize)($right)
                );
            }
        );
    }

    /**
     * As well as Apply, the Applicative instance implies
     * a Functor instance. Functor means "thing that we
     * can map over". Here's the function to map over it.
     * In practice, this means applying a function to the
     * output of {@see self::fold()}.
     * @param callable $f The fold transformation.
     * @return Talk\Fold
     */
    public function map(callable $f) : Fold
    {
        return new self(
            $this->monoid,
            $this->tally,
            function ($x) use ($f) {
                return $f(
                    ($this->summarize)($x)
                );
            }
        );
    }

    /**
     * We can also preprocess the data by declaring Fold as
     * a contravariant functor, which means we can contramap:
     * in practice, this is mapping every value before they
     * enter into the Fold.
     */
    public function contramap(callable $f) : Fold
    {
        return new self(
            $this->monoid,
            function ($x) use ($f) {
                return ($this->tally)($f($x));
            },
            $this->summarize
        );
    }

    /* -- NUMERICAL METHODS -- */

    /**
     * Add $this and $that.
     * @param Talk\Fold $that
     * @return Talk\Fold
     */
    public function plus(Fold $that) : Fold
    {
        return $this->map(function ($x) {
            return function ($y) use ($x) {
                return $x + $y;
            };
        })->ap($that);
    }

    /**
     * Subtract $that from $this.
     * @param Talk\Fold $that
     * @return Talk\Fold
     */
    public function minus(Fold $that) : Fold
    {
        return $this->map(function ($x) {
            return function ($y) use ($x) {
                return $x - $y;
            };
        })->ap($that);
    }

    /**
     * Multiply $this by $that.
     * @param Talk\Fold $that
     * @return Talk\Fold
     */
    public function times(Fold $that) : Fold
    {
        return $this->map(function ($x) {
            return function ($y) use ($x) {
                return $x * $y;
            };
        })->ap($that);
    }

    /**
     * Divide $this by $that.
     * @param Talk\Fold $that
     * @return Talk\Fold
     */
    public function divideBy(Fold $that) : Fold
    {
        return $this->map(function ($x) {
            return function ($y) use ($x) {
                return $x / $y;
            };
        })->ap($that);
    }

    /**
     * Raise $this to some power.
     * @param float $n The exponent
     * @return Talk\Fold
     */
    public function toThePowerOf(float $n) : Fold
    {
        return $this->map(function ($x) use ($n) {
            return pow($x, $n);
        });
    }

    /**
     * Square root the answer.
     * @return Talk\Fold
     */
    public function sqrt() : Fold
    {
        return $this->map('sqrt');
    }
}
