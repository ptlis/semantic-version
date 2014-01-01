<?php

/**
 * Entity to represent a semantic version number with a comparator.
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

namespace ptlis\SemanticVersion\Entity;

use ptlis\SemanticVersion\Entity\Comparator\ComparatorInterface;

/**
 * Entity to represent a semantic version number with a comparator.
 */
class ComparatorVersion
{
    /**
     * Comparator for ranging.
     *
     * @var string
     */
    private $comparator;

    /**
     * The Version entity.
     *
     * @var Version
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
     * @param Version $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }


    /**
     * @return Version
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
}
