<?php

/**
 * Abstract class for ComparatorVersion comparators.
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

/**
 * Abstract class for ComparatorVersion comparators.
 */
abstract class AbstractComparator implements ComparatorInterface
{
    /**
     * Return the string representation of the comparator
     *
     * @return string
     */
    public function __toString()
    {
        return $this::getSymbol();
    }
}
