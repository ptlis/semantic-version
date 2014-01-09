<?php

/**
 * Class representing the absence of a label.
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

namespace ptlis\SemanticVersion\Label;

/**
 * Class representing the absence of a label.
 */
class LabelAbsent implements LabelAbsentInterface
{
    /**
     * No label.
     *
     * @return null
     */
    public function getName()
    {
        return null;
    }


    /**
     * Get the label version number.
     *
     * @return null
     */
    public function getVersion()
    {
        return null;
    }


    /**
     * Absence of a label is the highest precedence.
     *
     * @return int
     */
    public function getPrecedence()
    {
        return 5;
    }


    /**
     * Get the build metadata.
     *
     * @return string
     */
    public function getBuildMetaData()
    {
        return '';
    }


    /**
     * Return a string representation of the label.
     *
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}
