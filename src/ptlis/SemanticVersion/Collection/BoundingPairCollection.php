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
            $lLowerVer  = $lPair->getLower()->getVersion();
            $lUpperVer  = $lPair->getUpper()->getVersion();
            $lLowerComp = $lPair->getLower()->getComparator();
            $lUpperComp = $lPair->getUpper()->getComparator();

            $rLowerVer  = $rPair->getLower()->getVersion();
            $rUpperVer  = $rPair->getUpper()->getVersion();

            if ($ordering == 'ascending') {
                $comparator = new LessThan();
            } else {
                $comparator = new GreaterThan();
            }

            // Identical bounding pairs
            if ($this->identicalBoundingPairs($lPair, $rPair)) {
                return 0;

            // Identical lower comparator version
            } elseif ($this->identicalComparatorVersions($lPair->getLower(), $rPair->getLower())) {

                // Identical Upper versions
                if ($equalTo->compare($lUpperVer, $rUpperVer)) {
                    return $this->orderComparatorUpperLeft($lUpperComp, $ordering);

                } elseif ($comparator->compare($lUpperVer, $rUpperVer)) {
                    return -1;

                } else {
                    return 1;
                }

            // Matching lower versions, different comparators
            } elseif ($equalTo->compare($lLowerVer, $rLowerVer)) {
                return $this->orderComparatorLowerLeft($lLowerComp, $ordering);

            // Mismatched lower, order by comparator
            } elseif ($comparator->compare($lLowerVer, $rLowerVer)) {
                return -1;

            } else {
                return 1;
            }
        };
    }


    /**
     * Returns true if the bounding pairs are identical.
     *
     * @param BoundingPair $lPair
     * @param BoundingPair $rPair
     *
     * @return bool
     */
    private function identicalBoundingPairs(BoundingPair $lPair, BoundingPair $rPair)
    {
        $identical = false;

        if ($lPair->__toString() === $rPair->__toString()) {
            $identical = true;
        }

        return $identical;
    }


    /**
     * Returns true if the comparator versions are identical.
     *
     * @param ComparatorVersion $lCompVer
     * @param ComparatorVersion $rCompVer
     *
     * @return bool
     */
    private function identicalComparatorVersions(ComparatorVersion $lCompVer, ComparatorVersion $rCompVer)
    {
        $identical = false;

        if ($lCompVer->getVersion()->__toString() === $rCompVer->getVersion()->__toString()
                && $lCompVer->getComparator()->getSymbol() === $rCompVer->getComparator()->getSymbol()) {
            $identical = true;
        }

        return $identical;
    }


    /**
     * Returns the ordering value for sort function based on lower left comparator & ordering.
     *
     * @param ComparatorInterface $comparator
     * @param                     $ordering
     *
     * @return int
     */
    private function orderComparatorLowerLeft(ComparatorInterface $comparator, $ordering)
    {
        if ($ordering == 'ascending') {
            if ($comparator->getSymbol() == '>=') {
                return -1;
            } else {
                return 1;
            }

        } else {
            if ($comparator->getSymbol() == '>') {
                return -1;
            } else {
                return 1;
            }
        }
    }


    /**
     * Returns the ordering value for sort function based on upper left comparator & ordering.
     *
     * @param ComparatorInterface $comparator
     * @param string              $ordering
     *
     * @return int
     */
    private function orderComparatorUpperLeft(ComparatorInterface $comparator, $ordering)
    {
        if ($ordering == 'ascending') {
            if ($comparator->getSymbol() == '<') {
                return -1;
            } else {
                return 1;
            }

        } else {
            if ($comparator->getSymbol() == '<=') {
                return -1;
            } else {
                return 1;
            }
        }
    }


    /**
     * Returns a string representation of the bounding pair collection.
     */
    public function __toString()
    {
        return implode(', ', $this->boundingPairList);
    }
}
