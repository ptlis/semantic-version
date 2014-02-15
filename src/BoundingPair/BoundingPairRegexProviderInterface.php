<?php

/**
 * Interface class for regex provider to be used by factory to parse a version number range.
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

namespace ptlis\SemanticVersion\BoundingPair;

/**
 * Interface class for regex provider to be used by factory to parse a version number range.
 */
interface BoundingPairRegexProviderInterface
{
    /**
     * Get the regex to parse a bound pair of comparator versions.
     *
     * @return string
     */
    public function getBoundingPair();
}
