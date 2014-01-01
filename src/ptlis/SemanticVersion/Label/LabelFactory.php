<?php

/**
 * Factory to create Labels.
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
            // TODO: Not in ctor, validate classes (plus add / remove functions?)
            $this->labelList = $labelList;

        // Keep defaults
        } else {
            $this->labelList = [
                'alpha' => 'ptlis\SemanticVersion\Label\LabelAlpha',
                'beta'  => 'ptlis\SemanticVersion\Label\LabelBeta',
                'rc'    => 'ptlis\SemanticVersion\Label\LabelRc'
            ];
        }

        $this->defaultLabel = 'ptlis\SemanticVersion\Label\LabelNone';
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
        } else {
            $label = new $this->defaultLabel();
            $version = null;
        }
        $label->setVersion($version);

        return $label;
    }
}
