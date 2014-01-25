<?php

/**
 * Entity to represent a semantic version number.
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
     * @param int|string $major
     *
     * @return Version
     */
    public function setMajor($major)
    {
        $filteredMajor = $this->validateVersionPart($major);

        if (false !== $filteredMajor) {
            $this->major = $filteredMajor;

            if ($filteredMajor === '*') {
                $this->setMinor('*');
            }
        } else {
            throw new InvalidVersionException(
                'Failed to set major version to invalid value "' . $major . '"'
            );
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
     * @param int|string $minor
     *
     * @return Version
     */
    public function setMinor($minor)
    {
        $filteredMinor = $this->validateVersionPart($minor);
        if (false !== $filteredMinor) {
            $this->minor = $filteredMinor;

            if ($filteredMinor === '*') {
                $this->setPatch('*');
            }
        } else {
            throw new InvalidVersionException(
                'Failed to set minor version to invalid value "' . $minor . '"'
            );
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
     * @param int|string $patch
     *
     * @return Version
     */
    public function setPatch($patch)
    {
        $filteredPatch = $this->validateVersionPart($patch);
        if (false !== $filteredPatch) {
            $this->patch = $filteredPatch;
        } else {
            throw new InvalidVersionException(
                'Failed to set patch version to invalid value "' . $patch . '"'
            );
        }

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
     * @param $versionPart
     *
     * @return string|false
     */
    private function validateVersionPart($versionPart)
    {
        $returnPart = false;

        if ($versionPart === 'x' || $versionPart === '*') {
            $returnPart = '*';
        } elseif (0 !== preg_match('/^0+$/', $versionPart)) {
            $returnPart = '0';
        } elseif (0 !== preg_match('/^[0-9]+$/', ltrim($versionPart, '0'))) {
            $returnPart = ltrim($versionPart, '0');
        }

        return $returnPart;
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
