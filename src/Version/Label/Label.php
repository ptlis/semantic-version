<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
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
     * @var int|null
     */
    private $version;

    /**
     * @var string
     */
    private $name;


    /**
     * Constructor.
     *
     * @param int $precedence
     * @param int|null $version
     * @param string $name
     */
    public function __construct($precedence, $version = null, $name = '')
    {
        $precedenceToNameMap = [
            self::PRECEDENCE_ALPHA => 'alpha',
            self::PRECEDENCE_BETA => 'beta',
            self::PRECEDENCE_RC => 'rc'
        ];

        $this->precedence = $precedence;
        $this->version = $version;

        if (array_key_exists($precedence, $precedenceToNameMap)) {
            $this->name = $precedenceToNameMap[$precedence];
        } else {
            $this->name = $name;
        }
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
        return strval($this->name);
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
