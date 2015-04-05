<?php

/**
 * Interface class for version number entities.
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

use ptlis\SemanticVersion\Label\LabelInterface;

/**
 * Interface class for version number entities.
 */
interface VersionInterface
{
    /**
     * @param LabelInterface $label
     *
     * @return VersionInterface
     */
    public function setLabel(LabelInterface $label);


    /**
     * @return LabelInterface
     */
    public function getLabel();


    /**
     * Set the major version number; may be '*' for wildcard
     *
     * @param string $major
     *
     * @return VersionInterface
     */
    public function setMajor($major);


    /**
     * Get the major version number; may be '*' for wildcard
     *
     * @return string
     */
    public function getMajor();


    /**
     * Set the minor version number; may be '*' for wildcard
     *
     * @param string $minor
     *
     * @return VersionInterface
     */
    public function setMinor($minor);


    /**
     * Get the minor version number; may be '*' for wildcard
     *
     * @return string
     */
    public function getMinor();


    /**
     * Set the calculated patch number.
     *
     * @param string $patch
     *
     * @return VersionInterface
     */
    public function setPatch($patch);


    /**
     * Returns the calculated patch number; 0 if no patch provided.
     *
     * @return string
     */
    public function getPatch();


    /**
     * Returns a string representation of the version number.
     *
     * @return string
     */
    public function __toString();
}