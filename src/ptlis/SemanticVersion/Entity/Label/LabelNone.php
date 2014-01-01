<?php

/**
 * Class representing the absence of a label.
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

namespace ptlis\SemanticVersion\Entity\Label;

/**
 * Class representing the absence of a label.
 */
class LabelNone implements LabelInterface
{
    /**
     * No label.
     *
     * @return string|null
     */
    public function getName()
    {
        return null;
    }


    /**
     * Get the label version number.
     *
     * @return int|null
     */
    public function getVersion()
    {
        return null;
    }


    /**
     * Set the label version number;
     *
     * @param int|null $version
     *
     * @return LabelNone
     */
    public function setVersion($version)
    {
        return $this;
    }


    /**
     * Absence of a label is the highest precedence.
     *
     * @return int
     */
    public function getPrecedence()
    {
        return 4;
    }


    /**
     * Return a string representation of the label.
     *
     * @return string|null
     */
    public function __toString()
    {
        return null;
    }
}
