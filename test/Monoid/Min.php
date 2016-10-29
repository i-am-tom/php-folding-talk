<?php

namespace Talk\Test\Monoid;

use Faker;

use Talk\Monoid\Factory;
use Talk\Monoid\Min;

describe('Min', function () {
    beforeAll(function () {
        $this->faker = Faker\Factory::create();
        $this->min = new Factory\Min;
    });

    context('__construct', function () {
        it('creates an object', function () {
            expect($this->min->build(2.0))
                ->toBeAnInstanceOf(Min::class);
        });

        it('holds the value', function () {
            expect(
                $this->min
                    ->build(2.0)
                    ->get()
            )->toBe(2.0);
        });
    });

    context('Factory\Min->empty', function () {
        it('has the maximum Min', function () {
            expect(
                $this->min
                    ->empty()
                    ->get()
            )->toBe((float) PHP_INT_MAX);
        });

        context('Identity', function () {
            it('has left identity', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num = $this->faker->randomFloat;

                    expect(
                        $this->min->empty()->concat(
                            $this->min->build($num)
                        )->get()
                    )->toBe($num);
                }
            });

            it('has right identity', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num = $this->faker->randomFloat;

                    expect(
                        $this->min->build($num)->concat(
                            $this->min->empty()
                        )->get()
                    )->toBe($num);
                }
            });
        });
    });

    context('->concat', function () {
        it('combines two Min', function () {
            expect(
                $this->min->build(250)->concat(
                    $this->min->build(125)
                )->get()
            )->toBe((float) 125);
        });

        context('Associativity', function () {
            it('is associative', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1 = $this->faker->randomNumber;
                    $num2 = $this->faker->randomNumber;
                    $num3 = $this->faker->randomNumber;

                    expect(
                        $this->min->build($num1)
                            ->concat($this->min->build($num2))
                            ->concat($this->min->build($num3))
                            ->get()
                    )->toBe(
                        $this->min->build($num1)
                            ->concat($this->min->build($num2))
                            ->concat($this->min->build($num3))
                            ->get()
                    );
                }
            });
        });

        // Min is a commutative monoid.
        context('Commutativity', function () {
            it('is commutative', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1 = $this->faker->randomNumber;
                    $num2 = $this->faker->randomNumber;

                    expect(
                        $this->min->build($num1)
                            ->concat($this->min->build($num2))
                            ->get()
                    )->toBe(
                        $this->min->build($num2)
                            ->concat($this->min->build($num1))
                            ->get()
                    );
                }
            });
        });
    });
});
