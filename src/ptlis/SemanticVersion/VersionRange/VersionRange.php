<?php

/**
 * Entity to represent a semantic version number range.
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

namespace ptlis\SemanticVersion\VersionRange;

use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Exception\InvalidVersionRangeException;
use ptlis\SemanticVersion\InRange\InRangeInterface;
use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Entity to represent a semantic version number range.
 */
class VersionRange implements InRangeInterface
{
    /**
     * @var ComparatorVersion
     */
    private $upper;

    /**
     * @var ComparatorVersion
     */
    private $lower;


    /**
     * @throws InvalidVersionRangeException
     *
     * @param ComparatorVersion $lower
     *
     * @return VersionRange
     */
    public function setLower(ComparatorVersion $lower = null)
    {
        if (!is_null($this->upper) && (!$this->upper->isSatisfiedBy($lower->getVersion())
                || !$lower->isSatisfiedBy($this->upper->getVersion()) )) {
            throw new InvalidVersionRangeException(
                'The provided version is outside the bounds allowed by the upper bound.'
            );
        }

        $this->lower = $lower;

        return $this;
    }


    /**
     * @return ComparatorVersion|null
     */
    public function getLower()
    {
        return $this->lower;
    }


    /**
     * @throws InvalidVersionRangeException
     *
     * @param ComparatorVersion $upper
     *
     * @return VersionRange
     */
    public function setUpper(ComparatorVersion $upper = null)
    {
        if (!is_null($this->lower) && (!$this->lower->isSatisfiedBy($upper->getVersion())
                || !$upper->isSatisfiedBy($this->lower->getVersion()) )) {
            throw new InvalidVersionRangeException(
                'The provided version is outside the bounds allowed by the lower bound.'
            );
        }

        $this->upper = $upper;

        return $this;
    }


    /**
     * @return ComparatorVersion|null
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

        if (!is_null($this->lower) && !is_null($this->upper)
                && $this->lower->__toString() == $this->upper->__toString()) {
            $strRange = $this->lower->__toString();
        } else {
            if (!is_null($this->lower)) {
                $strRange .= $this->lower->__toString();
            }

            if (!is_null($this->upper)) {
                $strRange .= $this->upper->__toString();
            }
        }

        return trim($strRange);
    }


    /**
     * Returns true if the provided version satisfies the requirements of the version range.
     *
     * @param VersionInterface $version
     *
     * @return boolean
     */
    public function isSatisfiedBy(VersionInterface $version)
    {
        $satisfied = false;

        // Upper & Lower set, both are satisfied by version
        if (!is_null($this->lower) && $this->lower->isSatisfiedBy($version)
                && !is_null($this->upper) && $this->upper->isSatisfiedBy($version)) {
            $satisfied = true;

        // Lower set only, is satisfied by version
        } elseif (is_null($this->upper) && !is_null($this->lower) && $this->lower->isSatisfiedBy($version)) {
            $satisfied = true;

        // Upper set only, is satisfied by version
        } elseif (is_null($this->lower) && !is_null($this->upper) && $this->upper->isSatisfiedBy($version)) {
            $satisfied = true;
        }
        return $satisfied;
    }
}
