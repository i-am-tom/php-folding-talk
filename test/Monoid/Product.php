<?php

namespace Talk\Test\Monoid;

use Faker;

use Talk\Monoid\Factory;
use Talk\Monoid\Product;

describe('Product', function () {
    beforeAll(function () {
        $this->faker = Faker\Factory::create();
        $this->product = new Factory\Product;
    });

    context('__construct', function () {
        it('creates an object', function () {
            expect($this->product->build(2.0))
                ->toBeAnInstanceOf(Product::class);
        });

        it('holds the value', function () {
            expect(
                $this->product
                    ->build(2.0)
                    ->get()
            )->toBe(2.0);
        });
    });

    context('Factory\Product->empty', function () {
        it('has the identity Product', function () {
            expect(
                $this->product
                    ->empty()
                    ->get()
            )->toBe((float) 1);
        });

        context('Identity', function () {
            it('has left identity', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num = $this->faker->randomFloat;

                    expect(
                        $this->product->empty()->concat(
                            $this->product->build($num)
                        )->get()
                    )->toBe($num);
                }
            });

            it('has right identity', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num = $this->faker->randomFloat;

                    expect(
                        $this->product->build($num)->concat(
                            $this->product->empty()
                        )->get()
                    )->toBe($num);
                }
            });
        });
    });

    context('->concat', function () {
        it('combines two Product', function () {
            expect(
                $this->product->build(250)->concat(
                    $this->product->build(125)
                )->get()
            )->toBe((float) 31250);
        });

        context('Associativity', function () {
            it('is associative', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1 = $this->faker->randomNumber;
                    $num2 = $this->faker->randomNumber;
                    $num3 = $this->faker->randomNumber;

                    expect(
                        $this->product->build($num1)
                            ->concat($this->product->build($num2))
                            ->concat($this->product->build($num3))
                            ->get()
                    )->toBe(
                        $this->product->build($num1)
                            ->concat($this->product->build($num2))
                            ->concat($this->product->build($num3))
                            ->get()
                    );
                }
            });
        });

        // Product is a commutative monoid.
        context('Commutativity', function () {
            it('is commutative', function () {
                for ($i = 0; $i < 100; $i++) {
                    $num1 = $this->faker->randomNumber;
                    $num2 = $this->faker->randomNumber;

                    expect(
                        $this->product->build($num1)
                            ->concat($this->product->build($num2))
                            ->get()
                    )->toBe(
                        $this->product->build($num2)
                            ->concat($this->product->build($num1))
                            ->get()
                    );
                }
            });
        });
    });
});
