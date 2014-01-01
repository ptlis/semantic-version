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

namespace ptlis\SemanticVersion\Entity;

use ptlis\SemanticVersion\Entity\Label\LabelInterface;

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
     * @param LabelInterface $label
     *
     * @return Version
     */
    public function setLabel(LabelInterface $label)
    {
        $this->label = (string)$label;

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
