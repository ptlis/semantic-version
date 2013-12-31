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

/*
 * Entity to represent a semantic version number.
 */
class Version
{
    const LABEL_ALPHA = 1;
    const LABEL_BETA = 2;
    const LABEL_RC = 3;
    const LABEL_NONE = 4;

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
     * @var string
     */
    private $label;

    /**
     * @var int
     */
    private $labelNumber = 0;

    /**
     * @var int
     */
    private $labelPrecedence = self::LABEL_NONE;


    /**
     * @param string $label
     *
     * @return Version
     */
    public function setLabel($label)
    {
        $this->label = (string)$label;

        return $this;
    }


    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }


    /**
     * @param int $labelNumber
     *
     * @return Version
     */
    public function setLabelNumber($labelNumber)
    {
        $this->labelNumber = (int)$labelNumber;

        return $this;
    }


    /**
     * @return int
     */
    public function getLabelNumber()
    {
        return $this->labelNumber;
    }


    /**
     * @param int $labelPrecedence
     *
     * @return Version
     */
    public function setLabelPrecedence($labelPrecedence)
    {
        $this->labelPrecedence = (int)$labelPrecedence;

        return $this;
    }


    /**
     * @return int
     */
    public function getLabelPrecedence()
    {
        return $this->labelPrecedence;
    }


    /**
     * @param int $major
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
     * @return int
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
     * @return int
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
     * @return int
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
