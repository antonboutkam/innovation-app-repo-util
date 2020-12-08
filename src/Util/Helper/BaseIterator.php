<?php

namespace Repo\Util\Helper;

use Iterator as PhpIterator;

/**
 * Implements the php iterator except for the methods that return a type so it is easy to loop over objects in a
 * strongly typed way.
 *
 * Class BaseIterator
 */
abstract class BaseIterator implements PhpIterator {
    protected int $position;
    protected array $array;

    abstract public function current();

    public function rewind() {
        $this->position = 0;
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->array[$this->position]);
    }
}
