<?php

/**
 * Interface used to see if a version satisfies the requirements of the bounding pair.
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

namespace ptlis\SemanticVersion\InRange;

use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Interface used to see if a version satisfies the requirements of the bounding pair.
 */
interface InRangeInterface
{
    /**
     * Returns true if the provided version satisfies the requirements of the bounding pair.
     *
     * @param VersionInterface $version
     *
     * @return boolean
     */
    public function isSatisfiedBy(VersionInterface $version);
}
