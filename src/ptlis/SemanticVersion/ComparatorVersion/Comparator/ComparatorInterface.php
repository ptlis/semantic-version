<?php

/**
 * Interface that ComparatorVersion comparators must implement.
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
 * Interface that ComparatorVersion comparators must implement.
 */
interface ComparatorInterface
{
    /**
     * Return a string representation of the comparator.
     *
     * @return string
     */
    public function __toString();

    /**
     * Retrieve the comparator's symbol.
     *
     * @return string
     */
    public static function getSymbol();

    /**
     * Compare the provided versions using the appropriate method for the comparator.
     *
     * @param ComparatorVersion $lCompVersion
     * @param ComparatorVersion $rCompVersion
     *
     * @return boolean
     */
    public function compare(ComparatorVersion $lCompVersion, ComparatorVersion $rCompVersion);
}
