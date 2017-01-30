<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Comparator;

/**
 * Convenience class, implements shared __toString.
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
