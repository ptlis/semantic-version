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

namespace ptlis\SemanticVersion\Entity;

use ptlis\SemanticVersion\Entity\Label\LabelInterface;
use ptlis\SemanticVersion\Entity\Label\LabelNone;

/*
 * Entity to represent a semantic version number.
 */
class Version implements VersionInterface
{
    /**
     * @var int
     */
    private $major;

    /**
     * @var int
     */
    private $minor = 0;

    /**
     * @var int
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
        $this->label = new LabelNone();
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
     * @param int|string $major
     *
     * @return Version
     */
    public function setMajor($major)
    {
        if ($major === 'x' || $major === '*') {
            $this->major = '*';
            $this->setMinor('*');
        } else {
            $this->major = (int)$major;
        }

        return $this;
    }


    /**
     * @return int|string
     */
    public function getMajor()
    {
        return $this->major;
    }


    /**
     * @param int|string $minor
     *
     * @return Version
     */
    public function setMinor($minor)
    {
        if ($minor === 'x' || $minor === '*') {
            $this->minor = '*';
            $this->setPatch('*');
        } else {
            $this->minor = (int)$minor;
        }

        return $this;
    }


    /**
     * @return int|string
     */
    public function getMinor()
    {
        return $this->minor;
    }


    /**
     * @param int|string $patch
     *
     * @return Version
     */
    public function setPatch($patch)
    {
        if ($patch === 'x' || $patch === '*') {
            $this->patch = '*';
        } else {
            $this->patch = (int)$patch;
        }

        return $this;
    }


    /**
     * @return int|string
     */
    public function getPatch()
    {
        return $this->patch;
    }


    /**
     * Return true if the provided versions match
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    public function equalTo(VersionInterface $version)
    {
        return ($this->__toString() == $version->__toString());
    }


    /**
     * Return true if the current instance is less than the passed instance.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    public function lessThan(VersionInterface $version)
    {
        switch (true) {
            case ($this->getMajor() < $version->getMajor()):
                $lessThan = true;
                break;

            case ($this->getMajor() == $version->getMajor()
                    && $this->getMinor() < $version->getMinor()):
                $lessThan = true;
                break;

            case ($this->getMajor() == $version->getMajor()
                    && $this->getMinor() == $version->getMinor()
                    && $this->getPatch() < $version->getPatch()):
                $lessThan = true;
                break;

            case ($this->getMajor() == $version->getMajor()
                    && $this->getMinor() == $version->getMinor()
                    && $this->getPatch() == $version->getPatch()
                    && $this->getLabel()->getPrecedence() < $version->getLabel()->getPrecedence()):
                $lessThan = true;
                break;

            case ($this->getMajor() == $version->getMajor()
                    && $this->getMinor() == $version->getMinor()
                    && $this->getPatch() == $version->getPatch()
                    && $this->getLabel()->getPrecedence() == $version->getLabel()->getPrecedence()
                    && $this->getLabel()->getVersion() < $version->getLabel()->getVersion()):
                $lessThan = true;
                break;

            default:
                $lessThan = false;
                break;
        }

        return $lessThan;
    }


    /**
     * Return true if the current instance is less or equal to the passed instance.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    public function lessOrEqualTo(VersionInterface $version)
    {
        return $this->equalTo($version) || $this->lessThan($version);
    }


    /**
     * Return true if the current instance is greater than the passed instance.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    public function greaterThan(VersionInterface $version)
    {
        return $version->lessThan($this);
    }


    /**
     * Return true if the current instance is greater or equal to the passed instance.
     *
     * @param VersionInterface $version
     *
     * @return bool
     */
    public function greaterOrEqualTo(VersionInterface $version)
    {
        return $this->equalTo($version) || $this->greaterThan($version);
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
}
