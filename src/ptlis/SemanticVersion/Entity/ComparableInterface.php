<?php

/**
 * Interface for comparable versions.
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

namespace ptlis\SemanticVersion\Entity;

/**
 * Interface for comparable versions.
 */
interface ComparableInterface
{
    /**
     * Check to see if the two comparable items are equal.
     *
     * @param ComparableInterface $comparable
     *
     * @return bool
     */
    public function equalTo(ComparableInterface $comparable);


    /**
     * Returns a string representation of the comparable object.
     *
     * @return string
     */
    public function __toString();
}
