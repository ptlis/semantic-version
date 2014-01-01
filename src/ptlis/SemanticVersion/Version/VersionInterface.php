<?php

/**
 * Interface class for version number entities.
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

namespace ptlis\SemanticVersion\Version;

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
     * @param int|string $major
     *
     * @return VersionInterface
     */
    public function setMajor($major);


    /**
     * Get the major version number; may be '*' for wildcard
     *
     * @return int
     */
    public function getMajor();


    /**
     * Set the minor version number; may be '*' for wildcard
     *
     * @param int|string $minor
     *
     * @return VersionInterface
     */
    public function setMinor($minor);


    /**
     * Get the minor version number; may be '*' for wildcard
     *
     * @return int|string
     */
    public function getMinor();


    /**
     * Set the calculated patch number.
     *
     * @param int|string $patch
     *
     * @return VersionInterface
     */
    public function setPatch($patch);


    /**
     * Returns the calculated patch number; 0 if no patch provided.
     *
     * @return int|string
     */
    public function getPatch();


    /**
     * Return true if the two comparable items are equal.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    public function equalTo(VersionInterface $version);


    /**
     * Return true if the current instance is less than the passed instance.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    public function lessThan(VersionInterface $version);


    /**
     * Return true if the current instance is less or equal to the passed instance.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    public function lessOrEqualTo(VersionInterface $version);


    /**
     * Return true if the current instance is greater than the passed instance.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    public function greaterThan(VersionInterface $version);


    /**
     * Return true if the current instance is greater or equal to the passed instance.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    public function greaterOrEqualTo(VersionInterface $version);


    /**
     * Returns a string representation of the version number.
     *
     * @return string
     */
    public function __toString();
}
