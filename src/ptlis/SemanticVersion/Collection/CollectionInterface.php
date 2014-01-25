<?php

/**
 * Interface that collections must implement.
 *
 * PHP Version 5.4
 *
 * @copyright   (c) 2014 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Interface that collections must implement.
 */
interface CollectionInterface extends Countable, IteratorAggregate, ArrayAccess
{
    /**
     * Return count of elements.
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int
     */
    public function count();


    /**
     * Retrieve an external iterator.
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable
     */
    public function getIterator();


    /**
     * Whether the offset exists.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param string $offset    An offset to check for.
     *
     * @return boolean
     */
    public function offsetExists($offset);


    /**
     * Get an element by offset.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param string $offset    The offset to retrieve.
     *
     * @return \Object
     */
    public function offsetGet($offset);


    /**
     * Set the object to the offset.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param string $offset    The offset to assign the value to.
     * @param \Object $value    The object to store.
     *
     * @return void
     */
    public function offsetSet($offset, $value);


    /**
     * Unset the object at the offset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param string $offset    The offset to unset.
     *
     * @return void
     */
    public function offsetUnset($offset);


    /**
     * Create string representation of collection.
     *
     * @link http://www.php.net/manual/en/language.oop5.magic.php#object.tostring
     *
     * @return string
     */
    public function __toString();
}
