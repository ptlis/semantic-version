<?php

/**
 * ComparatorVersion equality comparator.
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
 * ComparatorVersion equality comparator.
 */
class EqualTo extends AbstractComparator
{
    /**
     * Retrieve the comparator's symbol.
     *
     * @return string
     */
    public static function getSymbol()
    {
        return '=';
    }


    /**
     * Return true if the ComparatorVersions match.
     *
     * @param ComparatorVersion $lCompVersion
     * @param ComparatorVersion $rCompVersion
     *
     * @return boolean
     */
    public function compare(ComparatorVersion $lCompVersion, ComparatorVersion $rCompVersion)
    {
        return ($lCompVersion->__toString() === $rCompVersion->__toString());
    }
}
