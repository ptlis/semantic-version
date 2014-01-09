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
    private $defaultLabel = 'ptlis\SemanticVersion\Label\LabelNone';

    /**
     * Mapping of label names to classes.
     *
     * @var array
     */
    private $labelList = [
        'alpha' => 'ptlis\SemanticVersion\Label\LabelAlpha',
        'beta'  => 'ptlis\SemanticVersion\Label\LabelBeta',
        'rc'    => 'ptlis\SemanticVersion\Label\LabelRc'
    ];


    /**
     * Adds a label type to factory.
     *
     * @throws \RuntimeException
     *
     * @param $type
     * @param $class
     */
    public function addType($type, $class)
    {
        if (!class_exists($class)) {
            throw new \RuntimeException(
                'The class "' . $class . '" does not exist'
            );
        }

        if (!((new $class()) instanceof LabelInterface)) {
            throw new \RuntimeException(
                'Labels must implement the ptlis\SemanticVersion\Label\LabelInterface interface'
            );
        }

        $this->labelList[$type] = $class;
    }


    /**
     * Remove a label type from factory.
     *
     * @param $type
     */
    public function removeType($type)
    {
        unset($this->labelList[$type]);
    }


    /**
     *
     *
     * @throws \RuntimeException
     *
     * @param array $labelList
     */
    public function setTypeList(array $labelList)
    {
        $this->clearTypeList();
        foreach ($labelList as $type => $class) {
            $this->addType($type, $class);
        }
    }


    /**
     * Clears the type list.
     */
    public function clearTypeList()
    {
        $this->labelList = [];
    }


    /**
     * Set the label to default to
     *
     * @param string $defaultLabel
     */
    public function setDefaultLabel($defaultLabel)
    {
        $this->defaultLabel = $defaultLabel;
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
