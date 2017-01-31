<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Comparator;

use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Version greater than comparator.
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
     * Return true if the left version is greater than right version.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return boolean
     */
    public function compare(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        $lessThan = new LessThan();

        return $lessThan->compare($rVersion, $lVersion);
    }
}
