<?php

namespace tests\Label;

use ptlis\SemanticVersion\Label\LabelAbsentInterface;

class ReplacementAbsentLabel implements LabelAbsentInterface
{

    /**
     * Get the label name (eg 'alpha')
     *
     * @return string|null
     */
    public function getName()
    {
        return '';
    }

    /**
     * Get the label version number
     *
     * @return int|null
     */
    public function getVersion()
    {
        return '';
    }


    /**
     * Set the build metadata.
     *
     * @return string
     */
    public function getBuildMetaData()
    {
        return '';
    }


    /**
     * Get the precedence value for the lab (eg alpha (1) -> beta (2) -> rc (3) etc); greater values are later.
     *
     * @return int
     */
    public function getPrecedence()
    {
        return 5;
    }


    public function __toString()
    {
        return '';
    }
}