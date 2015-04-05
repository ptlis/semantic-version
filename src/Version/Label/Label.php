<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2015 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Version\Label;

/**
 * Value types for labels in version numbers.
 */
class Label implements LabelInterface
{
    /**
     * @var int
     */
    private $precedence;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int|null
     */
    private $version;

    /**
     * @var string
     */
    private $buildMetadata;


    /**
     * Constructor.
     *
     * @param int $precedence
     * @param string $name
     * @param int|null $version
     * @param string $buildMetadata
     */
    public function __construct($precedence, $name = '', $version = null, $buildMetadata = '')
    {
        $this->precedence = $precedence;
        $this->name = $name;
        $this->version = $version;
        $this->buildMetadata = $buildMetadata;
    }

    /**
     * {@inheritDoc}
     */
    public function getPrecedence()
    {
        return $this->precedence;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritDoc}
     */
    public function getBuildMetadata()
    {
        return $this->buildMetadata;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        $string =  $this->getName();

        if ($this->getVersion() > 0) {
            $string .= '.' . $this->getVersion();
        }

        if (strlen($this->getBuildMetadata())) {
            $string .= '+' . $this->getBuildMetadata();
        }

        return $string;
    }
}
