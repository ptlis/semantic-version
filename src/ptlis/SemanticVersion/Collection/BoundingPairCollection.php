<?php

/**
 * Collection used to store bounding pairs.
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

use ArrayIterator;
use OutOfBoundsException;
use ptlis\SemanticVersion\BoundingPair\BoundingPair;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Exception\SemanticVersionException;
use Traversable;

/**
 * Collection used to store bounding pairs.
 */
class BoundingPairCollection implements CollectionInterface
{
    /**
     * @var BoundingPair[]
     */
    private $boundingPairList = array();


    /**
     * Set the internal store to the provided values.
     *
     * @throws SemanticVersionException
     *
     * @param BoundingPair[] $boundingPairList
     */
    public function setList(array $boundingPairList)
    {
        $this->boundingPairList = array();
        foreach ($boundingPairList as $index => $boundingPair) {
            $this->offsetSet($index, $boundingPair);
        }
    }


    /**
     * Return count of elements.
     *
     * @return int
     */
    public function count()
    {
        return count($this->boundingPairList);
    }


    /**
     * Retrieve an external iterator.
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->boundingPairList);
    }


    /**
     * Whether the offset exists.
     *
     * @param string $offset An offset to check for.
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->boundingPairList);
    }


    /**
     * Get an element by offset.
     *
     * @throws OutOfBoundsException
     *
     * @param string $offset The offset to retrieve.
     *
     * @return BoundingPair
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException();
        }

        return $this->boundingPairList[$offset];
    }


    /**
     * Set the bounding pair to the offset.
     *
     * @throws SemanticVersionException
     *
     * @param string $offset        The offset to assign the value to.
     * @param BoundingPair $value   The bounding pair to store.
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof BoundingPair)) {
            throw new SemanticVersionException(
                'A BoundingPairCollection may only store objects implementing BoundingPair.'
            );
        }

        if (is_null($offset) || $offset === '') {
            $this->boundingPairList[] = $value;
        } else {
            $this->boundingPairList[$offset] = $value;
        }
    }


    /**
     * Unset the bounding pair at the offset
     *
     * @param string $offset The offset to unset.
     */
    public function offsetUnset($offset)
    {
        unset($this->boundingPairList[$offset]);
    }


    /**
     * Returns a new sorted collection.
     *
     * @return BoundingPairCollection with elements in ascending order
     */
    public function getAscending()
    {
        $newBoundingPairList = $this->boundingPairList;

        usort(
            $newBoundingPairList,
            $this->getCompareClosure(1)
        );

        $newCollection = new BoundingPairCollection();
        $newCollection->setList($newBoundingPairList);

        return $newCollection;
    }


    /**
     * Returns a new sorted collection.
     *
     * @return BoundingPairCollection with elements in descending order
     */
    public function getDescending()
    {
        $newBoundingPairList = $this->boundingPairList;

        usort(
            $newBoundingPairList,
            $this->getCompareClosure(-1)
        );

        $newCollection = new BoundingPairCollection();
        $newCollection->setList($newBoundingPairList);

        return $newCollection;
    }


    /**
     * Get closure for use in sorting.
     *
     * @param int    $factor    1 for ascending, -1 for descending
     *
     * @return callable
     */
    private function getCompareClosure($factor)
    {
        return function (BoundingPair $lPair, BoundingPair $rPair) use ($factor) {

            // Try comparing by lower version comparators
            $lowerResult = $this->compare(
                new LessThan(),
                1 * $factor,
                -1 * $factor,
                '>=',
                $lPair->getLower(),
                $rPair->getLower()
            );
            if ($lowerResult !== 0) {
                return $lowerResult;
            }

            // Compare by upper version comparators
            return $this->compare(
                new GreaterThan(),
                -1 * $factor,
                1 * $factor,
                '<=',
                $lPair->getUpper(),
                $rPair->getUpper()
            );
        };
    }


    /**
     * Compare lower ComparatorVersions of BoundingPairs.
     *
     * @param ComparatorInterface       $comparator
     * @param                           $rightLess
     * @param                           $rightGreater
     * @param string                    $symbol
     * @param ComparatorVersion|null    $lComp
     * @param ComparatorVersion|null    $rComp
     *
     * @return int
     */
    private function compare(
        ComparatorInterface $comparator,
        $rightLess,
        $rightGreater,
        $symbol,
        ComparatorVersion $lComp = null,
        ComparatorVersion $rComp = null
    ) {

        // Determine how to move the right value
        $move = 0;
        switch (true) {

            // The left & right ComparatorVersions are identical
            case $this->comparatorIdentical($lComp, $rComp):
                $move = 0;
                break;

            // Right ComparatorVersion null, equivalent to version being less/greater than than left
            case is_null($rComp):
                $move = $rightLess;
                break;

            // Left ComparatorVersion null, equivalent to version being less/greater than than right
            case is_null($lComp):
                $move = $rightGreater;
                break;

            // Right version less/greater than left
            case $comparator->compare($rComp->getVersion(), $lComp->getVersion()):
                $move = $rightLess;
                break;

            // Left version less/greater than right
            case $comparator->compare($lComp->getVersion(), $rComp->getVersion()):
                $move = $rightGreater;
                break;

            // Versions match, right comparator effectively less/greater
            case $rComp->getComparator()->getSymbol() === $symbol:
                $move = $rightLess;
                break;

            // Versions match, left comparator effectively less/greater
            case $lComp->getComparator()->getSymbol() === $symbol:
                $move = $rightGreater;
                break;
        }

        return $move;
    }


    /**
     * Returns true of the comparator versions are identical
     *
     * @param ComparatorVersion|null $lComp
     * @param ComparatorVersion|null $rComp
     *
     * @return bool
     */
    private function comparatorIdentical(ComparatorVersion $lComp = null, ComparatorVersion $rComp = null)
    {
        $identical = false;
        if (is_null($lComp) && is_null($rComp)) {
            $identical = true;
        } elseif (!is_null($lComp) && !is_null($rComp) && $lComp->__toString() === $rComp->__toString()) {
            $identical = true;
        }

        return $identical;
    }


    /**
     * Returns a string representation of the bounding pair collection.
     */
    public function __toString()
    {
        return implode(', ', $this->boundingPairList);
    }
}
