# Bringing Functional PHP into the Fold

This code supports the talk of the same name given on 9/11/2016. The concepts here owe total gratitude to [Gabriel Gonzalez](https://twitter.com/gabrielg439?lang=en), whose talks on Haskell folds are the inspiration behind the entire talk.

## Intro

When we're dealing with a list of data, we inevitably want to extract information from it: maxima, minima, lengths, sums, averages, and the list goes on and on. More importantly, every time another of these gets added to the list, we create another function to calculate it. Worse still, we duplicate these functions for every set of different data!

Principally, these functions are all formations of `array_reduce` (we'll call them **folds**, because you're folding up the list into a single value), specifically:

```php
<?php

$acc = START_VALUE;

foreach ($listOfData as $item) {
    $acc = f($acc, $item);
}

return $acc;
```

The only thing that really changes is the function `f`. This is where monoids come in.

### Monoids

## Demo

We use factories for monoids to compensate for PHP's weak type system (if you look at the `Pair` monoid, how can we infer its `empty` from context alone?) So, we first need to instantiate a couple of factories:

```php
<?php

include 'vendor/autoload.php';

$sumFactory = new Folds\Monoid\Factory\Sum;
$foldFactory = new Folds\Factory\Fold($sumFactory);
```

A `Fold` is made of two parts: a `tally` function, which transforms data to a monoidal type, and a `summarize` function, which extracts the result from the final monoid. For functions like `length` and `sum`, these are pretty straightforward:

```php
<?php

$length = $foldFactory->build(
    function ($_) use ($sumFactory) {
        return $sumFactory->build(1);
    },
    function ($sum) { return $sum->get(); }
);

$sum = $foldFactory->build(
    function ($x) use ($sumFactory) {
        return $sumFactory->build($x);
    },
    function ($sum) { return $sum->get(); }
);

// For example...
$length->fold(range(0, 10)); // 11
$sum->fold(range(0, 10)); // 55
```

Because `Fold` is an `Applicative`, we can actually combine them with mathematical operations, which makes for some really clear and concise definitions:

```php
<?php

$average = $sum->divideBy($length);

// For example...
$average->fold(range(0, 10)); // 55 / 11 = 5
```

Because `Fold` is a `Contravariant` functor, we can also take a pre-existing `Fold`, and transform it to produce a new `Fold` that pre-processes the data using contramap:

```php
<?php

// This squares individual values and THEN sums them.
$sumSq = $sum->contramap(function ($x) { return pow($x, 2); });

// For example...
$sumSq->fold(range(0, 10)); // 385
```

And, of course, we can now shove all these concepts together to
produce very complex folds:

```php
<?php

$standardDev = $sumSq
    ->divideBy($length)
    ->minus($average->toThePowerOf(2))
    ->sqrt();

// Et voila!
$standardDev->fold(range(0, 10)); // 3.162...
```

Remember, also, that these calculations only pass over the list once, and all the intermediate values are accumulated until they can be collapsed into a final answer, which means the time complexity of these operations doesn't grow linearly with the complexity of the calculation!
