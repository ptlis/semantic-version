<?php

/**
 * BoundingPair greater than equal or comparator.
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
 * BoundingPair greater than equal or comparator.
 */
class GreaterOrEqualTo extends AbstractComparator
{
    /**
     * Retrieve the comparator's symbol.
     *
     * @return string
     */
    public static function getSymbol()
    {
        return '>=';
    }


    /**
     * Return true if the left BoundingPair is greater or equal to the right.
     *
     * @param BoundingPair $lPair
     * @param BoundingPair $rPair
     *
     * @return boolean
     */
    public function compare(BoundingPair $lPair, BoundingPair $rPair)
    {
        $greaterThan = new GreaterThan();
        $equalTo = new EqualTo();

        return ($greaterThan->compare($lPair, $rPair) || $equalTo->compare($lPair, $rPair));
    }
}
