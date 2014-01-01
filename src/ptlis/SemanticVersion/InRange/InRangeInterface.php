<?php

/**
 * Interface used to see if a version satisfies the requirements of the version range.
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

namespace ptlis\SemanticVersion\InRange;

use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Interface used to see if a version satisfies the requirements of the version range.
 */
interface InRangeInterface
{
    /**
     * Returns true if the provided version satisfies the requirements of the version range.
     *
     * @param VersionInterface $version
     *
     * @return boolean
     */
    public function isSatisfiedBy(VersionInterface $version);
}
