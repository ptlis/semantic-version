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
     * @var string
     */
    private $buildMetadata;


    /**
     * Constructor.
     *
     * @param string $name
     * @param int|null $version
     * @param string $buildMetadata
     */
    public function __construct($name = '', $version = null, $buildMetadata = '')
    {
        $this->name = $name;
        $this->version = $version;
        $this->buildMetadata = $buildMetadata;
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
            $this->version,
            $this->buildMetadata
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
            $version,
            $this->buildMetadata
        );
    }

    /**
     * Set build metadata.
     *
     * @param string $buildMetadata
     *
     * @return $this
     */
    public function setBuildMetadata($buildMetadata)
    {
        return new LabelBuilder(
            $this->name,
            $this->version,
            $buildMetadata
        );
    }

    /**
     * Build a label from the provided specification
     *
     * @return LabelInterface
     */
    public function build()
    {
        $label = null;

        switch ($this->name) {
            case '':
                $label = new LabelAbsent();
                break;

            case 'alpha':
                $label = new LabelAlpha($this->version, $this->buildMetadata);
                break;

            case 'beta':
                $label = new LabelBeta($this->version, $this->buildMetadata);
                break;

            case 'rc':
                $label = new LabelRc($this->version, $this->buildMetadata);
                break;

            default:
                $label = new LabelDev($this->name, $this->version, $this->buildMetadata);
                break;
        }

        return $label;
    }
}
