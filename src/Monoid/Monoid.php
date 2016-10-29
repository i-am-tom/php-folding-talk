<?php

namespace Talk\Monoid;

/**
 * (Again, I'm using interfaces to define objects'
 * high-level purpose, rather than their functionality).
 * Monoids are semigroups with identity. To put that in
 * a more OOP way, monoids have two features:
 *
 * - A `concat` operation, for combining two monoids of
 *   the same type to make a single monoid.
 *
 * - An identity value: a value that, when combined with
 *   any monoid of the same type, returns that other
 *   monoid unchanged.
 *
 * Why is this useful? Because almost every array_reduce
 * call can be expressed as a monoid, and using monoids
 * allows for all sorts of optimisations.
 *
 * @see Talk\Fold::fold() For optimisation talk.
 */
interface Monoid {}
