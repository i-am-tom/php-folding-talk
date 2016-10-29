<?php

namespace Talk\Test\Monoid;

use Faker;

use Talk\Monoid\Factory;
use Talk\Monoid\Pair;

describe('Pair', function () {
    beforeAll(function () {
        $this->faker = Faker\Factory::create();

        $this->s = new Factory\Sum;
        $this->p = new Factory\Product;

        $this->pair = new Factory\Pair($this->s, $this->p);

        $this->unpack = function ($x, $y) {
            return [$x->get(), $y->get()];
        };
    });

    context('__construct', function () {
        it('creates an object', function () {
            expect(
                $this->pair->build(
                    $this->s->build(2.0),
                    $this->p->build(3.0)
                )
            )->toBeAnInstanceOf(Pair::class);
        });

        it('holds the value', function () {
            expect(
                $this->pair->build(
                    $this->s->build(2.0),
                    $this->p->build(3.0)
                )->get($this->unpack)
            )->toBe([2.0, 3.0]);
        });
    });

    context('Factory\Pair->empty', function () {
        it('has the identity Pair', function () {
            expect(
                $this->pair
                    ->empty()
                    ->get($this->unpack)
            )->toBe([(float) 0, (float) 1]);
        });

        context('Identity', function () {
            it('has left identity', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1 = $this->faker->randomFloat;
                    $num2 = $this->faker->randomFloat;

                    expect(
                        $this->pair
                            ->empty()
                            ->concat(
                                $this->pair->build(
                                    $this->s->build($num1),
                                    $this->p->build($num2)
                                )
                            )
                            ->get($this->unpack)
                    )->toBe([$num1, $num2]);
                }
            });

            it('has right identity', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1 = $this->faker->randomFloat;
                    $num2 = $this->faker->randomFloat;

                    expect(
                        $this->pair->build(
                                $this->s->build($num1),
                                $this->p->build($num2)
                            )
                            ->concat(
                                $this->pair->empty()
                            )
                            ->get($this->unpack)
                    )->toBe([$num1, $num2]);
                }
            });
        });
    });

    context('->concat', function () {
        it('combines two Pair', function () {
            expect(
                $this->pair->build(
                    $this->s->build(5),
                    $this->p->build(5)
                )->concat($this->pair->build(
                    $this->s->build(6),
                    $this->p->build(6)
                ))->get($this->unpack)
            )->toBe([(float) 11, (float) 30]);
        });

        context('Associativity', function () {
            it('is associative', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1a = $this->faker->randomNumber(3);
                    $num1b = $this->faker->randomNumber(3);
                    $num2a = $this->faker->randomNumber(3);
                    $num2b = $this->faker->randomNumber(3);
                    $num3a = $this->faker->randomNumber(3);
                    $num3b = $this->faker->randomNumber(3);

                    expect(
                        $this->pair->build(
                            $this->s->build($num1a),
                            $this->p->build($num1b)
                        )->concat($this->pair->build(
                            $this->s->build($num2a),
                            $this->p->build($num2b)
                        ))->concat($this->pair->build(
                            $this->s->build($num3a),
                            $this->p->build($num3b)
                        ))->get($this->unpack)
                    )->toBe(
                        $this->pair->build(
                            $this->s->build($num1a),
                            $this->p->build($num1b)
                        )->concat(
                            $this->pair->build(
                                $this->s->build($num2a),
                                $this->p->build($num2b)
                            )->concat($this->pair->build(
                                $this->s->build($num3a),
                                $this->p->build($num3b)
                            ))
                        )->get($this->unpack)
                    );
                }
            });
        });

        // Pair is a commutative monoid.
        context('Commutativity', function () {
            it('is commutative', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1a = $this->faker->randomNumber;
                    $num1b = $this->faker->randomNumber;
                    $num2a = $this->faker->randomNumber;
                    $num2b = $this->faker->randomNumber;

                    expect(
                        $this->pair->build(
                            $this->s->build($num1a),
                            $this->p->build($num1b)
                        )->concat($this->pair->build(
                            $this->s->build($num2a),
                            $this->p->build($num2b)
                        ))->get($this->unpack)
                    )->toBe(
                        $this->pair->build(
                            $this->s->build($num2a),
                            $this->p->build($num2b)
                        )->concat($this->pair->build(
                            $this->s->build($num1a),
                            $this->p->build($num1b)
                        ))->get($this->unpack)
                    );
                }
            });
        });
    });
});
