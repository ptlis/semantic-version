<?php

/**
 * Entity to represent a semantic version number.
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

use ptlis\SemanticVersion\Exception\InvalidVersionException;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Label\LabelInterface;

/*
 * Entity to represent a semantic version number.
 */
class Version implements VersionInterface
{
    /**
     * @var string
     */
    private $major;

    /**
     * @var string
     */
    private $minor = 0;

    /**
     * @var string
     */
    private $patch = 0;

    /**
     * @var LabelInterface
     */
    private $label;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->label = new LabelAbsent();
    }


    /**
     * @param LabelInterface $label
     *
     * @return Version
     */
    public function setLabel(LabelInterface $label)
    {
        $this->label = $label;

        return $this;
    }


    /**
     * @return LabelInterface
     */
    public function getLabel()
    {
        return $this->label;
    }


    /**
     * @throws InvalidVersionException
     *
     * @param string $major
     *
     * @return Version
     */
    public function setMajor($major)
    {
        $filteredMajor = $this->normalizeVersionPart($major, 'major');

        $this->major = $filteredMajor;

        if ($filteredMajor === '*') {
            $this->setMinor('*');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getMajor()
    {
        return $this->major;
    }


    /**
     * @throws InvalidVersionException
     *
     * @param string $minor
     *
     * @return Version
     */
    public function setMinor($minor)
    {
        $filteredMinor = $this->normalizeVersionPart($minor, 'minor');

        $this->minor = $filteredMinor;

        if ($filteredMinor === '*') {
            $this->setPatch('*');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getMinor()
    {
        return $this->minor;
    }


    /**
     * @throws InvalidVersionException
     *
     * @param string $patch
     *
     * @return Version
     */
    public function setPatch($patch)
    {
        $filteredPatch = $this->normalizeVersionPart($patch, 'patch');

        $this->patch = $filteredPatch;

        return $this;
    }


    /**
     * @return string
     */
    public function getPatch()
    {
        return $this->patch;
    }


    /**
     * Validates the provided version part, on success returns the normalised value on failure returns false.
     *
     * @throws InvalidVersionException
     *
     * @param string $versionPart
     * @param string $partName
     *
     * @return string|false
     */
    private function normalizeVersionPart($versionPart, $partName)
    {
        if ($versionPart === 'x' || $versionPart === '*') {
            $returnPart = '*';

        } elseif (0 !== preg_match('/^0+$/', $versionPart)) {
            $returnPart = '0';

        } elseif (0 !== preg_match('/^[0-9]+$/', ltrim($versionPart, '0'))) {
            $returnPart = ltrim($versionPart, '0');

        } else {
            throw new InvalidVersionException(
                'Failed to set ' . $partName . ' version to invalid value "' . $versionPart . '"'
            );
        }

        if (!$this->maxIntCheck($versionPart)) {
            throw new InvalidVersionException(
                ucwords($partName) . ' version number is larger than PHP\'s max int "' . $versionPart . '"'
            );
        }

        return $returnPart;
    }


    /**
     * Test to ensure that the version part is less than PHP's max int.
     *
     * @param string $versionPart
     *
     * @return boolean
     */
    private function maxIntCheck($versionPart)
    {
        $valid = true;

        // Value must be less than PHP's maximum integer size.
        if (($versionPart !== '*' || $versionPart !== 'x')
                && (intval($versionPart) != $versionPart || intval($versionPart) === PHP_INT_MAX)) {
            $valid = false;
        }

        return $valid;
    }


    /**
     * Returns a string representation of the version number.
     *
     * @return string
     */
    public function __toString()
    {
        $strVersion = $this->major;
        if (strlen($this->minor)) {
            $strVersion .= '.' . $this->minor;

            if (strlen($this->patch)) {
                $strVersion .= '.' . $this->patch;

                if (strlen($this->label)) {
                    $strVersion .= '-' . $this->label;
                }
            }
        }

        return (string)$strVersion;
    }


    /**
     * Deep clone.
     */
    public function __clone()
    {
        $this->label = clone $this->label;
    }
}