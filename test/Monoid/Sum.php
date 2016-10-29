<?php

namespace Talk\Test\Monoid;

use Faker;

use Talk\Monoid\Factory;
use Talk\Monoid\Sum;

describe('Sum', function () {
    beforeAll(function () {
        $this->faker = Faker\Factory::create();
        $this->sum = new Factory\Sum;
    });

    context('__construct', function () {
        it('creates an object', function () {
            expect($this->sum->build(2.0))
                ->toBeAnInstanceOf(Sum::class);
        });

        it('holds the value', function () {
            expect(
                $this->sum
                    ->build(2.0)
                    ->get()
            )->toBe(2.0);
        });
    });

    context('Factory\Sum->empty', function () {
        it('has the identity Sum', function () {
            expect(
                $this->sum
                    ->empty()
                    ->get()
            )->toBe((float) 0);
        });

        context('Identity', function () {
            it('has left identity', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num = $this->faker->randomFloat;

                    expect(
                        $this->sum->empty()->concat(
                            $this->sum->build($num)
                        )->get()
                    )->toBe($num);
                }
            });

            it('has right identity', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num = $this->faker->randomFloat;

                    expect(
                        $this->sum->build($num)->concat(
                            $this->sum->empty()
                        )->get()
                    )->toBe($num);
                }
            });
        });
    });

    context('->concat', function () {
        it('combines two Sum', function () {
            expect(
                $this->sum->build(250)->concat(
                    $this->sum->build(125)
                )->get()
            )->toBe((float) 375);
        });

        context('Associativity', function () {
            it('is associative', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1 = $this->faker->randomNumber;
                    $num2 = $this->faker->randomNumber;
                    $num3 = $this->faker->randomNumber;

                    expect(
                        $this->sum->build($num1)
                            ->concat($this->sum->build($num2))
                            ->concat($this->sum->build($num3))
                            ->get()
                    )->toBe(
                        $this->sum->build($num1)
                            ->concat($this->sum->build($num2))
                            ->concat($this->sum->build($num3))
                            ->get()
                    );
                }
            });
        });

        // Sum is a commutative monoid.
        context('Commutativity', function () {
            it('is commutative', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1 = $this->faker->randomNumber;
                    $num2 = $this->faker->randomNumber;

                    expect(
                        $this->sum->build($num1)
                            ->concat($this->sum->build($num2))
                            ->get()
                    )->toBe(
                        $this->sum->build($num2)
                            ->concat($this->sum->build($num1))
                            ->get()
                    );
                }
            });
        });
    });
});
