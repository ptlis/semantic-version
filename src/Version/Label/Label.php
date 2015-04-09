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
use Symfony\Component\Yaml\Exception\RuntimeException;

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
     * Constructor.
     *
     * @param int $precedence
     * @param string $name
     * @param int|null $version
     */
    public function __construct($precedence, $name = '', $version = null)
    {
        $this->precedence = $precedence;
        $this->name = $name;
        $this->version = $version;
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
    public function __toString()
    {
        $string =  $this->getName();

        if ($this->getVersion() > 0) {
            $string .= '.' . $this->getVersion();
        }

        return $string;
    }
}
