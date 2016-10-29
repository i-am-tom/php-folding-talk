<?php

namespace Talk\Test\Monoid;

use Faker;

use Talk\Monoid\Factory;
use Talk\Monoid\Max;

describe('Max', function () {
    beforeAll(function () {
        $this->faker = Faker\Factory::create();
        $this->max = new Factory\Max;
    });

    context('__construct', function () {
        it('creates an object', function () {
            expect($this->max->build(2.0))
                ->toBeAnInstanceOf(Max::class);
        });

        it('holds the value', function () {
            expect(
                $this->max
                    ->build(2.0)
                    ->get()
            )->toBe(2.0);
        });
    });

    context('Factory\Max->empty', function () {
        it('has the minimum Max', function () {
            expect(
                $this->max
                    ->empty()
                    ->get()
            )->toBe((float) PHP_INT_MIN);
        });

        context('Identity', function () {
            it('has left identity', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num = $this->faker->randomFloat;

                    expect(
                        $this->max->empty()->concat(
                            $this->max->build($num)
                        )->get()
                    )->toBe($num);
                }
            });

            it('has right identity', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num = $this->faker->randomFloat;

                    expect(
                        $this->max->build($num)->concat(
                            $this->max->empty()
                        )->get()
                    )->toBe($num);
                }
            });
        });
    });

    context('->concat', function () {
        it('combines two Max', function () {
            expect(
                $this->max->build(250)->concat(
                    $this->max->build(125)
                )->get()
            )->toBe((float) 250);
        });

        context('Associativity', function () {
            it('is associative', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1 = $this->faker->randomNumber;
                    $num2 = $this->faker->randomNumber;
                    $num3 = $this->faker->randomNumber;

                    expect(
                        $this->max->build($num1)
                            ->concat($this->max->build($num2))
                            ->concat($this->max->build($num3))
                            ->get()
                    )->toBe(
                        $this->max->build($num1)
                            ->concat($this->max->build($num2))
                            ->concat($this->max->build($num3))
                            ->get()
                    );
                }
            });
        });

        // Max is a commutative monoid.
        context('Commutativity', function () {
            it('is commutative', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1 = $this->faker->randomNumber;
                    $num2 = $this->faker->randomNumber;

                    expect(
                        $this->max->build($num1)
                            ->concat($this->max->build($num2))
                            ->get()
                    )->toBe(
                        $this->max->build($num2)
                            ->concat($this->max->build($num1))
                            ->get()
                    );
                }
            });
        });
    });
});
