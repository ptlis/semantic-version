<?php

/**
 * BoundingPair less than or equal comparator.
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

/**
 * BoundingPair less than or equal comparator.
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
     * Return true if the left BoundingPair is less or equal to the right.
     *
     * @param BoundingPair $lPair
     * @param BoundingPair $rPair
     *
     * @return boolean
     */
    public function compare(BoundingPair $lPair, BoundingPair $rPair)
    {
        $lessThan = new LessThan();
        $equalTo = new EqualTo();

        return ($lessThan->compare($lPair, $rPair) || $equalTo->compare($lPair, $rPair));
    }
}
