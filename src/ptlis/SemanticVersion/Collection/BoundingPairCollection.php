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
    private $boundingPairList = [];


    /**
     * Set the internal store to the provided values.
     *
     * @throws SemanticVersionException
     *
     * @param BoundingPair[] $boundingPairList
     */
    public function setList(array $boundingPairList)
    {
        $this->boundingPairList = [];
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

        $equalTo = new EqualTo();

        usort(
            $newBoundingPairList,
            $this->getCompareClosure($equalTo, 'ascending')
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

        $equalTo = new EqualTo();

        usort(
            $newBoundingPairList,
            $this->getCompareClosure($equalTo, 'descending')
        );

        $newCollection = new BoundingPairCollection();
        $newCollection->setList($newBoundingPairList);

        return $newCollection;
    }

    const MOVE_R_UP = 1;
    const UNCHANGED = 0;
    const MOVE_R_DOWN = -1;


    /**
     * Get closure for use in sorting.
     *
     * @param EqualTo   $equalTo
     * @param string    $ordering
     *
     * @return callable
     */
    private function getCompareClosure(EqualTo $equalTo, $ordering)
    {
        return function (BoundingPair $lPair, BoundingPair $rPair) use ($equalTo, $ordering) {

            // Try comparing by lower version comparators
            $lowerResult = $this->compareLower($lPair, $rPair, $ordering);
            if ($lowerResult !== static::UNCHANGED) {
                return $lowerResult;
            }

            // Compare by upper version comparators
            return $this->compareUpper($lPair, $rPair, $ordering);

        };
    }


    /**
     * Compare lower ComparatorVersions of BoundingPairs.
     *
     * @param BoundingPair $lPair
     * @param BoundingPair $rPair
     * @param string       $ordering
     *
     * @return int
     */
    private function compareLower(BoundingPair $lPair, BoundingPair $rPair, $ordering)
    {
        $lessThan = new LessThan();

        // Factor is used to invert return for descending sort
        $factor = 1;
        if ($ordering == 'descending') {
            $factor = -1;
        }

        // Determine how to move the right value
        $move = static::UNCHANGED;
        switch (true) {

            // Both null so matching
            case is_null($lPair->getLower()) && is_null($rPair->getLower()):
                $move = static::UNCHANGED;
                break;

            // Right pair lower null, equivalent to version being less than than left
            case is_null($rPair->getLower()):
                $move = static::MOVE_R_UP * $factor;
                break;

            // Left pair lower null, equivalent to version being less than than right
            case is_null($lPair->getLower()):
                $move = static::MOVE_R_DOWN * $factor;
                break;

            // The left & right lower comparator versions are identical
            case $lPair->getLower()->__toString() === $rPair->getLower()->__toString():
                $move = static::UNCHANGED;
                break;

            // Right pair lower version less than left
            case $lessThan->compare($rPair->getLower()->getVersion(), $lPair->getLower()->getVersion()):
                $move = static::MOVE_R_UP * $factor;
                break;

            // Left pair lower version less than right
            case $lessThan->compare($lPair->getLower()->getVersion(), $rPair->getLower()->getVersion()):
                $move = static::MOVE_R_DOWN * $factor;
                break;

            // Versions match, right comparator effectively lower
            case $rPair->getLower()->getComparator()->getSymbol() === '>=':
                $move = static::MOVE_R_UP * $factor;
                break;

            // Versions match, left comparator effectively lower
            case $lPair->getLower()->getComparator()->getSymbol() === '>=':
                $move = static::MOVE_R_DOWN * $factor;
                break;
        }

        return $move;
    }


    /**
     * Compare upper ComparatorVersions of BoundingPairs.
     *
     * @param BoundingPair $lPair
     * @param BoundingPair $rPair
     * @param string       $ordering
     *
     * @return int
     */
    private function compareUpper(BoundingPair $lPair, BoundingPair $rPair, $ordering)
    {
        $greaterThan = new GreaterThan();

        // Factor is used to invert return for descending sort
        $factor = 1;
        if ($ordering == 'descending') {
            $factor = -1;
        }

        // Determine how to move the right value
        $move = static::UNCHANGED;
        switch (true) {

            // Both null so matching
            case is_null($lPair->getUpper()) && is_null($rPair->getUpper()):
                $move = static::UNCHANGED;
                break;

            // Right pair upper null, equivalent to version being greater than than left
            case is_null($rPair->getUpper()):
                $move = static::MOVE_R_DOWN * $factor;
                break;

            // Left pair upper null, equivalent to version being greater than than right
            case is_null($lPair->getUpper()):
                $move = static::MOVE_R_UP * $factor;
                break;

            // The left & right upper comparator versions are identical
            case $lPair->getUpper()->__toString() === $rPair->getUpper()->__toString():
                $move = static::UNCHANGED;
                break;

            // Right pair upper version greater than left
            case $greaterThan->compare($rPair->getUpper()->getVersion(), $lPair->getUpper()->getVersion()):
                $move = static::MOVE_R_DOWN * $factor;
                break;

            // Left pair upper version greater than right
            case $greaterThan->compare($lPair->getUpper()->getVersion(), $rPair->getUpper()->getVersion()):
                $move = static::MOVE_R_UP * $factor;
                break;

            // Versions match, right comparator effectively lower
            case $rPair->getUpper()->getComparator()->getSymbol() === '<':
                $move = static::MOVE_R_UP * $factor;
                break;

            // Versions match, left comparator effectively lower
            case $lPair->getUpper()->getComparator()->getSymbol() === '<':
                $move = static::MOVE_R_DOWN * $factor;
                break;
        }

        return $move;
    }


    /**
     * Returns a string representation of the bounding pair collection.
     */
    public function __toString()
    {
        return implode(', ', $this->boundingPairList);
    }
}
