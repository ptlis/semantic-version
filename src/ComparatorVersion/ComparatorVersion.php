<?php

/**
 * Entity to represent a semantic version number with a comparator.
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

namespace ptlis\SemanticVersion\ComparatorVersion;

use ptlis\SemanticVersion\OldVersion\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\InRange\InRangeInterface;
use ptlis\SemanticVersion\OldVersion\VersionInterface;

/**
 * Entity to represent a semantic version number with a comparator.
 */
class ComparatorVersion implements InRangeInterface
{
    /**
     * Comparator for ranging.
     *
     * @var ComparatorInterface
     */
    private $comparator;

    /**
     * The Version entity.
     *
     * @var VersionInterface
     */
    private $version;


    /**
     * @param ComparatorInterface $comparator
     *
     * @return $this
     */
    public function setComparator(ComparatorInterface $comparator)
    {
        $this->comparator = $comparator;

        return $this;
    }


    /**
     * @return ComparatorInterface
     */
    public function getComparator()
    {
        return $this->comparator;
    }


    /**
     * @param VersionInterface $version
     *
     * @return $this
     */
    public function setVersion(VersionInterface $version)
    {
        $this->version = $version;

        return $this;
    }


    /**
     * @return VersionInterface
     */
    public function getVersion()
    {
        return $this->version;
    }


    /**
     * Returns a string representation of the comparator version number.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->comparator . $this->version->__toString();
    }


    /**
     * Returns true if the provided version satisfies the requirements of the bounding pair.
     *
     * @param VersionInterface $version
     *
     * @return boolean
     */
    public function isSatisfiedBy(VersionInterface $version)
    {
        return $this->comparator->compare($version, $this->version);
    }


    /**
     * Deep clone.
     */
    public function __clone()
    {
        if (!is_null($this->comparator)) {
            $this->comparator = clone $this->comparator;
        }

        if (!is_null($this->version)) {
            $this->version = clone $this->version;
        }
    }
}
