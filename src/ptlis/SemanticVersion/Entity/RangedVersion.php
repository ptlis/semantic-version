<?php

/**
 * Entity to represent a ranged semantic version number.
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

namespace ptlis\SemanticVersion\Entity;

/**
 * Entity to represent a ranged semantic version number.
 */
class RangedVersion
{
    const GREATER_THAN          = '>';
    const GREATER_OR_EQUAL_TO   = '>=';
    const LESS_THAN             = '<';
    const LESS_OR_EQUAL_TO      = '<=';
    const EQUAL_TO              = '=';


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
     * @param string $comparator
     *
     * @return $this
     */
    public function setComparator($comparator)
    {
        $this->comparator = $comparator;

        return $this;
    }


    /**
     * @return string
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
     * Returns a string representation of the ranged version number.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->comparator . $this->version->__toString();
    }
}
