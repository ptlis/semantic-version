<?php

/**
 * Interface class for regex provider to be used by factory to parse a comparator & version number.
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

namespace ptlis\SemanticVersion\ComparatorVersion;

/**
 * Interface class for regex provider to be used by factory to parse a comparator & version number.
 */
interface ComparatorVersionRegexProviderInterface
{
    /**
     * Get the regex to parse a bound comparator version.
     *
     * @return string
     */
    public function getComparatorVersion();
}
