<?php

/**
 * ComparatorVersion less than or equal comparator.
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

/**
 * ComparatorVersion less than or equal comparator.
 */
class LessOrEqualTo extends AbstractComparator
{
    /**
     * Retrieve the comparator's symbol.
     *
     * @return string
     */
    public static function getSymbol()
    {
        return '<=';
    }


    /**
     * Return true if the left ComparatorVersion is less or equal to the right version.
     *
     * @param ComparatorVersion $lCompVersion
     * @param ComparatorVersion $rCompVersion
     *
     * @return boolean
     */
    public function compare(ComparatorVersion $lCompVersion, ComparatorVersion $rCompVersion)
    {
        $lessThan = new LessThan();
        $equalTo = new EqualTo();

        return ($lessThan->compare($lCompVersion, $rCompVersion) || $equalTo->compare($lCompVersion, $rCompVersion));
    }
}
