<?php

/**
 * Collection used to store bounding pairs.
 *
 * PHP Version 5.3
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
use ptlis\SemanticVersion\BoundingPair\Comparator\EqualTo;
use ptlis\SemanticVersion\BoundingPair\Comparator\GreaterThan;
use ptlis\SemanticVersion\BoundingPair\Comparator\LessThan;
use ptlis\SemanticVersion\Exception\SemanticVersionException;
use ptlis\SemanticVersion\Version\VersionInterface;
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
            function (BoundingPair $lPair, BoundingPair $rPair) {
                $equalTo = new EqualTo();
                $greaterThan = new GreaterThan();

                if ($equalTo->compare($lPair, $rPair)) {
                    return 0;

                } elseif ($greaterThan->compare($lPair, $rPair)) {
                    return 1;

                } else {
                    return -1;
                }
            }
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
            function (BoundingPair $lPair, BoundingPair $rPair) {
                $equalTo = new EqualTo();
                $lessThan = new LessThan();

                if ($equalTo->compare($lPair, $rPair)) {
                    return 0;

                } elseif ($lessThan->compare($lPair, $rPair)) {
                    return 1;

                } else {
                    return -1;
                }
            }
        );

        $newCollection = new BoundingPairCollection();
        $newCollection->setList($newBoundingPairList);

        return $newCollection;
    }


    /**
     * Returns a string representation of the bounding pair collection.
     */
    public function __toString()
    {
        return implode(', ', $this->boundingPairList);
    }


    /**
     * Returns true if the provided version satisfies the requirements encoded in the VersionCollection.
     *
     * @param VersionInterface $compareVersion
     *
     * @return boolean
     */
    public function isSatisfiedBy(VersionInterface $compareVersion)
    {
        $satisfied = false;

        foreach ($this->boundingPairList as $boundingPair) {
            if ($boundingPair->isSatisfiedBy($compareVersion)) {
                $satisfied = true;
            }
        }

        return $satisfied;
    }
}
