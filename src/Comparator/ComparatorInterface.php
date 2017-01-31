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
 * Interface that version comparators must implement.
 */
interface ComparatorInterface
{
    /**
     * Retrieve the comparator's symbol.
     *
     * @return string
     */
    public static function getSymbol();

    /**
     * Compare the provided versions using the appropriate method for the comparator.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return boolean
     */
    public function compare(VersionInterface $lVersion, VersionInterface $rVersion);

    /**
     * Return a string representation of the comparator.
     *
     * @return string
     */
    public function __toString();
}
