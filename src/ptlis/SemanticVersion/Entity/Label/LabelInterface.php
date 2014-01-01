<?php

/**
 * Interface that version labels must implement.
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

namespace ptlis\SemanticVersion\Entity\Label;

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
     * Set the label version number;
     *
     * @param int|null $version
     *
     * @return LabelInterface
     */
    public function setVersion($version);


    /**
     * Get the precedence value for the lab (eg alpha (1) -> beta (2) -> rc (3) etc); greater values are later.
     *
     * @return int
     */
    public function getPrecedence();


    /**
     * Return a string representation of the label.
     *
     * @return string
     */
    public function __toString();
}
