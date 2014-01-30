<?php

/**
 * Interface that version labels must implement.
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

namespace ptlis\SemanticVersion\Label;

/**
 * Interface that version labels must implement.
 */
interface LabelInterface
{
    /**
     * Get the label name (eg 'alpha')
     *
     * @return string|null
     */
    public function getName();


    /**
     * Get the label version number
     *
     * @return int|null
     */
    public function getVersion();


    /**
     * Get the precedence value for the label (eg alpha (1) -> beta (2) -> rc (3) etc); greater values are later.
     *
     * @return int
     */
    public function getPrecedence();


    /**
     * Get the build metadata for the label.
     *
     * @return string
     */
    public function getBuildMetaData();


    /**
     * Return a string representation of the label.
     *
     * @return string
     */
    public function __toString();
}
