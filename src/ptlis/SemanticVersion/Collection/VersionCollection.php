<?php

/**
 * Collection used to store versions.
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
use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Exception\SemanticVersionException;
use ptlis\SemanticVersion\Version\VersionInterface;
use Traversable;

/**
 * Collection used to store versions.
 */
class VersionCollection implements SortableCollectionInterface
{
    /**
     * @var VersionInterface[]
     */
    private $versionList = [];


    /**
     * Set the internal store to the provided values.
     *
     * @throws SemanticVersionException
     *
     * @param VersionInterface[] $versionList
     */
    public function setList(array $versionList)
    {
        $this->versionList = [];
        foreach ($versionList as $index => $version) {
            $this->offsetSet($index, $version);
        }
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
            function ($lVersion, $rVersion) use ($lessThan, $equalTo) {
                if ($equalTo->compare($lVersion, $rVersion)) {
                    return 0;
                } elseif ($lessThan->compare($lVersion, $rVersion)) {
                    return -1;
                } else {
                    return 1;
                }
            }
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
            function ($lVersion, $rVersion) use ($greaterThan, $equalTo) {
                if ($equalTo->compare($lVersion, $rVersion)) {
                    return 0;
                } elseif ($greaterThan->compare($lVersion, $rVersion)) {
                    return -1;
                } else {
                    return 1;
                }
            }
        );

        $newCollection = new VersionCollection();
        $newCollection->setList($newVersionList);

        return $newCollection;
    }
}
