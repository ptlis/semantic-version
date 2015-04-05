<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2015 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Version;

/**
 * Interface class for version range value types.
 */
interface VersionRangeInterface extends SatisfiedByVersionInterface
{
    /**
     * Returns a string representation of the version range.
     *
     * @return string
     */
    public function __toString();
}
