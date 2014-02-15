<?php

/**
 * Collection used to store versions.
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
use Traversable;
use ptlis\SemanticVersion\Version\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Version\Comparator\EqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterThan;
use ptlis\SemanticVersion\Version\Comparator\LessThan;
use ptlis\SemanticVersion\Exception\SemanticVersionException;
use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Collection used to store versions.
 */
class VersionCollection implements SortableCollectionInterface
{
    /**
     * @var VersionInterface[]
     */
    private $versionList = array();


    /**
     * Set the internal store to the provided values.
     *
     * @throws SemanticVersionException
     *
     * @param VersionInterface[] $versionList
     *
     * @return VersionCollection
     */
    public function setList(array $versionList)
    {
        $this->versionList = array();
        foreach ($versionList as $index => $version) {
            $this->offsetSet($index, $version);
        }

        return $this;
    }


    /**
     * Return count of elements.
     *
     * @return int
     */
    public function count()
    {
        return count($this->versionList);
    }


    /**
     * Retrieve an external iterator.
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->versionList);
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
        return array_key_exists($offset, $this->versionList);
    }


    /**
     * Get an element by offset.
     *
     * @throws OutOfBoundsException
     *
     * @param string $offset The offset to retrieve.
     *
     * @return VersionInterface
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException();
        }

        return $this->versionList[$offset];
    }


    /**
     * Set the version to the offset.
     *
     * @throws SemanticVersionException
     *
     * @param string $offset            The offset to assign the value to.
     * @param VersionInterface $value   The version to store.
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof VersionInterface)) {
            throw new SemanticVersionException(
                'A VersionCollection may only store objects implementing VersionInterface.'
            );
        }

        if (is_null($offset) || $offset === '') {
            $this->versionList[] = $value;
        } else {
            $this->versionList[$offset] = $value;
        }
    }


    /**
     * Unset the version at the offset
     *
     * @param string $offset The offset to unset.
     */
    public function offsetUnset($offset)
    {
        unset($this->versionList[$offset]);
    }


    /**
     * Returns a new sorted collection.
     *
     * @return VersionCollection with elements in ascending order
     */
    public function getAscending()
    {
        $newVersionList = $this->versionList;

        $lessThan = new LessThan();
        $equalTo = new EqualTo();

        usort(
            $newVersionList,
            $this->getCompareClosure($equalTo, $lessThan)
        );

        $newCollection = new VersionCollection();
        $newCollection->setList($newVersionList);

        return $newCollection;
    }


    /**
     * Returns a new sorted collection.
     *
     * @return SortableCollectionInterface with elements in descending order
     */
    public function getDescending()
    {
        $newVersionList = $this->versionList;

        $greaterThan = new GreaterThan();
        $equalTo = new EqualTo();

        usort(
            $newVersionList,
            $this->getCompareClosure($equalTo, $greaterThan)
        );

        $newCollection = new VersionCollection();
        $newCollection->setList($newVersionList);

        return $newCollection;
    }


    /**
     * Get closure for use in sorting.
     *
     * @param EqualTo             $equalTo
     * @param ComparatorInterface $comparator
     *
     * @return \Closure
     */
    private function getCompareClosure(EqualTo $equalTo, ComparatorInterface $comparator)
    {
        return function (VersionInterface $lVersion, VersionInterface $rVersion) use ($comparator, $equalTo) {
            if ($equalTo->compare($lVersion, $rVersion)) {
                return 0;
            } elseif ($comparator->compare($lVersion, $rVersion)) {
                return -1;
            } else {
                return 1;
            }
        };
    }


    /**
     * Returns a string representation of the version collection.
     */
    public function __toString()
    {
        return implode(', ', $this->versionList);
    }


    /**
     * Deep clone
     */
    public function __clone()
    {
        $newVersionList = array();

        foreach ($this->versionList as $index => $version) {
            $newVersionList[$index] = clone $version;
        }

        $this->versionList = $newVersionList;
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

        $equalTo = new EqualTo();
        foreach ($this->versionList as $version) {
            if ($equalTo->compare($compareVersion, $version)) {
                $satisfied = true;
            }
        }

        return $satisfied;
    }
}