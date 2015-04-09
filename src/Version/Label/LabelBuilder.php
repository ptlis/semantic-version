<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2014-2015 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Version\Label;

/**
 * Immutable builder for label instances.
 */
class LabelBuilder
{
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
     * @param string $name
     * @param int|null $version
     */
    public function __construct($name = '', $version = null)
    {
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * Set label name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        return new LabelBuilder(
            $name,
            $this->version
        );
    }

    /**
     * Set label version.
     *
     * @param int|null $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        return new LabelBuilder(
            $this->name,
            $version
        );
    }

    /**
     * Build a label from the provided specification
     *
     * @return LabelInterface
     */
    public function build()
    {
        $labelMap = array(
            'alpha' => Label::PRECEDENCE_ALPHA,
            'beta' => Label::PRECEDENCE_BETA,
            'rc' => Label::PRECEDENCE_RC
        );

        // No Label present
        if (!strlen($this->name)) {
            $label = new Label(Label::PRECEDENCE_ABSENT);

        // Alpha, Beta & RC standard labels
        } elseif (array_key_exists($this->name, $labelMap)) {
            $label = new Label($labelMap[$this->name], $this->version);

        // Anything else is a miscellaneous 'dev' label
        } else {
            $label = new Label(Label::PRECEDENCE_DEV, $this->version, $this->name);
        }

        return $label;
    }
}
