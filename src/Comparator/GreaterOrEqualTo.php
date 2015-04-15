<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2014-2015 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Comparator;

use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Version greater than equal or comparator.
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
     * Return true if the left version is greater or equal to the right version.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return boolean
     */
    public function compare(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        $greaterThan = new GreaterThan();
        $equalTo = new EqualTo();

        return ($greaterThan->compare($lVersion, $rVersion) || $equalTo->compare($lVersion, $rVersion));
    }
}
