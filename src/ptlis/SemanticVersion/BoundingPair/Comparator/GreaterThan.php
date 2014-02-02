<?php

/**
 * BoundingPair greater than comparator.
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

namespace ptlis\SemanticVersion\BoundingPair\Comparator;

use ptlis\SemanticVersion\BoundingPair\BoundingPair;
use ptlis\SemanticVersion\ComparatorVersion\Comparator\EqualTo as CompEqualTo;
use ptlis\SemanticVersion\ComparatorVersion\Comparator\GreaterThan as CompGreaterThan;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;

/**
 * BoundingPair greater than comparator.
 */
class GreaterThan extends AbstractComparator
{
    /**
     * Retrieve the comparator's symbol.
     *
     * @return string
     */
    public static function getSymbol()
    {
        return '>';
    }


    /**
     * Return true if the left BoundingPair is greater than right.
     *
     * @param BoundingPair $lPair
     * @param BoundingPair $rPair
     *
     * @return boolean
     */
    public function compare(BoundingPair $lPair, BoundingPair $rPair)
    {
        $greater = $this->compareUpper($lPair->getUpper(), $rPair->getUpper());
        if ($greater === 0) {
            $greater = $this->compareLower($lPair->getLower(), $rPair->getLower());
        }

        return $greater === 1;
    }


    /**
     * Compare the upper ComparatorVersions to find whether the left one is greater.
     *
     * @param ComparatorVersion|null $lCompVersion
     * @param ComparatorVersion|null $rCompVersion
     *
     * @return int
     */
    private function compareUpper(ComparatorVersion $lCompVersion = null, ComparatorVersion $rCompVersion = null)
    {
        $greater = 0;

        // Left null (effectively infinite version)
        if ($this->leftOnlyNull($lCompVersion, $rCompVersion)) {
            $greater = 1;

        // Right null (effectively infinite version)
        } elseif ($this->rightOnlyNull($lCompVersion, $rCompVersion)) {
            $greater = -1;

        // Neither null, left is greater
        } elseif ($this->nullSafeGreaterThan($lCompVersion, $rCompVersion)) {
            $greater = 1;
        }

        return $greater;
    }


    /**
     * Compare the upper ComparatorVersions to find whether the left one is greater.
     *
     * @param ComparatorVersion|null $lCompVersion
     * @param ComparatorVersion|null $rCompVersion
     *
     * @return int
     */
    private function compareLower(ComparatorVersion $lCompVersion = null, ComparatorVersion $rCompVersion = null)
    {
        $greater = 0;

        // Left null (effectively negative infinite version)
        if ($this->leftOnlyNull($lCompVersion, $rCompVersion)) {
            $greater = -1;

        // Right null (effectively negative infinite version)
        } elseif ($this->rightOnlyNull($lCompVersion, $rCompVersion)) {
            $greater = 1;

        // Neither null, left is greater
        } elseif ($this->nullSafeGreaterThan($lCompVersion, $rCompVersion)) {
            $greater = 1;
        }

        return $greater;
    }


    /**
     * Returns true if the left ComparatorVersion only is null (null being equivalent to infinite version number)
     *
     * @param ComparatorVersion|null $lCompVersion
     * @param ComparatorVersion|null $rCompVersion
     *
     * @return boolean
     */
    private function leftOnlyNull(ComparatorVersion $lCompVersion = null, ComparatorVersion $rCompVersion = null)
    {
        $null = false;
        if (is_null($lCompVersion) && !is_null($rCompVersion)) {
            $null = true;
        }

        return $null;
    }


    /**
     * Returns true if the right ComparatorVersion only is null (null being equivalent to infinite version number)
     *
     * @param ComparatorVersion|null $lCompVersion
     * @param ComparatorVersion|null $rCompVersion
     *
     * @return boolean
     */
    private function rightOnlyNull(ComparatorVersion $lCompVersion = null, ComparatorVersion $rCompVersion = null)
    {
        $null = false;
        if (!is_null($lCompVersion) && is_null($rCompVersion)) {
            $null = true;
        }

        return $null;
    }


    /**
     * Returns true if neither ComparatorVersion is null & the left is greater than the right.
     *
     * @param ComparatorVersion $lCompVersion
     * @param ComparatorVersion $rCompVersion
     *
     * @return boolean
     */
    private function nullSafeGreaterThan(ComparatorVersion $lCompVersion = null, ComparatorVersion $rCompVersion = null)
    {
        $compGreaterThan = new CompGreaterThan();

        $null = false;
        if (!is_null($lCompVersion) && !is_null($rCompVersion)
                && $compGreaterThan->compare($lCompVersion, $rCompVersion)) {
            $null = true;
        }

        return $null;
    }
}
