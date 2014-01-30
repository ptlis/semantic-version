<?php

/**
 * Sort functionality for BoundingPairCollection
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

use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Comparator\LessThan;

/**
 * Sort functionality for BoundingPairCollection
 */
class BoundingPairSort
{
    /**
     * Compare lower ComparatorVersions from BoundingPair.
     *
     * @param int                       $factor
     * @param ComparatorVersion|null    $lComp
     * @param ComparatorVersion|null    $rComp
     *
     * @return int
     */
    public function compareLower($factor, ComparatorVersion $lComp = null, ComparatorVersion $rComp = null)
    {
        return $this->sharedCompare(
            new LessThan(),
            1 * $factor,
            -1 * $factor,
            '>=',
            $lComp,
            $rComp
        );
    }


    /**
     * Compare upper ComparatorVersions from BoundingPair.
     *
     * @param int                       $factor
     * @param ComparatorVersion|null    $lComp
     * @param ComparatorVersion|null    $rComp
     *
     * @return int
     */
    public function compareUpper($factor, ComparatorVersion $lComp = null, ComparatorVersion $rComp = null)
    {
        return $this->sharedCompare(
            new GreaterThan(),
            -1 * $factor,
            1 * $factor,
            '<=',
            $lComp,
            $rComp
        );
    }


    /**
     * Shared comparison logic for upper & lower ComparatorVersions
     *
     * @param ComparatorInterface       $comparator
     * @param int                       $rightLess
     * @param int                       $rightGreater
     * @param string                    $symbol
     * @param ComparatorVersion|null    $lComp
     * @param ComparatorVersion|null    $rComp
     *
     * @return int
     */
    private function sharedCompare(
        ComparatorInterface $comparator,
        $rightLess,
        $rightGreater,
        $symbol,
        ComparatorVersion $lComp = null,
        ComparatorVersion $rComp = null
    ) {

        if (is_null($lComp) || is_null($rComp)) {
            $move = $this->compareWithNull($rightLess, $rightGreater, $lComp, $rComp);

        } elseif ($this->identical($lComp, $rComp)) {
            $move = 0;

        } else {
            $move = $this->compare($comparator, $rightLess, $rightGreater, $symbol, $lComp, $rComp);
        }

        return $move;
    }


    /**
     * Compare ComparatorVersions where one or both are null.
     *
     * @param int                       $rightLess
     * @param int                       $rightGreater
     * @param ComparatorVersion|null    $lComp
     * @param ComparatorVersion|null    $rComp
     *
     * @return int
     */
    private function compareWithNull(
        $rightLess,
        $rightGreater,
        ComparatorVersion $lComp = null,
        ComparatorVersion $rComp = null
    ) {
        $move = 0;
        if (!is_null($lComp)) {
            $move = $rightLess;

        } elseif (!is_null($rComp)) {
            $move = $rightGreater;
        }

        return $move;
    }


    /**
     * Compare ComparatorVersions
     *
     * @param ComparatorInterface   $comparator
     * @param int                   $rightLess
     * @param int                   $rightGreater
     * @param string                $symbol
     * @param ComparatorVersion     $lComp
     * @param ComparatorVersion     $rComp
     *
     * @return int
     */
    private function compare(
        ComparatorInterface $comparator,
        $rightLess,
        $rightGreater,
        $symbol,
        ComparatorVersion $lComp,
        ComparatorVersion $rComp
    ) {
        $move = 0;

        // Right version less/greater than left
        if ($comparator->compare($rComp->getVersion(), $lComp->getVersion())) {
            $move = $rightLess;

        // Left version less/greater than right
        } elseif ($comparator->compare($lComp->getVersion(), $rComp->getVersion())) {
            $move = $rightGreater;

        // Versions match, right comparator effectively less/greater
        } elseif ($rComp->getComparator()->getSymbol() === $symbol) {
            $move = $rightLess;

        // Versions match, left comparator effectively less/greater
        } elseif ($lComp->getComparator()->getSymbol() === $symbol) {
            $move = $rightGreater;
        }

        return $move;
    }


    /**
     * Returns true of the comparator versions are identical
     *
     * @param ComparatorVersion $lComp
     * @param ComparatorVersion $rComp
     *
     * @return bool
     */
    private function identical(ComparatorVersion $lComp, ComparatorVersion $rComp)
    {
        $identical = false;
        if ($lComp->__toString() === $rComp->__toString()) {
            $identical = true;
        }

        return $identical;
    }
}
