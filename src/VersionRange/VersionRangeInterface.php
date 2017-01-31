<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\VersionRange;

use ptlis\SemanticVersion\Version\SatisfiedByVersionInterface;

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
