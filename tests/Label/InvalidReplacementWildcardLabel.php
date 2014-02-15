<?php

namespace ptlis\SemanticVersion\Test\Label;

class InvalidReplacementWildcardLabel
{
    private $name;

    private $version;

    private $metadata;

    /**
     * Get the label name (eg 'alpha')
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the label version number
     *
     * @return int|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set the label version number;
     *
     * @param int|null $version
     *
     * @return LabelInterface
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }


    /**
     * Set the build metadata.
     *
     * @param string $metadata
     */
    public function setBuildMetaData($metadata)
    {
        $this->metadata = $metadata;
    }


    /**
     * Set the build metadata.
     *
     * @return string
     */
    public function getBuildMetaData()
    {
        return $this->metadata;
    }


    /**
     * Get the precedence value for the lab (eg alpha (1) -> beta (2) -> rc (3) etc); greater values are later.
     *
     * @return int
     */
    public function getPrecedence()
    {
        return 1;
    }

    /**
     * Sets the label name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    public function __toString()
    {
        return '';
    }
}
