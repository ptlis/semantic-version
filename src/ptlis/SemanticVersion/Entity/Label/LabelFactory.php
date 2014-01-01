<?php

/**
 * Factory to create Labels.
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

namespace ptlis\SemanticVersion\Entity\Label;

/**
 * Factory to create Labels.
 */
class LabelFactory
{
    /**
     * @var LabelNone
     */
    private $defaultLabel;

    /**
     * Mapping of label names to classes.
     *
     * @var array
     */
    private $labelList;


    /**
     * Constructor
     *
     * @param array|null $labelList Override default labels.
     */
    public function __construct(array $labelList = null)
    {
        // Override defaults
        if (!is_null($labelList) && is_array($labelList) && count($labelList)) {
            $this->labelList = $labelList;

            // Keep defaults
        } else {
            $this->labelList = [
                'alpha' => 'ptlis\SemanticVersion\Entity\Label\LabelAlpha',
                'beta'  => 'ptlis\SemanticVersion\Entity\Label\LabelBeta',
                'rc'    => 'ptlis\SemanticVersion\Entity\Label\LabelRc'
            ];
        }

        $this->defaultLabel = 'ptlis\SemanticVersion\Entity\Label\LabelNone';
    }


    /**
     * Get a label class from the name.
     *
     * @param string    $name
     * @param int|null  $version
     *
     * @return LabelInterface
     */
    public function get($name, $version = null)
    {
        if (array_key_exists($name, $this->labelList)) {
            $label = new $this->labelList[$name]();
            $label->setVersion($version);
        } else {
            $label = new $this->defaultLabel();
        }

        return $label;
    }
}
