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
     * @var string
     */
    private $wildcardLabel = 'ptlis\SemanticVersion\Label\LabelDev';

    /**
     * @var string
     */
    private $absentLabel = 'ptlis\SemanticVersion\Label\LabelAbsent';


    /**
     * Mapping of label names to classes.
     *
     * @var array
     */
    private $labelList = array(
        'alpha' => 'ptlis\SemanticVersion\Label\LabelAlpha',
        'beta'  => 'ptlis\SemanticVersion\Label\LabelBeta',
        'rc'    => 'ptlis\SemanticVersion\Label\LabelRc'
    );


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
     * @param string[] $labelList
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
        $this->labelList = array();
    }


    /**
     * Set the label to use for wildcard matching (labels not in list)
     *
     * @throws \RuntimeException
     *
     * @param string $class
     */
    public function setWildcardLabel($class)
    {
        if (!class_exists($class)) {
            throw new \RuntimeException(
                'The class "' . $class . '" does not exist'
            );
        }

        if (!((new $class()) instanceof LabelWildcardInterface)) {
            throw new \RuntimeException(
                'Wildcard labels must implement the ptlis\SemanticVersion\Label\LabelWildcardInterface interface'
            );
        }

        $this->wildcardLabel = $class;
    }


    /**
     * Set the label to use for label being absent.
     *
     * @throws \RuntimeException
     *
     * @param string $class
     */
    public function setAbsentLabel($class)
    {
        if (!class_exists($class)) {
            throw new \RuntimeException(
                'The class "' . $class . '" does not exist'
            );
        }

        if (!((new $class()) instanceof LabelAbsentInterface)) {
            throw new \RuntimeException(
                'Absent labels must implement the ptlis\SemanticVersion\Label\LabelAbsentInterface interface'
            );
        }

        $this->absentLabel = $class;
    }


    /**
     * Get a label class from the name.
     *
     * @param string    $name
     * @param int|null  $version
     * @param string    $metadata
     *
     * @return LabelInterface
     */
    public function get($name, $version = null, $metadata = null)
    {
        if (strlen($name)) {
            if (array_key_exists($name, $this->labelList)) {
                $label = new $this->labelList[$name]();
            } else {
                $label = new $this->wildcardLabel();
                $label->setName($name);
            }
            $label->setVersion($version);
            $label->setBuildMetaData($metadata);

        } else {
            $label = new $this->absentLabel();
            $version = null;
        }

        return $label;
    }
}
