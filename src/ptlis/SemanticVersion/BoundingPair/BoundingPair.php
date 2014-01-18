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

namespace ptlis\SemanticVersion\BoundingPair;

use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Exception\InvalidBoundingPairException;
use ptlis\SemanticVersion\InRange\InRangeInterface;
use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Entity to represent a semantic version number range.
 */
class BoundingPair implements InRangeInterface
{
    /**
     * @var ComparatorVersion|null
     */
    private $upper;

    /**
     * @var ComparatorVersion|null
     */
    private $lower;


    /**
     * @throws InvalidBoundingPairException
     *
     * @param ComparatorVersion|null $lower
     *
     * @return BoundingPair
     */
    public function setLower(ComparatorVersion $lower = null)
    {
        if (!is_null($this->upper) && (!$this->upper->isSatisfiedBy($lower->getVersion())
                || !$lower->isSatisfiedBy($this->upper->getVersion()) )) {
            throw new InvalidBoundingPairException(
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
     * @throws InvalidBoundingPairException
     *
     * @param ComparatorVersion|null $upper
     *
     * @return BoundingPair
     */
    public function setUpper(ComparatorVersion $upper = null)
    {
        if (!is_null($this->lower) && (!$this->lower->isSatisfiedBy($upper->getVersion())
                || !$upper->isSatisfiedBy($this->lower->getVersion()) )) {
            throw new InvalidBoundingPairException(
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
     * @throws InvalidBoundingPairException
     *
     * @param ComparatorVersion $version1
     * @param ComparatorVersion $version2
     *
     * @return $this
     */
    public function setUpperLower(ComparatorVersion $version1, ComparatorVersion $version2)
    {
        $equalTo = new EqualTo();
        $greaterThan = new GreaterThan();

        // If the versions are equal it doesn't matter
        if ($equalTo->compare($version1->getVersion(), $version2->getVersion())) {
            $upper = $version1;
            $lower = $version2;

        // $version1 is greater
        } elseif ($greaterThan->compare($version1->getVersion(), $version2->getVersion())) {
            $upper = $version1;
            $lower = $version2;

        // $version2 is greater
        } else {
            $upper = $version2;
            $lower = $version1;
        }

        if (!$upper->isSatisfiedBy($lower->getVersion()) || !$lower->isSatisfiedBy($upper->getVersion())) {
            throw new InvalidBoundingPairException(
                'The provided versions conflict.'
            );
        }

        $this->upper = $upper;
        $this->lower = $lower;

        return $this;
    }


    /**
     * Returns a string representation of the bounding pair.
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
     * Returns true if the provided version satisfies the requirements of the bounding pair.
     *
     * @param VersionInterface $version
     *
     * @return boolean
     */
    public function isSatisfiedBy(VersionInterface $version)
    {
        $satisfied = false;

        // Upper & Lower set, both are satisfied by version
        if ($this->lowerSatisfiedBy($version) && $this->upperSatisfiedBy($version)) {
            $satisfied = true;

        // Lower set only, is satisfied by version
        } elseif (is_null($this->upper) && $this->lowerSatisfiedBy($version)) {
            $satisfied = true;

        // Upper set only, is satisfied by version
        } elseif (is_null($this->lower) && $this->upperSatisfiedBy($version)) {
            $satisfied = true;
        }
        return $satisfied;
    }


    /**
     * Returns true if the upper comparator version is satisfied by the passed version.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    private function lowerSatisfiedBy(VersionInterface $version)
    {
        return !is_null($this->lower) && $this->lower->isSatisfiedBy($version);
    }


    /**
     * Returns true if the lower comparator version is satisfied by the passed version.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    private function upperSatisfiedBy(VersionInterface $version)
    {
        return !is_null($this->upper) && $this->upper->isSatisfiedBy($version);
    }
}
