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

use ptlis\SemanticVersion\Version\Label\LabelInterface;

/**
 * Interface class for version number value types.
 */
interface VersionInterface extends SatisfiedByVersionInterface
{
    /**
     * @return LabelInterface
     */
    public function getLabel();

    /**
     * Get the major version number.
     *
     * @return int
     */
    public function getMajor();

    /**
     * Get the minor version number; may be '*' for wildcard
     *
     * @return int
     */
    public function getMinor();

    /**
     * Returns the calculated patch number; 0 if no patch provided.
     *
     * @return int
     */
    public function getPatch();

    /**
     * Returns a string representation of the version number.
     *
     * @return string
     */
    public function __toString();
}
