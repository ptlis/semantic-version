<?php

/**
 * ComparatorVersion less than comparator.
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

namespace ptlis\SemanticVersion\ComparatorVersion\Comparator;

use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\OldVersion\Comparator\ComparatorInterface as VersionComparatorInterface;
use ptlis\SemanticVersion\OldVersion\Comparator\EqualTo as VersionEqualTo;
use ptlis\SemanticVersion\OldVersion\Comparator\LessThan as VersionLessThan;

/**
 * ComparatorVersion less than comparator.
 */
class LessThan extends AbstractComparator
{
    /**
     * Retrieve the comparator's symbol.
     *
     * @return string
     */
    public static function getSymbol()
    {
        return '<';
    }


    /**
     * Return true if the left ComparatorVersion is less than right version.
     *
     * @param ComparatorVersion $lCompVersion
     * @param ComparatorVersion $rCompVersion
     *
     * @return boolean
     */
    public function compare(ComparatorVersion $lCompVersion, ComparatorVersion $rCompVersion)
    {
        $versionLessThan = new VersionLessThan();
        $versionEqualTo = new VersionEqualTo();

        $lessThan = false;

        // Left version number less than right
        if ($versionLessThan->compare($lCompVersion->getVersion(), $rCompVersion->getVersion())) {
            $lessThan = true;

        // Otherwise compare comparator
        } elseif ($versionEqualTo->compare($lCompVersion->getVersion(), $rCompVersion->getVersion())
                && 1 === $this->compareComparators($lCompVersion->getComparator(), $rCompVersion->getComparator())) {
            $lessThan = true;
        }

        return $lessThan;
    }


    /**
     * Returns 1 if left comparator is (effectively) lower, 0 if equal and -1 if greater.
     *
     * Matrix used:
     *
     *          right
     *     +----+----+----+----+----+----+
     *     |    | <  | <= | =  | >= | >  |
     *     +----+----+----+----+----+----+
     *  l  | <  | 0  | 1  | 1  | 1  | 1  |
     *  e  | <= | -1 | 0  | 1  | 1  | 1  |
     *  f  | =  | -1 | -1 | 0  | 1  | 1  |
     *  t  | >= | -1 | -1 | -1 | 0  | 1  |
     *     | >  | -1 | -1 | -1 | -1 | 0  |
     *     +----+----+----+----+----+----+
     *
     * @param VersionComparatorInterface $lComp
     * @param VersionComparatorInterface $rComp
     *
     * @return integer
     */
    private function compareComparators(VersionComparatorInterface $lComp, VersionComparatorInterface $rComp)
    {
        // Lookup table for comparator comparisons, first index is left comparator & next is right.
        $lookupTable = array(
            '<'     => array(
                '<'     => 0,
                '<='    => 1,
                '='     => 1,
                '>='    => 1,
                '>'     => 1
            ),
            '<='    => array(
                '<'     => -1,
                '<='    => 0,
                '='     => 1,
                '>='    => 1,
                '>'     => 1
            ),
            '='    => array(
                '<'     => -1,
                '<='    => -1,
                '='     => 0,
                '>='    => 1,
                '>'     => 1
            ),
            '>='    => array(
                '<'     => -1,
                '<='    => -1,
                '='     => -1,
                '>='    => 0,
                '>'     => 1
            ),
            '>'     => array(
                '<'     => -1,
                '<='    => -1,
                '='     => -1,
                '>='    => -1,
                '>'     => 0
            )
        );

        return $lookupTable[$lComp->getSymbol()][$rComp->getSymbol()];
    }
}
