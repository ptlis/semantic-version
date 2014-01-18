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

namespace ptlis\SemanticVersion\Label;

/**
 * Abstract Class for Named labels.
 */
abstract class AbstractNamedLabel implements LabelPresentInterface
{
    /**
     * @var int
     */
    private $version = 0;

    /**
     * @var string|null
     */
    private $metadata;


    /**
     * Constructor
     *
     * @param int           $version
     * @param string|null   $metadata
     */
    public function __construct($version = 0, $metadata = null)
    {
        $this->version = $version;
        $this->metadata = $metadata;
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
     * Set the build metadata.
     *
     * @param string $metadata
     *
     * @return LabelDev
     */
    public function setBuildMetaData($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }


    /**
     * Set the build metadata.
     *
     * @return string|null
     */
    public function getBuildMetaData()
    {
        return $this->metadata;
    }


    /**
     * Return a string representation of the label.
     *
     * @return string|null
     */
    public function __toString()
    {
        if ($this->getVersion() > 0) {
            $string =  $this->getName() . '.' . $this->getVersion();
        } else {
            $string =  $this->getName();
        }

        if (strlen($this->metadata)) {
            $string .= '+' . $this->metadata;
        }

        return $string;
    }
}
