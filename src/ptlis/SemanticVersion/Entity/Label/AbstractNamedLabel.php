<?php

/**
 * Abstract Class for Named labels.
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

namespace ptlis\SemanticVersion\Entity\Label;

/**
 * Abstract Class for Named labels.
 */
abstract class AbstractNamedLabel implements LabelInterface
{
    /**
     * @var int
     */
    private $version = 0;


    /**
     * Constructor
     *
     * @param int|null $version
     */
    public function __construct($version = null)
    {
        $this->version = $version;
    }


    /**
     * Get the label version number.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }


    /**
     * Set the label version number;
     *
     * @param int $version
     *
     * @return LabelRc
     */
    public function setVersion($version)
    {
        $this->version = (int)$version;

        return $this;
    }


    /**
     * Return a string representation of the label.
     *
     * @return string|null
     */
    public function __toString()
    {
        if ($this->getVersion() > 0) {
            return $this->getName() . '.' . $this->getVersion();
        } else {
            return $this->getName();
        }
    }
}
