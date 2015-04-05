<?php

/**
 * Interface class for regex provider to be used by factory to parse a version number.
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

namespace ptlis\SemanticVersion\OldVersion;

/**
 * Interface class for regex provider to be used by factory to parse a version number.
 */
interface VersionRegexProviderInterface
{
    /**
     * Get the regex to parse a semantic version number.
     *
     * @return string
     */
    public function getVersion();
}
