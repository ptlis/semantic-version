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
 * Interface used to see if a version satisfied the encoded requirements.
 */
interface SatisfiedByVersionInterface
{
    /**
     * Returns true if the provided version satisfies the requirements encoded in the class instance.
     *
     * @param VersionInterface $version
     *
     * @return boolean
     */
    public function isSatisfiedBy(VersionInterface $version);
}
