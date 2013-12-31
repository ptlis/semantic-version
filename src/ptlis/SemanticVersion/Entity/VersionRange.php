<?php

/**
 * Entity to represent a semantic version number range.
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
 * Entity to represent a semantic version number range.
 */
class VersionRange
{
    /**
     * @var RangedVersion
     */
    private $upper;

    /**
     * @var RangedVersion
     */
    private $lower;


    /**
     * @param RangedVersion $lower
     *
     * @return VersionRange
     */
    public function setLower(RangedVersion $lower = null)
    {
        $this->lower = $lower;

        return $this;
    }


    /**
     * @return RangedVersion|null
     */
    public function getLower()
    {
        return $this->lower;
    }


    /**
     * @param RangedVersion $upper
     *
     * @return VersionRange
     */
    public function setUpper(RangedVersion $upper = null)
    {
        $this->upper = $upper;

        return $this;
    }


    /**
     * @return RangedVersion|null
     */
    public function getUpper()
    {
        return $this->upper;
    }


    /**
     * Returns a string representation of the version range.
     *
     * @return string
     */
    public function __toString()
    {
        $strRange = '';

        if (!is_null($this->lower)) {
            $strRange .= $this->lower->__toString();
        }

        if (!is_null($this->upper)) {
            $strRange .= $this->upper->__toString();
        }

        return trim($strRange);
    }
}
