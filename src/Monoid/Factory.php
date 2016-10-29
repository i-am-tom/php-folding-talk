<?php

namespace Talk\Monoid;

/**
 * I'm using interfaces in this project more to describe the
 * purpose of each thing rather than to regiment its purpose.
 * The trouble is that the only function that could be here
 * is `empty`, but, really, that's an operation on the monoid,
 * and the factory is an unhappy side-effect of needing to
 * prop up a type system that can't do inference.
 *
 * @todo Use "clone" in the "build" functions to save some
 *       time on instantiation.
 */
interface Factory {}
