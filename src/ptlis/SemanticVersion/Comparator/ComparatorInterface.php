<?php

/**
 * Interface that comparators must implement.
 *
 * PHP Version 5.4
 *
 * @copyright   (c) 2014 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Comparator;

/**
 * Interface that comparators must implement.
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
}
