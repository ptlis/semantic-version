<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Version;

use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Label\LabelInterface;

/**
 * Value type for simple version numbers.
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
     * Constructor.
     *
     * If no label is passed then an absent label type will be created & used.
     *
     * @param int $major
     * @param int $minor
     * @param int $patch
     * @param LabelInterface|null $label
     */
    public function __construct($major, $minor = 0, $patch = 0, LabelInterface $label = null)
    {
        if (is_null($label)) {
            $label = new Label(Label::PRECEDENCE_ABSENT);
        }

        $this->major = intval($major);
        $this->minor = intval($minor);
        $this->patch = intval($patch);
        $this->label = $label;
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy(VersionInterface $version)
    {
        return strval($this) === strval($version);
    }

    /**
     * {@inheritDoc}
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * {@inheritDoc}
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * {@inheritDoc}
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        $strVersion = implode(
            '.',
            array(
                $this->major,
                $this->minor,
                $this->patch
            )
        );

        // Only include the label portion if not absent
        if (LabelInterface::PRECEDENCE_ABSENT !== $this->label->getPrecedence()) {
            $strVersion .= '-' . $this->label;
        }

        return $strVersion;
    }
}
