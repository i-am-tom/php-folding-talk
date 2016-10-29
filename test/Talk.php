<?php

namespace Talk\Test;

use Talk\Monoid;
use Talk\Factory;

describe('Talk examples', function () {
    beforeAll(function () {
        $sumFactory = new Monoid\Factory\Sum;
        $factory = new Factory\Fold($sumFactory);

        $this->length = $factory->build(
            function ($_) use ($sumFactory) {
                return $sumFactory->build(1);
            },
            function ($sum) { return $sum->get(); }
        );

        $this->sum = $factory->build(
            function ($x) use ($sumFactory) {
                return $sumFactory->build($x);
            },
            function ($sum) { return $sum->get(); }
        );

        $divide = function ($x) {
            return function ($y) use ($x) {
                return $x / $y;
            };
        };

        $this->average = $this->sum
            ->divideBy($this->length);

        $sumSq = $this->sum->contramap(
            function ($x) { return pow($x, 2); }
        );

        $this->standardDev = $sumSq
            ->divideBy($this->length)
            ->minus($this->average->toThePowerOf(2))
            ->sqrt();
    });

    context('Length', function () {
        it('counts a list', function () {
            expect(
                $this
                    ->length
                    ->fold(range(0, 10))
            )->toBe((double) 11);
        });

        it('counts an empty list', function () {
            expect($this->length->fold([]))->toBe((double) 0);
        });
    });

    context('Sum', function () {
        it('sums a list', function () {
            expect(
                $this
                    ->sum
                    ->fold(range(0, 10))
            )->toBe((double) 55);
        });

        it('sums an empty list', function () {
            expect($this->sum->fold([]))->toBe((double) 0);
        });
    });

    context('Average', function () {
        it('averages a list', function () {
            expect(
                $this
                    ->average
                    ->fold(range(0, 10))
            )->toBe((float) 5);
        });
    });

    context('Standard deviation', function () {
        it('computes standard deviation', function () {
            expect(round(
                $this
                    ->standardDev
                    ->fold(range(0, 10)),
                3
            ))->toBe(3.162);
        });
    });
});
